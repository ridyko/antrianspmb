<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Setting;
use App\Models\Counter;
use App\Models\CallHistory;

class DisplayStateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic settings
        Setting::create(['key' => 'header_title', 'value' => 'Test Title']);
        Setting::create(['key' => 'header_subtitle', 'value' => 'Test Subtitle']);
        Setting::create(['key' => 'header_address', 'value' => 'Test Address']);
        Setting::create(['key' => 'marquee_text', 'value' => 'Test Marquee']);
        Setting::create(['key' => 'media_type', 'value' => 'video']);
        Setting::create(['key' => 'speech_rate', 'value' => '1.0']);
        Setting::create(['key' => 'speech_pitch', 'value' => '1.0']);

        // Seed counters
        Counter::create(['name' => 'LOKET 1', 'room' => 'LOKET 1', 'sort_order' => 1]);
        Counter::create(['name' => 'LOKET 2', 'room' => 'LOKET 2', 'sort_order' => 2]);
    }

    public function test_state_endpoint_without_last_call_id_returns_empty_new_calls()
    {
        // Create call histories
        $call1 = CallHistory::create(['counter_id' => 1, 'ticket_number' => 5, 'room' => 'LOKET 1']);
        $call2 = CallHistory::create(['counter_id' => 2, 'ticket_number' => 6, 'room' => 'LOKET 2']);

        $response = $this->getJson(route('display.state'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'settings',
                'counters',
                'last_call',
                'new_calls',
                'waiting_count'
            ])
            ->assertJsonPath('last_call.id', $call2->id)
            ->assertJsonCount(0, 'new_calls');
    }

    public function test_state_endpoint_with_last_call_id_returns_subsequent_calls_chronologically()
    {
        // Create multiple call histories representing concurrent/sequential calls
        $call1 = CallHistory::create(['counter_id' => 1, 'ticket_number' => 10, 'room' => 'LOKET 1']);
        $call2 = CallHistory::create(['counter_id' => 2, 'ticket_number' => 11, 'room' => 'LOKET 2']);
        $call3 = CallHistory::create(['counter_id' => 1, 'ticket_number' => 12, 'room' => 'LOKET 1']);

        // Query with last_call_id pointing to the first call (call1)
        $response = $this->getJson(route('display.state', ['last_call_id' => $call1->id]));

        $response->assertStatus(200)
            ->assertJsonCount(2, 'new_calls')
            // Verify order is ascending (chronological: call2 then call3)
            ->assertJsonPath('new_calls.0.id', $call2->id)
            ->assertJsonPath('new_calls.0.ticket_number', 11)
            ->assertJsonPath('new_calls.1.id', $call3->id)
            ->assertJsonPath('new_calls.1.ticket_number', 12);
    }

    public function test_state_endpoint_with_zero_last_call_id_returns_all_calls()
    {
        $call1 = CallHistory::create(['counter_id' => 1, 'ticket_number' => 10, 'room' => 'LOKET 1']);
        $call2 = CallHistory::create(['counter_id' => 2, 'ticket_number' => 11, 'room' => 'LOKET 2']);

        $response = $this->getJson(route('display.state', ['last_call_id' => '0']));

        $response->assertStatus(200)
            ->assertJsonCount(2, 'new_calls')
            ->assertJsonPath('new_calls.0.id', $call1->id)
            ->assertJsonPath('new_calls.1.id', $call2->id);
    }
}
