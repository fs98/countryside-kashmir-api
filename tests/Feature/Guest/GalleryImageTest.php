<?php

namespace Tests\Feature\Guest;

use App\Models\GalleryImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GalleryImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index function.
     *
     * @return void
     */
    public function test_gallery_images_can_be_retrieved()
    {
        $user = User::factory()->create();

        GalleryImage::create([
            'image' => fake()->imageUrl(),
            'image_alt' => fake()->sentence($nbWords = 3, $variableNbWords = true),
            'user_id' => $user->id
        ]);

        $response = $this->get('/api/guest/gallery-images');

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id',
                            'image_alt',
                            'user_id',
                            'deleted_at',
                            'created_at',
                            'updated_at',
                            'image_url'
                        ]
                    ],
                ]
            );
    }

    /**
     * Test show function.
     *
     * @return void
     */
    public function test_gallery_image_can_be_retrieved_by_id()
    {
        $user = User::factory()->create();

        $galleryImage = GalleryImage::create([
            'image' => fake()->imageUrl(),
            'image_alt' => fake()->sentence($nbWords = 3, $variableNbWords = true),
            'user_id' => $user->id
        ]);

        $response = $this->get('/api/guest/gallery-images/' . $galleryImage->id);

        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'image_alt',
                        'user_id',
                        'deleted_at',
                        'created_at',
                        'updated_at',
                        'image_url'
                    ]
                ]
            );
    }
}
