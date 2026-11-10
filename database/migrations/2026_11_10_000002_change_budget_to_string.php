<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    // Anggaran dibuat teks bebas (sebelumnya angka). Data lama tetap aman.
    public function up(): void
    {
        if (Schema::hasColumn('announcements', 'budget')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->string('budget')->nullable()->change();
            });
        }
 
        if (Schema::hasColumn('reports', 'resolution_budget')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->string('resolution_budget')->nullable()->change();
            });
        }
    }
 
    public function down(): void
    {
        // Tidak dikembalikan ke angka agar data teks tidak hilang/terpotong.
    }
};