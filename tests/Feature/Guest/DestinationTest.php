<?php

namespace Tests\Feature\Guest;

use App\Models\Author;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DestinationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index function.
     *
     * @return void
     */
    public function test_destinations_can_be_retrieved_for_guest_user()
    {
        $admin = Role::create(['name' => 'Admin']);
        $superAdmin = Role::create(['name' => 'Super Admin']);

        Destination::factory(5)
            ->for(User::factory()->hasAttached($superAdmin)->create())
            ->for(Author::factory()->create())
            ->create();

        $response = $this->get('/api/guest/destinations');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'name',
                            'slug',
                            'image_alt',
                            'image_url'
                        ]
                    ]
                ]
            );
    }

    /**
     * Test show function.
     *
     * @return void
     */
    public function test_destinations_can_be_retrieved_by_id_for_guest_user()
    {
        $admin = Role::create(['name' => 'Admin']);
        $superAdmin = Role::create(['name' => 'Super Admin']);

        $destination = Destination::factory()
            ->for(User::factory()->hasAttached($superAdmin)->create())
            ->for(Author::factory()->create())
            ->create();

        $this->assertDatabaseHas('destinations', [
            'id' => $destination->id,
        ]);

        $response = $this->get('/api/guest/destinations/' . $destination->id);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'name',
                        'slug',
                        'description',
                        'image_alt',
                        'keywords',
                        'image_url',
                        'destination_images'
                    ]
                ]
            );
    }
}
