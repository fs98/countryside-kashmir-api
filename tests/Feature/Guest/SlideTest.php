<?php

namespace Tests\Feature\Guest;

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
                            'image_alt',
                            'title',
                            'subtitle',
                            'image_url'
                        ]
                    ]
                ]
            );
    }
}
