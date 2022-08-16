<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index function.
     *
     * @return void
     */
    public function test_authors_can_be_retrieved()
    {
        Author::factory(5)->create();

        $response = $this->get('/api/authors');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id',
                            'name',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            );
    }

    /**
     * Test store function.
     *
     * @return void
     */
    public function test_new_user_can_be_added()
    {
        $response = $this->post('/api/authors', [
            'name' => 'Aubrey Farrell'
        ]);

        $response->assertStatus(200)->assertJsonStructure(
            [
                'success',
                'data' => [
                    'name',
                    'updated_at',
                    'created_at',
                    'id'
                ],
                'message'
            ]
        );

        $this->assertDatabaseHas('authors', [
            'name' => 'Aubrey Farrell',
        ]);
    }

    /**
     * Test show function.
     *
     * @return void
     */
    public function test_author_can_be_retrieved_by_id()
    {
        $author = Author::factory()->create();

        $response = $this->get('/api/authors/' . $author->id);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at'
                    ]
                ]
            );
    }
}
