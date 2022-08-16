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

    /**
     * Test update function.
     *
     * @return void
     */
    public function test_user_can_be_updated()
    {
        $author = Author::factory()->create([
            'name' => 'Aubrey'
        ]);

        $response = $this->put('/api/authors/' . $author->id, [
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
        ])->assertDatabaseMissing('authors', [
            'name' => 'Aubrey',
        ]);
    }

    /**
     * Test destroy function.
     *
     * @return void
     */
    public function test_user_can_be_deleted()
    {
        $author = Author::factory()->create([
            'name' => 'Aubrey'
        ]);

        $this->assertDatabaseHas('authors', [
            'name' => 'Aubrey',
        ]);

        $response = $this->delete('/api/authors/' . $author->id);

        $response->assertStatus(200)->assertJsonStructure(
            [
                'success',
                'data' => [
                    'id',
                    'name',
                    'updated_at',
                    'created_at'
                ],
                'message'
            ]
        );

        $this->assertDatabaseMissing('authors', [
            'name' => 'Aubrey',
        ]);
    }

    /**
     * Test data is validated.
     *
     * @return void
     */
    public function test_new_author_data_is_validated()
    {
        $response = $this->post('/api/authors', [
            'name' => ''
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'name'
            ]);
    }

    /**
     * Test data is validated for unique author name.
     *
     * @return void
     */
    public function test_author_with_the_same_name_cannot_be_added()
    {
        Author::factory()->create([
            'name' => 'Aubrey Farrell'
        ]);

        $response = $this->post('/api/authors', [
            'name' => 'Aubrey Farrell'
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'name'
            ]);
    }
}
