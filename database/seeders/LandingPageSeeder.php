<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use App\Models\Partner;
use App\Models\Flyer;
use App\Models\Syllabus;
use App\Models\TrainingHistory;

class LandingPageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Banner Utama
        Banner::create([
            'title' => 'Tingkatkan Keterampilan Manajemen Sumber Daya Manusia di Perusahaan Anda',
            'subtitle' => 'HR Manufaktur berkomitmen menjadi mitra yang andal dan efisien dalam mengoptimalkan sumber daya manusia perusahaan Anda.',
            'image' => 'banners/default.jpg',
            'wa_number' => '6285123456789',
            'wa_button_text' => 'Pesan training',
        ]);

        // 2. Logo Mitra
        Partner::create(['name' => 'Haier', 'logo' => 'partners/haier.png']);
        Partner::create(['name' => 'Advanex', 'logo' => 'partners/advanex.png']);
        Partner::create(['name' => 'Exedy', 'logo' => 'partners/exedy.png']);

        // 3. Flyer Training
        Flyer::create([
            'title' => 'Program Sertifikasi HR Manufaktur Profesional',
            'thumbnail_image' => 'flyers/thumbs/default1.jpg',
            'full_image' => 'flyers/full/default1.jpg',
        ]);

        // 4. Buku Silabus
        Syllabus::create([
            'title' => 'Industrial Relations & Labor Law',
            'cover_image' => 'syllabi/default.jpg',
            'short_description' => 'Memahami hukum ketenagakerjaan dan hubungan industrial di pabrik.',
            'long_description' => 'Silabus ini membahas secara mendalam mengenai undang-undang ketenagakerjaan terbaru, penanganan perselisihan industrial, penyusunan Peraturan Perusahaan (PP), serta Perjanjian Kerja Bersama (PKB).',
        ]);

        // 5. Histori Training
        TrainingHistory::create([
            'title' => 'Pelatihan Batch 1 - HR Management di Cikarang',
            'content' => 'Pelatihan sukses diselenggarakan dengan melibatkan 25 manajer HR dari berbagai perusahaan manufaktur terkemuka.',
            'documentation_image' => 'histories/default.jpg',
            'date' => now(),
        ]);
    }
}