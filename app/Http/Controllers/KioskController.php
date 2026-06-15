<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Ticket;
use Carbon\Carbon;

class KioskController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('kiosk', compact('settings'));
    }

    public function issue(Request $request)
    {
        // Get the latest ticket number
        $latestTicket = Ticket::max('ticket_number') ?? 0;
        $nextNumber = $latestTicket + 1;

        $ticket = Ticket::create([
            'ticket_number' => $nextNumber,
            'status' => 'waiting',
        ]);

        return response()->json([
            'success' => true,
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'formatted_number' => str_pad($ticket->ticket_number, 3, '0', STR_PAD_LEFT),
            'date' => Carbon::now()->translatedFormat('d F Y'),
            'time' => Carbon::now()->format('H:i:s'),
        ]);
    }
}
