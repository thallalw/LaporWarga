<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    // Menyeragamkan kolom reports.priority menjadi enum('tinggi','sedang','rendah').
    // Berguna jika kolom ini sudah ada sebelumnya dengan pilihan nilai yang berbeda.
    public function up(): void
    {
        if (!Schema::hasColumn('reports', 'priority')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->enum('priority', ['tinggi', 'sedang', 'rendah'])->default('sedang')->after('status');
            });
            return;
        }
 
        // 1) Lepas batasan enum lama supaya semua nilai lama bisa disimpan sementara
        Schema::table('reports', function (Blueprint $table) {
            $table->string('priority', 50)->nullable()->change();
        });
 
        // 2) Petakan nilai lama ke tinggi/sedang/rendah (yang tidak dikenal jadi 'sedang')
        $map = [
            'tinggi' => ['tinggi', 'high', 'urgent', 'darurat'],
            'rendah' => ['rendah', 'low'],
        ];
        $rows = DB::table('reports')->select('id', 'priority')->get();
        foreach ($rows as $row) {
            $v = strtolower(trim((string) $row->priority));
            $new = 'sedang';
            foreach ($map as $target => $aliases) {
                if (in_array($v, $aliases, true)) {
                    $new = $target;
                }
            }
            if ($row->priority !== $new) {
                DB::table('reports')->where('id', $row->id)->update(['priority' => $new]);
            }
        }
 
        // 3) Pasang kembali sebagai enum yang benar
        Schema::table('reports', function (Blueprint $table) {
            $table->enum('priority', ['tinggi', 'sedang', 'rendah'])->default('sedang')->nullable(false)->change();
        });
    }
 
    public function down(): void
    {
        // Dibiarkan: kolom priority tetap ada.
    }
};
 