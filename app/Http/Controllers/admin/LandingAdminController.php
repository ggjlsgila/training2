<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Flyer;
use App\Models\LandingSetting;
use App\Models\Partner;
use App\Models\Syllabus;
use App\Models\TrainingHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LandingAdminController extends Controller
{
    // Halaman Utama Admin Landing Page Settings
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'banner');

        $banners = Banner::latest()->get();
        $partners = Partner::all();
        $flyers = Flyer::all();
        $syllabi = Syllabus::all();
        $histories = TrainingHistory::all();
        $flyerTitleSegments = LandingSetting::flyerTitleSegments();
        $flyerDescriptionSegments = LandingSetting::flyerDescriptionSegments();

        return view('admin.landing_settings', compact(
            'activeTab',
            'banners',
            'partners',
            'flyers',
            'syllabi',
            'histories',
            'flyerTitleSegments',
            'flyerDescriptionSegments'
        ));
    }

    public function updateFlyerSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'flyer_section_title' => ['required', 'string', 'max:255'],
            'flyer_title_segments_json' => ['nullable', 'string', 'max:20000'],
            'flyer_section_description' => ['required', 'string', 'max:2000'],
            'flyer_description_segments_json' => ['nullable', 'string', 'max:20000'],
        ]);

        $titleSegments = $this->validateTextSegments(
            $validated['flyer_section_title'],
            $validated['flyer_title_segments_json'] ?? null,
            'flyer_title_segments_json',
            'flyer_title_segments'
        );
        $descriptionSegments = $this->validateTextSegments(
            $validated['flyer_section_description'],
            $validated['flyer_description_segments_json'] ?? null,
            'flyer_description_segments_json',
            'flyer_description_segments'
        );

        DB::transaction(function () use ($titleSegments, $descriptionSegments): void {
            LandingSetting::updateOrCreate(
                ['key' => LandingSetting::FLYER_TITLE_SEGMENTS_KEY],
                ['value' => json_encode($titleSegments, JSON_THROW_ON_ERROR)]
            );
            LandingSetting::updateOrCreate(
                ['key' => LandingSetting::FLYER_DESCRIPTION_SEGMENTS_KEY],
                ['value' => json_encode($descriptionSegments, JSON_THROW_ON_ERROR)]
            );
        });

        return redirect()->route('admin.landing.index', ['tab' => 'flyer'])
            ->with('success', 'Teks section flyer berhasil diperbarui!');
    }

    // ==========================================
    // 1. BANNER CRUD (Section 1)
    // ==========================================
    // Store Banner Baru
    public function storeBanner(Request $request)
    {
        $validated = $this->validateBannerData($request, true);

        $imagePath = $validated['image']->store('banners', 'public');

        Banner::create([
            'title' => $validated['title'],
            'title_segments' => $validated['title_segments'],
            'subtitle' => $validated['subtitle'] ?? null,
            'image' => $imagePath,
            'wa_number' => $validated['wa_number'] ?? null,
            'wa_button_text' => $validated['wa_button_text'] ?? 'Pesan training',
        ]);

        return redirect()->route('admin.landing.index', ['tab' => 'banner'])
            ->with('success', 'Banner baru berhasil ditambahkan!');
    }

    // Update Banner
    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $this->validateBannerData($request, false);

        $banner->title = $validated['title'];
        $banner->title_segments = $validated['title_segments'];
        $banner->subtitle = $validated['subtitle'] ?? null;
        $banner->wa_number = $validated['wa_number'] ?? null;
        $banner->wa_button_text = $validated['wa_button_text'] ?? null;

        if (isset($validated['image'])) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $validated['image']->store('banners', 'public');
        }

        $banner->save();

        return redirect()->route('admin.landing.index', ['tab' => 'banner'])
            ->with('success', 'Banner berhasil diperbarui!');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateBannerData(Request $request, bool $imageRequired): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_segments_json' => ['nullable', 'string', 'max:20000'],
            'image' => [
                $imageRequired ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
            'subtitle' => ['nullable', 'string'],
            'wa_number' => ['nullable', 'string'],
            'wa_button_text' => ['nullable', 'string'],
        ]);

        $validated['title_segments'] = $this->validateTextSegments(
            $validated['title'],
            $validated['title_segments_json'] ?? null,
            'title_segments_json',
            'title_segments'
        );

        return $validated;
    }

    /**
     * @return array<int, array{text: string, color: ?string}>
     */
    private function validateTextSegments(
        string $text,
        ?string $segmentsJson,
        string $jsonField,
        string $segmentsField
    ): array {
        if ($segmentsJson === null) {
            return [['text' => $text, 'color' => null]];
        }

        try {
            $segments = json_decode($segmentsJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw ValidationException::withMessages([
                $jsonField => 'Format warna teks tidak valid.',
            ]);
        }

        if (is_array($segments) && array_is_list($segments)) {
            foreach ($segments as &$segment) {
                if (is_array($segment) && ($segment['color'] ?? null) === '') {
                    $segment['color'] = null;
                }
            }
            unset($segment);
        }

        $validatedSegments = Validator::make(
            [$segmentsField => $segments],
            [
                $segmentsField => ['required', 'array', 'list', 'max:255'],
                "{$segmentsField}.*" => ['required', 'array:text,color'],
                "{$segmentsField}.*.text" => ['required', 'string'],
                "{$segmentsField}.*.color" => ['nullable', 'regex:/\A#[0-9a-fA-F]{6}\z/'],
            ]
        )->validate()[$segmentsField];

        $segments = array_map(
            static fn (array $segment): array => [
                'text' => $segment['text'],
                'color' => $segment['color'] ?: null,
            ],
            $validatedSegments
        );

        if (implode('', array_column($segments, 'text')) !== $text) {
            throw ValidationException::withMessages([
                $jsonField => 'Format warna teks tidak sesuai dengan isi teks.',
            ]);
        }

        return $segments;
    }

    public function destroyBanner(int $id): RedirectResponse
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.landing.index', ['tab' => 'banner'])
            ->with('success', 'Banner berhasil dihapus!');
    }

    // ==========================================
    // 2. PARTNER CRUD (Section 2)
    // ==========================================
    public function storePartner(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|max:2048',
            'name' => 'nullable|string',
        ]);

        $logoPath = $request->file('logo')->store('partners', 'public');

        Partner::create([
            'name' => $request->name,
            'logo' => $logoPath,
        ]);

        return redirect()->route('admin.landing.index', ['tab' => 'partner'])
            ->with('success', 'Logo mitra berhasil ditambahkan!');
    }

    public function destroyPartner($id)
    {
        $partner = Partner::findOrFail($id);

        if ($partner->logo && Storage::disk('public')->exists($partner->logo)) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.landing.index', ['tab' => 'partner'])
            ->with('success', 'Logo mitra dihapus!');
    }

    // ==========================================
    // 3. FLYER CRUD (Section 3)
    // ==========================================
    public function storeFlyer(Request $request)
    {
        $request->validate([
            'thumbnail_image' => 'required|image|max:2048',
            'full_image' => 'required|image|max:5120',
        ]);

        Flyer::create([
            'title' => $request->title,
            'thumbnail_image' => $request->file('thumbnail_image')->store('flyers/thumbs', 'public'),
            'full_image' => $request->file('full_image')->store('flyers/full', 'public'),
        ]);

        return redirect()->route('admin.landing.index', ['tab' => 'flyer'])
            ->with('success', 'Flyer berhasil ditambahkan!');
    }

    public function destroyFlyer($id)
    {
        $flyer = Flyer::findOrFail($id);

        if ($flyer->thumbnail_image) {
            Storage::disk('public')->delete($flyer->thumbnail_image);
        }
        if ($flyer->full_image) {
            Storage::disk('public')->delete($flyer->full_image);
        }

        $flyer->delete();

        return redirect()->route('admin.landing.index', ['tab' => 'flyer'])
            ->with('success', 'Flyer berhasil dihapus!');
    }

    // ==========================================
    // 4. SYLLABUS CRUD (Section 4)
    // ==========================================
    public function storeSyllabus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'cover_image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'short_description' => ['required', 'string', 'max:255'],
            'long_description' => ['required', 'string', 'max:10000'],
        ]);

        $coverImagePath = $validated['cover_image']->store('syllabi', 'public');

        Syllabus::create([
            'title' => $validated['title'],
            'cover_image' => $coverImagePath,
            'short_description' => $validated['short_description'],
            'long_description' => $validated['long_description'],
        ]);

        return redirect()->route('admin.landing.index', ['tab' => 'syllabus'])
            ->with('success', 'Silabus berhasil ditambahkan!');
    }

    public function destroySyllabus(int $id): RedirectResponse
    {
        $syllabus = Syllabus::findOrFail($id);

        if ($syllabus->cover_image && Storage::disk('public')->exists($syllabus->cover_image)) {
            Storage::disk('public')->delete($syllabus->cover_image);
        }

        $syllabus->delete();

        return redirect()->route('admin.landing.index', ['tab' => 'syllabus'])
            ->with('success', 'Silabus berhasil dihapus!');
    }

    // ==========================================
    // 5. TRAINING HISTORY CRUD (Section 5)
    // ==========================================
    public function storeHistory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:1000'],
            'documentation_image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'date' => ['nullable', 'date'],
        ]);

        TrainingHistory::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'documentation_image' => $validated['documentation_image']->store('histories', 'public'),
            'date' => $validated['date'] ?? null,
        ]);

        return redirect()->route('admin.landing.index', ['tab' => 'history'])
            ->with('success', 'Riwayat pelatihan berhasil ditambahkan!');
    }

    public function destroyHistory(int $id): RedirectResponse
    {
        $history = TrainingHistory::findOrFail($id);

        if ($history->documentation_image && Storage::disk('public')->exists($history->documentation_image)) {
            Storage::disk('public')->delete($history->documentation_image);
        }

        $history->delete();

        return redirect()->route('admin.landing.index', ['tab' => 'history'])
            ->with('success', 'Riwayat pelatihan berhasil dihapus!');
    }
}
