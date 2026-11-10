<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        // Hanya admin yang bisa membuat pengumuman
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'area_id' => 'required|exists:areas,id',
            'description' => 'required|string',
            'budget' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('announcements', 'public');
        }

        $announcement = Announcement::create([
            'user_id' => Auth::id(),
            'area_id' => $request->area_id,
            'title' => $request->title,
            'description' => $request->description,
            'budget' => $request->budget,
            'photo_path' => $photoPath,
        ]);

        // Notify semua warga tentang pengumuman baru
        Notification::notify(
            User::where('role', 'warga')->pluck('id'),
            'Fasilitas Baru',
            '"' . Str::limit($announcement->title, 60) . '" kini tersedia di ' . $announcement->area->name . '.',
            'info'
        );

        return back()->with('success', 'Fasilitas baru berhasil diumumkan ke warga.');
    }

    // Edit pengumuman fasilitas
    public function update(Request $request, Announcement $announcement)
    {
        // Cuman (RT/RW) yang bisa mengedit pengumuman
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'area_id' => 'required|exists:areas,id',
            'description' => 'required|string',
            'budget' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // foto opsional saat edit
        ]);

        // Ganti foto
        if ($request->hasFile('photo')) {
            if ($announcement->photo_path && Storage::disk('public')->exists($announcement->photo_path)) {
                Storage::disk('public')->delete($announcement->photo_path);
            }
            $announcement->photo_path = $request->file('photo')->store('announcements', 'public');
        }

        $announcement->title = $request->title;
        $announcement->area_id = $request->area_id;
        $announcement->description = $request->description;
        $announcement->budget = $request->budget;
        $announcement->save();

        return back()->with('success', 'Pengumuman fasilitas berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        if ($announcement->photo_path && Storage::disk('public')->exists($announcement->photo_path)) {
            Storage::disk('public')->delete($announcement->photo_path);
        }

        $announcement->delete();
        return back()->with('success', 'Pengumuman fasilitas berhasil dihapus.');
    }
}