<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Username untuk login RT/RW (warga tetap login dengan nama + blok)
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });
        }

        $hasUnique = collect(Schema::getIndexes('users'))
            ->contains(fn ($i) => $i['columns'] === ['username'] && $i['unique']);
        if (!$hasUnique) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username');
            });
        }

        // Isi username default untuk akun pengurus yang sudah ada
        DB::table('users')->where('role', 'rt')->whereNull('username')->update(['username' => 'ketua_rt']);
        DB::table('users')->where('role', 'rw')->whereNull('username')->update(['username' => 'ketua_rw']);

        // Prioritas laporan + anggaran penyelesaian (teks bebas)
        if (!Schema::hasColumn('reports', 'priority')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->enum('priority', ['tinggi', 'sedang', 'rendah'])->default('sedang')->after('status');
            });
        }
        if (!Schema::hasColumn('reports', 'resolution_budget')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->string('resolution_budget')->nullable()->after('resolution_note');
            });
        } else {
            Schema::table('reports', fn (Blueprint $table) => $table->string('resolution_budget')->nullable()->change());
        }

        // Anggaran fasilitas baru (teks bebas)
        if (!Schema::hasColumn('announcements', 'budget')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->string('budget')->nullable()->after('description');
            });
        } else {
            Schema::table('announcements', fn (Blueprint $table) => $table->string('budget')->nullable()->change());
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('announcements', 'budget')) {
            Schema::table('announcements', fn (Blueprint $t) => $t->dropColumn('budget'));
        }
        foreach (['priority', 'resolution_budget'] as $col) {
            if (Schema::hasColumn('reports', $col)) {
                Schema::table('reports', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $t) {
                $t->dropUnique(['username']);
                $t->dropColumn('username');
            });
        }
    }
};
