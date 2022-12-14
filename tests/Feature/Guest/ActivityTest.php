<?php

namespace Tests\Feature\Guest;

use App\Models\Activity;
use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index function.
     *
     * @return void
     */
    public function test_activities_can_be_retrieved_for_guest_user()
    {
        $admin = Role::create(['name' => 'Admin']);
        $superAdmin = Role::create(['name' => 'Super Admin']);

        Activity::factory(5)
            ->for(User::factory()->hasAttached($superAdmin)->create())
            ->for(Author::factory()->create())
            ->create();

        $response = $this->get('/api/guest/activities');

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
    public function test_activities_can_be_retrieved_by_slug_for_guest_user()
    {
        $admin = Role::create(['name' => 'Admin']);
        $superAdmin = Role::create(['name' => 'Super Admin']);

        $activity = Activity::factory()
            ->for(User::factory()->hasAttached($superAdmin)->create())
            ->for(Author::factory()->create())
            ->create();


        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
        ]);

        $response = $this->get('/api/guest/activities/' . $activity->slug);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'name',
                        'slug',
                        'image_alt',
                        'image_url'
                    ]
                ]
            );
    }
}
