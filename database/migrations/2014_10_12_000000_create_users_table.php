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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('regional_id');
            $table->unsignedBigInteger('lokasi_id');
            $table->unsignedBigInteger('role_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nik');
            $table->text('alamat_ktp');
            $table->text('alamat_dom');
            $table->string('telp');
            $table->string('foto')->default('user-images/default.jpg');
            $table->string('password');
            $table->string('ttd')->default('user-ttd/default.jpg');
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['?','??']);
            $table->timestamps();
            $table->rememberToken();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
