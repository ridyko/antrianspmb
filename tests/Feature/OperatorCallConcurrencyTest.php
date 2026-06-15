<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Setting;
use App\Models\Counter;
use App\Models\Ticket;
use App\Models\CallHistory;

class OperatorCallConcurrencyTest extends TestCase
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

        // Seed counters
        Counter::create(['id' => 1, 'name' => 'LOKET 1', 'room' => 'LOKET 1', 'sort_order' => 1]);
        Counter::create(['id' => 2, 'name' => 'LOKET 2', 'room' => 'LOKET 2', 'sort_order' => 2]);
        Counter::create(['id' => 3, 'name' => 'LOKET 3', 'room' => 'LOKET 3', 'sort_order' => 3]);
    }

    public function test_concurrent_call_resolves_tickets_sequentially_without_duplicate_calling()
    {
        // Create waiting tickets
        $ticket18 = Ticket::create(['ticket_number' => 18, 'status' => 'waiting']);
        $ticket19 = Ticket::create(['ticket_number' => 19, 'status' => 'waiting']);

        // Call next for Counter 2
        $response1 = $this->postJson(route('operator.call-next'), ['counter_id' => 2]);
        $response1->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('ticket_number', 18);

        // Call next for Counter 3
        $response2 = $this->postJson(route('operator.call-next'), ['counter_id' => 3]);
        $response2->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('ticket_number', 19); // Should correctly fetch 19, not 18!

        // Assert ticket assignments in database
        $this->assertEquals('called', $ticket18->fresh()->status);
        $this->assertEquals(2, $ticket18->fresh()->counter_id);

        $this->assertEquals('called', $ticket19->fresh()->status);
        $this->assertEquals(3, $ticket19->fresh()->counter_id);

        // Assert Call histories exist for both
        $this->assertDatabaseHas('call_histories', [
            'counter_id' => 2,
            'ticket_number' => 18
        ]);
        $this->assertDatabaseHas('call_histories', [
            'counter_id' => 3,
            'ticket_number' => 19
        ]);
    }
}
