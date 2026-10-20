<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingSetting;

class LandingSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaultSettings = [
            'hero_title' => 'Tingkatkan Keterampilan Manajemen Sumber Daya Manusia di Perusahaan Anda',
            'hero_subtitle' => 'HR Manufaktur berkomitmen menjadi mitra yang andal dan efisien dalam mengoptimalkan sumber daya manusia perusahaan Anda.',
            'wa_number' => '6285123456789',
            'wa_button_text' => 'Pesan training',
            'partner_count_text' => '50+ Perusahaan Mitra Telah Merekrut Alumni Kami',
            'feature_1' => 'Training di manapun dan kapanpun, bisa di dalam pabrik dan di tempat khusus training',
            'feature_2' => 'Materi menyesuaikan dengan kebutuhan manajemen',
            'feature_3' => 'Tersedia 100+ silabus yang dapat Anda pilih dalam training',
        ];

        foreach ($defaultSettings as $key => $value) {
            LandingSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}