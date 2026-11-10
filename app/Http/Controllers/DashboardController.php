<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Announcement;
use App\Models\Area;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data laporan beserta relasinya, urutkan dari yang terbaru
        $reports = Report::with(['user', 'category', 'area', 'resolver', 'prioritizer', 'likes'])->latest()->get();
        
        // Ambil data pengumuman
        $announcements = Announcement::with(['author', 'area'])->latest()->get();
        
        // Ambil master data untuk form dropdown
        $areas = Area::all();
        $categories = Category::all();

        // Ambil notifikasi yang belum dibaca milik user yang login, lalu tandai sudah dibaca
        $notifications = collect();
        if (Auth::check()) {
            $notifications = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->latest()
                ->get();

            if ($notifications->isNotEmpty()) {
                Notification::whereIn('id', $notifications->pluck('id'))->update(['is_read' => true]);
            }
        }

        return view('dashboard.index', compact('reports', 'announcements', 'areas', 'categories', 'notifications'));
    }
}

