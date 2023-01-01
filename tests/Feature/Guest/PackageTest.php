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

class PackageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test show function.
     *
     * @return void
     */
    public function test_packages_can_be_retrieved_by_slug_for_guest_user()
    {
        $admin = Role::create(['name' => 'Admin']);
        $superAdmin = Role::create(['name' => 'Super Admin']);

        $package = Package::factory()
            ->for(User::factory()->hasAttached($superAdmin)->create())
            ->for(Author::factory()->create())
            ->for(Category::factory()->create())
            ->create();

        $this->assertDatabaseHas('packages', [
            'id' => $package->id,
        ]);

        $response = $this->get('/api/guest/packages/' . $package->slug);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'name',
                        'slug',
                        'description',
                        'image_alt',
                        'days',
                        'nights',
                        'price',
                        'persons',
                        'keywords',
                        'image_url',
                        'category',
                        'destinations',
                        'package_images'
                    ]
                ]
            );
    }
}
