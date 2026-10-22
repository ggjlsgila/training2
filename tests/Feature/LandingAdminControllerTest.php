<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Syllabus;
use App\Models\TrainingHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LandingAdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_syllabus_with_its_cover_and_descriptions(): void
    {
        Storage::fake('public');

        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('admin.landing.store_syllabus'), [
                'title' => 'Silabus Hubungan Industrial',
                'cover_image' => UploadedFile::fake()->image('cover.jpg'),
                'short_description' => 'Panduan hubungan industrial.',
                'long_description' => 'Ringkasan lengkap silabus hubungan industrial.',
            ]);

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'syllabus']));
        $response->assertSessionHas('success', 'Silabus berhasil ditambahkan!');

        $syllabus = Syllabus::firstOrFail();
        $this->assertSame('Silabus Hubungan Industrial', $syllabus->title);
        $this->assertSame('Panduan hubungan industrial.', $syllabus->short_description);
        $this->assertSame('Ringkasan lengkap silabus hubungan industrial.', $syllabus->long_description);
        Storage::disk('public')->assertExists($syllabus->cover_image);
    }

    public function test_syllabus_creation_rejects_invalid_cover_images_without_saving(): void
    {
        Storage::fake('public');

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('admin.landing.index', ['tab' => 'syllabus']))
            ->post(route('admin.landing.store_syllabus'), [
                'title' => 'Silabus Hubungan Industrial',
                'cover_image' => UploadedFile::fake()->create('cover.pdf', 50, 'application/pdf'),
                'short_description' => 'Panduan hubungan industrial.',
                'long_description' => 'Ringkasan lengkap silabus hubungan industrial.',
            ]);

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'syllabus']));
        $response->assertSessionHasErrors('cover_image');
        $this->assertDatabaseCount('syllabi', 0);
        $this->assertSame([], Storage::disk('public')->allFiles('syllabi'));
    }

    public function test_authenticated_user_can_delete_a_syllabus_and_its_cover_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('syllabi/cover.jpg', 'cover image');
        $syllabus = Syllabus::create([
            'title' => 'Silabus untuk dihapus',
            'cover_image' => 'syllabi/cover.jpg',
            'short_description' => 'Deskripsi pendek.',
            'long_description' => 'Deskripsi lengkap.',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('admin.landing.destroy_syllabus', $syllabus->id));

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'syllabus']));
        $this->assertDatabaseMissing('syllabi', ['id' => $syllabus->id]);
        Storage::disk('public')->assertMissing('syllabi/cover.jpg');
    }

    public function test_authenticated_user_can_delete_a_banner_and_its_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('banners/banner.jpg', 'banner image');
        $banner = Banner::create([
            'title' => 'Banner untuk dihapus',
            'image' => 'banners/banner.jpg',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('admin.landing.destroy_banner', $banner->id));

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'banner']));
        $this->assertDatabaseMissing('banners', ['id' => $banner->id]);
        Storage::disk('public')->assertMissing('banners/banner.jpg');
    }

    public function test_authenticated_user_can_create_a_training_history_with_its_cover_and_short_description(): void
    {
        Storage::fake('public');

        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('admin.landing.store_history'), [
                'title' => 'Pelatihan HR Manufaktur',
                'content' => 'Pelatihan bersama tim HR di Cikarang.',
                'documentation_image' => UploadedFile::fake()->image('training.jpg'),
                'date' => '2026-10-20',
            ]);

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'history']));
        $response->assertSessionHas('success', 'Riwayat pelatihan berhasil ditambahkan!');

        $history = TrainingHistory::firstOrFail();
        $this->assertSame('Pelatihan HR Manufaktur', $history->title);
        $this->assertSame('Pelatihan bersama tim HR di Cikarang.', $history->content);
        $this->assertSame('2026-10-20', $history->date);
        Storage::disk('public')->assertExists($history->documentation_image);
    }

    public function test_training_history_creation_rejects_non_image_files_without_saving(): void
    {
        Storage::fake('public');

        $response = $this
            ->actingAs(User::factory()->create())
            ->from(route('admin.landing.index', ['tab' => 'history']))
            ->post(route('admin.landing.store_history'), [
                'title' => 'Pelatihan HR Manufaktur',
                'content' => 'Pelatihan bersama tim HR di Cikarang.',
                'documentation_image' => UploadedFile::fake()->create('training.pdf', 50, 'application/pdf'),
            ]);

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'history']));
        $response->assertSessionHasErrors([
            'documentation_image' => 'The documentation image field must be an image.',
        ]);
        $this->assertDatabaseCount('training_histories', 0);
        $this->assertSame([], Storage::disk('public')->allFiles('histories'));
    }

    public function test_authenticated_user_can_delete_a_training_history_and_its_cover_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('histories/training.jpg', 'training image');
        $history = TrainingHistory::create([
            'title' => 'Pelatihan untuk dihapus',
            'content' => 'Keterangan singkat.',
            'documentation_image' => 'histories/training.jpg',
            'date' => '2026-10-20',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('admin.landing.destroy_history', $history->id));

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'history']));
        $this->assertDatabaseMissing('training_histories', ['id' => $history->id]);
        Storage::disk('public')->assertMissing('histories/training.jpg');
    }

    public function test_public_landing_page_renders_training_histories_in_pages_of_four(): void
    {
        foreach (range(1, 5) as $index) {
            $history = TrainingHistory::create([
                'title' => "Riwayat training {$index}",
                'content' => "Keterangan training {$index}.",
                'documentation_image' => "histories/training-{$index}.jpg",
            ]);
            $history->forceFill(['created_at' => "2026-10-20 00:00:0{$index}"])->save();
        }

        $response = $this->get(route('landing.public'));

        $response->assertOk();
        $response->assertSee('Riwayat training 5');
        $response->assertSee('Riwayat training 4');
        $response->assertSee('Riwayat training 3');
        $response->assertSee('Riwayat training 2');
        $response->assertSee('Riwayat training 1');
        $response->assertSee('historySwiper', false);
        $response->assertSee('history-button-prev', false);
        $response->assertSee('history-button-next', false);
    }

    public function test_guest_is_redirected_to_login_when_deleting_a_banner(): void
    {
        $banner = Banner::create([
            'title' => 'Banner tetap ada',
            'image' => 'banners/banner.jpg',
        ]);

        $response = $this->delete(route('admin.landing.destroy_banner', $banner->id));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('banners', ['id' => $banner->id]);
    }

    public function test_authenticated_user_can_create_a_banner_with_colored_title_segments(): void
    {
        Storage::fake('public');

        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('admin.landing.store_banner'), [
                'title' => 'HR Training 2026',
                'title_segments_json' => json_encode([
                    ['text' => 'HR ', 'color' => ''],
                    ['text' => 'Training', 'color' => '#12ABEF'],
                    ['text' => ' 2026', 'color' => ''],
                ]),
                'image' => UploadedFile::fake()->image('banner.jpg'),
            ]);

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'banner']));

        $banner = Banner::firstOrFail();
        $this->assertSame([
            ['text' => 'HR ', 'color' => null],
            ['text' => 'Training', 'color' => '#12ABEF'],
            ['text' => ' 2026', 'color' => null],
        ], $banner->title_segments);
        Storage::disk('public')->assertExists($banner->image);
    }

    public function test_banner_update_rejects_unsafe_title_colors(): void
    {
        $banner = Banner::create([
            'title' => 'Judul lama',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->put(route('admin.landing.update_banner', $banner->id), [
                'title' => 'Judul baru',
                'title_segments_json' => json_encode([
                    ['text' => 'Judul baru', 'color' => 'red; background:url(javascript:alert(1))'],
                ]),
            ]);

        $response->assertSessionHasErrors('title_segments.0.color');
        $this->assertSame('Judul lama', $banner->refresh()->title);
    }

    public function test_authenticated_user_can_update_colored_title_segments(): void
    {
        $banner = Banner::create([
            'title' => 'Judul lama',
        ]);

        $response = $this
            ->actingAs(User::factory()->create())
            ->put(route('admin.landing.update_banner', $banner->id), [
                'title' => 'Judul baru',
                'title_segments_json' => json_encode([
                    ['text' => 'Judul ', 'color' => ''],
                    ['text' => 'baru', 'color' => '#22AA88'],
                ]),
            ]);

        $response->assertRedirect(route('admin.landing.index', ['tab' => 'banner']));
        $this->assertSame([
            ['text' => 'Judul ', 'color' => null],
            ['text' => 'baru', 'color' => '#22AA88'],
        ], $banner->refresh()->title_segments);
    }

    public function test_public_landing_escapes_colored_title_text(): void
    {
        Banner::create([
            'title' => '<script>alert(1)</script>',
            'title_segments' => [
                ['text' => '<script>alert(1)</script>', 'color' => '#22AA88'],
            ],
        ]);

        $response = $this->get(route('landing.public'));

        $response->assertOk();
        $response->assertSee('<script>alert(1)</script>');
        $response->assertDontSee('<script>alert(1)</script>', false);
        $response->assertSee('color: #22AA88', false);
    }
}
