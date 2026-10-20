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
    Schema::create('training_histories', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');             // Artikel/cerita proses training
        $table->string('documentation_image'); // Foto dokumentasi
        $table->date('date')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_histories');
    }
};
