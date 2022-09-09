<?php

namespace Tests\Feature;

use App\Models\Slide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SlideTest extends TestCase
{
    /**
     * Test index function.
     *
     * @return void
     */
    public function test_sliders_can_be_retrieved_for_guest_user()
    {
        Slide::factory(5)->create();

        $response = $this->get('/api/guest/slides');

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
                    ]
                ]
            );
    }
}
