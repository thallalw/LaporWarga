<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate; // <-- Tambahan Facade Gate

class ReportController extends Controller
{
    // Simpan laporan baru dari warga
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'area_id' => 'required|exists:areas,id',
            'specific_address' => 'required|string|max:255',
            'priority' => 'required|in:tinggi,sedang,rendah',
            'description' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload foto ke storage/app/public/reports
        $photoPath = $request->file('photo')->store('reports', 'public');

        $report = Report::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'area_id' => $request->area_id,
            'title' => $request->title,
            'specific_address' => $request->specific_address,
            'priority' => $request->priority,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'status' => 'baru',
        ]);

        // Notifikasi ke seluruh RT/RW: ada laporan baru masuk
        Notification::notify(
            User::whereIn('role', ['rt', 'rw'])->where('id', '!=', Auth::id())->pluck('id'),
            'Laporan Baru Masuk',
            Auth::user()->name . ' melaporkan "' . Str::limit($report->title, 60) . '" di ' . $report->area->name . '.',
            'info'
        );

        return back()->with('success', 'Laporan berhasil dikirim dan masuk ke sistem.');
    }

    // Update laporan milik warga (hanya jika status masih "baru")
    public function update(Request $request, Report $report)
    {
        // REVISI: Menggunakan Gate::authorize
        Gate::authorize('update', $report);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'area_id' => 'required|exists:areas,id',
            'specific_address' => 'required|string|max:255',
            'priority' => 'required|in:tinggi,sedang,rendah',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // Cek apakah ada foto baru yang diupload
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($report->photo_path && Storage::disk('public')->exists($report->photo_path)) {
                Storage::disk('public')->delete($report->photo_path);
            }
            $report->photo_path = $request->file('photo')->store('reports', 'public');
        }

        $report->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'area_id' => $request->area_id,
            'specific_address' => $request->specific_address,
            'priority' => $request->priority,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Laporan Anda berhasil diperbarui.');
    }

    // Hapus laporan
    public function destroy(Report $report)
    {
        // REVISI: Menggunakan Gate::authorize
        Gate::authorize('delete', $report);

        if ($report->photo_path && Storage::disk('public')->exists($report->photo_path)) {
            Storage::disk('public')->delete($report->photo_path);
        }

        $report->delete();
        return back()->with('success', 'Laporan berhasil dihapus.');
    }

    // Admin mengubah status laporan dan memberikan bukti penyelesaian
    public function updateStatus(Request $request, Report $report)
    {
        // Menggunakan Gate::authorize dan passing instance $report
        Gate::authorize('updateStatus', $report);

        $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
            'handling_priority' => 'required_if:status,diproses|nullable|in:tinggi,sedang,rendah',
            'res_note' => 'required_if:status,selesai|nullable|string',
            'res_budget' => 'required_if:status,selesai|nullable|string|max:255',
            'res_photo' => 'required_if:status,selesai|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $sudahSelesai = $report->status === 'selesai';

        $report->status = $request->status;

        // Prioritas penanganan dari RT/RW: ditetapkan saat "diproses",
        // tetap tersimpan saat "selesai", dihapus jika dikembalikan ke "baru"
        if ($request->status === 'diproses') {
            $report->handling_priority = $request->handling_priority;
            $report->handling_priority_by = Auth::id();
        } elseif ($request->status === 'baru') {
            $report->handling_priority = null;
            $report->handling_priority_by = null;
        }

        if ($request->status === 'selesai') {
            $report->resolved_by = Auth::id();
            $report->resolved_at = now();
            $report->resolution_note = $request->res_note;
            $report->resolution_budget = $request->res_budget;
            
            if ($request->hasFile('res_photo')) {
                $report->resolution_photo_path = $request->file('res_photo')->store('resolutions', 'public');
            }
        } else {
            // Jika dikembalikan ke status diproses/baru, bersihkan data penyelesaian
            $report->resolved_by = null;
            $report->resolved_at = null;
            $report->resolution_note = null;
            $report->resolution_budget = null;
            $report->resolution_photo_path = null;
        }

        $report->save();

        // Pop UP ke warga pelapor kalo laporannya udah selesai ditangani
        if ($request->status === 'selesai' && !$sudahSelesai && $report->user_id != Auth::id()) {
            Notification::notify(
                [$report->user_id],
                'Laporan Anda Selesai',
                'Laporan "' . Str::limit($report->title, 60) . '" telah diselesaikan oleh ' . Auth::user()->username . ' (' . Auth::user()->roleLabel() . '). Catatan: ' . Str::limit($report->resolution_note, 100),
                'success'
            );
        }

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    // Fitur menyukai laporan (Like)
    public function like(Report $report)
    {
        $user = Auth::user();

        // 1 user 1 like per laporan
        $report->likes()->toggle($user->id);

        return back()->with('success', 'Tanggapan berhasil disimpan.');
    }

    // Fitur Arsip Laporan
    public function toggleArchive(Report $report)
    {
        if ($report->archived_at) {
            $report->archived_at = null;
            $msg = 'Laporan dikeluarkan dari arsip.';
        } else {
            $report->archived_at = now();
            $msg = 'Laporan berhasil diarsipkan.';
        }
        
        $report->save();
        return back()->with('success', $msg);
    }
}

