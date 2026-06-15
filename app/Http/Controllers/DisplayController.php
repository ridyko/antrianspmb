<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Counter;
use App\Models\CallHistory;
use App\Models\Ticket;

class DisplayController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        $counters = Counter::orderBy('sort_order')->get();
        $last_call = CallHistory::latest('id')->first();
        
        return view('display', compact('settings', 'counters', 'last_call'));
    }

    public function state(Request $request)
    {
        $settings = Setting::pluck('value', 'key')->all();
        $counters = Counter::orderBy('sort_order')->get();
        $waiting_count = Ticket::where('status', 'waiting')->count();
        
        $last_call_id = $request->query('last_call_id');
        $new_calls = [];
        
        $latest_call = CallHistory::latest('id')->first();
        
        if ($last_call_id !== null && $last_call_id !== '') {
            $new_calls = CallHistory::where('id', '>', $last_call_id)
                ->orderBy('id', 'asc')
                ->get();
        }
        
        return response()->json([
            'settings' => $settings,
            'counters' => $counters,
            'last_call' => $latest_call,
            'new_calls' => $new_calls,
            'waiting_count' => $waiting_count
        ]);
    }
}
