<?php

namespace Tests\Feature;

use App\Models\Flyer;
use App\Models\LandingSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingFlyerSectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_save_flyer_section_text_with_colored_segments(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->put(route('admin.landing.update_flyer_section'), [
                'flyer_section_title' => 'Temukan Jalur Belajar',
                'flyer_title_segments_json' => json_encode([
                    ['text' => 'Temukan ', 'color' => ''],
                    ['text' => 'Jalur', 'color' => '#F59E0B'],
                    ['text' => ' Belajar', 'color' => ''],
                ]),
                'flyer_section_description' => 'Pilih program yang sesuai.',
                'flyer_description_segments_json' => json_encode([
                    ['text' => 'Pilih program ', 'color' => ''],
                    ['text' => 'yang sesuai.', 'color' => '#2563EB'],
                ]),
            ]);

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'flyer']));
        $this->assertSame([
            ['text' => 'Temukan ', 'color' => null],
            ['text' => 'Jalur', 'color' => '#F59E0B'],
            ['text' => ' Belajar', 'color' => null],
        ], LandingSetting::flyerTitleSegments());
        $this->assertSame([
            ['text' => 'Pilih program ', 'color' => null],
            ['text' => 'yang sesuai.', 'color' => '#2563EB'],
        ], LandingSetting::flyerDescriptionSegments());
    }

    public function test_flyer_section_update_rejects_invalid_text_colors(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->put(route('admin.landing.update_flyer_section'), [
                'flyer_section_title' => 'Judul aman',
                'flyer_title_segments_json' => json_encode([
                    ['text' => 'Judul aman', 'color' => 'red; background:url(javascript:alert(1))'],
                ]),
                'flyer_section_description' => 'Deskripsi aman',
                'flyer_description_segments_json' => json_encode([
                    ['text' => 'Deskripsi aman', 'color' => ''],
                ]),
            ]);

        $response->assertSessionHasErrors('flyer_title_segments.0.color');
        $this->assertDatabaseMissing('landing_settings', [
            'key' => LandingSetting::FLYER_TITLE_SEGMENTS_KEY,
        ]);
    }

    public function test_guest_cannot_update_flyer_section_text(): void
    {
        $response = $this->put(route('admin.landing.update_flyer_section'), []);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('landing_settings', [
            'key' => LandingSetting::FLYER_TITLE_SEGMENTS_KEY,
        ]);
    }

    public function test_public_can_open_full_flyer_page_and_return_to_landing(): void
    {
        $flyer = Flyer::create([
            'title' => 'Program Leadership',
            'thumbnail_image' => 'flyers/thumbs/leadership.jpg',
            'full_image' => 'flyers/full/leadership.jpg',
        ]);

        $response = $this->get(route('flyers.show', $flyer));

        $response->assertOk();
        $response->assertSee('Program Leadership');
        $response->assertSee(route('landing.public'));
        $response->assertSee(asset('storage/flyers/full/leadership.jpg'));
    }

    public function test_public_landing_renders_configured_flyer_section_text_safely(): void
    {
        LandingSetting::create([
            'key' => LandingSetting::FLYER_TITLE_SEGMENTS_KEY,
            'value' => json_encode([
                ['text' => '<script>alert(1)</script>', 'color' => '#F59E0B'],
            ]),
        ]);

        $response = $this->get(route('landing.public'));

        $response->assertOk();
        $response->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
        $response->assertDontSee('<script>alert(1)</script>', false);
    }
}
