<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Setting Teks & Kontak Umum
        Schema::create('landing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // contoh: 'hero_title', 'wa_number', 'cta_button_text'
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Program Training / Brosur
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('TRAINING');
            $table->string('image'); // path file gambar brosur
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Tabel Silabus / Buku Acuan
       Schema::create('syllabi', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('cover_image');
    $table->string('short_description'); // Tambahkan baris ini
    $table->text('long_description');    // Tambahkan baris ini
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('syllabi');
        Schema::dropIfExists('training_programs');
        Schema::dropIfExists('landing_settings');
    }
};