<?php

namespace Tests\Feature;

use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SlideTest extends TestCase
{
    /**
     * Test index function.
     *
     * @return void
     */
    public function test_slides_can_be_retrieved()
    {
        $role = Role::create(['name' => 'Admin']);

        // Create admin user
        $user = User::factory()
            ->hasAttached($role)
            ->create();

        Slide::factory(5)->create();

        /**
         * 
         * @var User $user
         */
        $response = $this->actingAs($user)
            ->get('/api/slides');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id',
                            'image_alt',
                            'order',
                            'title',
                            'subtitle',
                            'created_at',
                            'updated_at',
                            'image_url'
                        ]
                    ],
                    'links' => [
                        'first',
                        'last',
                        'prev',
                        'next'
                    ],
                    'meta' => [
                        'current_page',
                        'from',
                        'last_page',
                        'links' => [
                            [
                                'url',
                                'label',
                                'active'
                            ]
                        ],
                        'path',
                        'per_page',
                        'to',
                        'total'
                    ]

                ]
            );
    }
}
