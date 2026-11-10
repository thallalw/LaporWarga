<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Pelapor
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('area_id'); // Lokasi Kejadian
            
            $table->string('title');
            $table->string('specific_address');
            $table->text('description');
            $table->string('photo_path')->nullable(); // Foto bukti
            $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
            
            // Kolom Penyelesaian (Diisi oleh Admin)
            $table->unsignedBigInteger('resolved_by')->nullable(); // Admin yang menyelesaikan
            $table->text('resolution_note')->nullable();
            $table->string('resolution_photo_path')->nullable();
            
            // Timestamps untuk pencatatan waktu
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('archived_at')->nullable(); // Sistem arsip menggunakan timestamp
            $table->timestamps(); // created_at, updated_at

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('restrict');
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
