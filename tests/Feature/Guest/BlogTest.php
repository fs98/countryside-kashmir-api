<?php

namespace Tests\Feature\Guest;

use App\Models\Author;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index function.
     *
     * @return void
     */
    public function test_blogs_can_be_retrieved_for_guest_user()
    {
        $admin = Role::create(['name' => 'Admin']);
        $superAdmin = Role::create(['name' => 'Super Admin']);

        Blog::factory(5)
            ->for(User::factory()->hasAttached($superAdmin)->create())
            ->for(Author::factory()->create())
            ->create();

        $response = $this->get('/api/guest/blogs');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'title',
                            'slug',
                            'image_alt',
                            'content',
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
    public function test_blogs_can_be_retrieved_by_slug_for_guest_user()
    {
        $admin = Role::create(['name' => 'Admin']);
        $superAdmin = Role::create(['name' => 'Super Admin']);

        $blog = Blog::factory()
            ->for(User::factory()->hasAttached($superAdmin)->create())
            ->for(Author::factory()->create())
            ->create();

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
        ]);

        $response = $this->get('/api/guest/blogs/' . $blog->slug);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'title',
                        'slug',
                        'image_alt',
                        'content',
                        'image_url'
                    ]
                ]
            );
    }
}
