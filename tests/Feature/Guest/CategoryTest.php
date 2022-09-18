<?php

namespace Tests\Feature\Guest;

use App\Models\Author;
use App\Models\Category;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index function.
     *
     * @return void
     */
    public function test_categories_can_be_retrieved_for_guest_user()
    {
        $admin = Role::create(['name' => 'Admin']);
        $superAdmin = Role::create(['name' => 'Super Admin']);

        Category::factory(2)
            ->has(
                Package::factory()
                    ->for(User::factory()->hasAttached($superAdmin)->create())
                    ->for(Author::factory()->create())
                    ->for(Category::factory()->create())
                    ->count(1)
            )
            ->create();


        $response = $this->get('/api/guest/categories');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'name',
                            'slug',
                            'packages'
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
    public function test_categories_can_be_retrieved_by_id_for_guest_user()
    {
        $admin = Role::create(['name' => 'Admin']);
        $superAdmin = Role::create(['name' => 'Super Admin']);

        $category = Category::factory()
            ->has(
                Package::factory()
                    ->for(User::factory()->hasAttached($superAdmin)->create())
                    ->for(Author::factory()->create())
                    ->for(Category::factory()->create())
                    ->count(1)
            )
            ->create();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);

        $response = $this->get('/api/guest/categories/' . $category->id);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'name',
                        'slug',
                        'packages'
                    ]
                ]
            );
    }
}
