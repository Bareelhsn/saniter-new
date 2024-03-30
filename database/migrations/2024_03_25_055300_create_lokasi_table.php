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
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            $table->integer('regional_id');
            $table->string('nama_bandara', length: 150);
            $table->string('lokasi_proyek', length: 150);
            $table->string('latitude', length: 50);
            $table->string('longitude', length: 50);
            $table->integer('radius');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
