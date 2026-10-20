<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('flyers', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable();
        $table->string('thumbnail_image'); // Gambar flyer berjalan (slide/preview)
        $table->string('full_image');      // Gambar flyer Full Resolution
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flyers');
    }
};
