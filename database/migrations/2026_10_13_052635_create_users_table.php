<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('role', ['warga', 'rt', 'rw'])->default('warga');
            $table->unsignedBigInteger('area_id')->nullable(); // Foreign Key ke areas (Bisa null untuk RT/RW)
            $table->string('password'); // Akan menggunakan Hash::make()
            $table->rememberToken(); // Diperlukan untuk fitur 'remember me' session Laravel
            $table->timestamps();

            // Mencegah akun ganda (nama dan area_id tidak boleh sama persis)
            $table->unique(['name', 'area_id'], 'unique_user_area');

            // Definisi Foreign Key
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};