<?php

namespace Tests\Feature\Guest;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * Unauthenticated user can send a message.
     *
     * @return void
     */
    public function test_guest_can_send_a_message()
    {
        $response = $this->post('/api/guest/messages', [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'content' => fake()->text(100),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'first_name',
                    'last_name',
                    'phone_number',
                    'email',
                    'content',
                    'updated_at',
                    'created_at',
                    'id'
                ],
                'message',
            ]);
    }

    /**
     * Test data is validated.
     *
     * @return void
     */
    public function test_new_message_data_is_validated()
    {
        $response = $this->post('/api/guest/messages', [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'content' => fake()->text(100),
        ]);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [
                    'phone_number'
                ]
            ]);
    }
}
