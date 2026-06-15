<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Counter;
use App\Models\Ticket;
use App\Models\CallHistory;

class OperatorController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        $counters = Counter::orderBy('sort_order')->get();
        return view('operator', compact('settings', 'counters'));
    }

    public function callNext(Request $request)
    {
        $request->validate([
            'counter_id' => 'required|exists:counters,id',
        ]);

        $counter_id = $request->counter_id;
        $counter = Counter::findOrFail($counter_id);

        // Run atomic transaction to find and lock the next ticket
        $ticket = \DB::transaction(function () use ($counter_id, $counter) {
            $ticket = Ticket::where('status', 'waiting')
                ->orderBy('ticket_number', 'asc')
                ->lockForUpdate()
                ->first();

            if (!$ticket) {
                return null;
            }

            // Update ticket status
            $ticket->status = 'called';
            $ticket->counter_id = $counter_id;
            $ticket->save();

            // Update counter state
            $counter->current_call_number = $ticket->ticket_number;
            $counter->save();

            // Log call history for TV Display
            CallHistory::create([
                'counter_id' => $counter->id,
                'ticket_number' => $ticket->ticket_number,
                'room' => $counter->room,
            ]);

            return $ticket;
        });

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada antrean menunggu.',
            ]);
        }

        return response()->json([
            'success' => true,
            'ticket_number' => $ticket->ticket_number,
            'formatted_number' => str_pad($ticket->ticket_number, 3, '0', STR_PAD_LEFT),
            'counter_name' => $counter->name,
            'room' => $counter->room,
        ]);
    }

    public function recall(Request $request)
    {
        $request->validate([
            'counter_id' => 'required|exists:counters,id',
        ]);

        $counter = Counter::findOrFail($request->counter_id);

        if (!$counter->current_call_number) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada antrean yang dipanggil di loket ini.',
            ]);
        }

        // Re-log call history to trigger TV speech and animation
        CallHistory::create([
            'counter_id' => $counter->id,
            'ticket_number' => $counter->current_call_number,
            'room' => $counter->room,
        ]);

        return response()->json([
            'success' => true,
            'ticket_number' => $counter->current_call_number,
            'formatted_number' => str_pad($counter->current_call_number, 3, '0', STR_PAD_LEFT),
            'counter_name' => $counter->name,
            'room' => $counter->room,
        ]);
    }

    public function stats()
    {
        $total = Ticket::count();
        $waiting = Ticket::where('status', 'waiting')->count();
        $called = Ticket::where('status', 'called')->count();
        $counters = Counter::orderBy('sort_order')->get()->map(function($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'room' => $c->room,
                'current_call_number' => $c->current_call_number,
                'formatted_number' => $c->current_call_number ? str_pad($c->current_call_number, 3, '0', STR_PAD_LEFT) : '--',
            ];
        });

        return response()->json([
            'total' => $total,
            'waiting' => $waiting,
            'called' => $called,
            'counters' => $counters,
        ]);
    }
}
