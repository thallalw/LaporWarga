<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Prioritas penanganan dari sudut pandang RT/RW (diisi saat status "diproses")
    public function up(): void
    {
        if (!Schema::hasColumn('reports', 'handling_priority')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->enum('handling_priority', ['tinggi', 'sedang', 'rendah'])->nullable()->after('priority');
            });
        }

        if (!Schema::hasColumn('reports', 'handling_priority_by')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->unsignedBigInteger('handling_priority_by')->nullable()->after('handling_priority');
                $table->foreign('handling_priority_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reports', 'handling_priority_by')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropForeign(['handling_priority_by']);
                $table->dropColumn('handling_priority_by');
            });
        }
        if (Schema::hasColumn('reports', 'handling_priority')) {
            Schema::table('reports', fn (Blueprint $t) => $t->dropColumn('handling_priority'));
        }
    }
};
