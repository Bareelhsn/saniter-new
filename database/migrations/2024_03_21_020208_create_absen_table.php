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
        Schema::create('absen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lokasi_id');
            $table->unsignedBigInteger('shift_id');
            $table->date('tgl_masuk');
            $table->time('jam_masuk');
            $table->string('foto_masuk');
            $table->string('lokasi_masuk');
            $table->date('tgl_pulang');
            $table->time('jam_pulang');
            $table->string('foto_pulang');
            $table->string('lokasi_pulang');
            $table->string('terlambat');
            $table->string('keterangan');
            $table->enum('status', ['?','??']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen');
    }
};
