<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Area;

class AuthController extends Controller
{
    // Proses Registrasi Warga
    public function registerWarga(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'block' => 'required|string|max:255',
            'password' => 'required|string|min:4',
        ]);

        // Normalisasi spasi ekstra pada nama (mencegah duplikat karena salah spasi)
        $normalizedName = trim(preg_replace('/\s+/', ' ', $request->name));

        // Cari Area/Blok berdasarkan input
        $area = Area::where('name', $request->block)->first();
        if (!$area) {
            return back()->with('error', 'Blok tidak ditemukan atau tidak valid.');
        }

        // Cek apakah nama dan area sudah ada
        $exists = User::where('name', $normalizedName)->where('area_id', $area->id)->first();
        if ($exists) {
            return back()->with('error', 'Akun dengan nama dan blok tersebut sudah terdaftar. Silakan masuk.');
        }

        // Buat Akun Warga
        $user = User::create([
            'name' => $normalizedName,
            'role' => 'warga',
            'area_id' => $area->id,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil, selamat datang!');
    }

    // Proses Login (Warga & Admin)
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required_without:username|string',
            'block' => 'required_without:username|string',
            'username' => 'required_without:name|string',
            'role' => 'required_with:username|in:rt,rw',
            'password' => 'required|string',
        ]);

        // Logika Login Warga
        if (!$request->filled('username')) {
            $normalizedName = trim(preg_replace('/\s+/', ' ', $request->name));
            $area = Area::where('name', $request->block)->first();

            if (!$area) {
                return back()->with('error', 'Blok tidak ditemukan.');
            }

            if (Auth::attempt(['name' => $normalizedName, 'password' => $request->password, 'area_id' => $area->id])) {
                $request->session()->regenerate();
                return redirect()->route('dashboard')->with('success', 'Berhasil masuk sebagai warga.');
            }
        } 
        // Logika Login Admin (RT / RW)
        else {
            // Username harus cocok dengan peran yang dipilih
            $admin = User::where('username', trim($request->username))
                ->where('role', $request->role)
                ->first();

            if ($admin && Hash::check($request->password, $admin->password)) {
                Auth::login($admin);
                $request->session()->regenerate();
                return redirect()->route('dashboard')->with('success', 'Berhasil masuk sebagai ' . $admin->roleLabel() . '.');
            }
        }

        return back()->with('error', 'Data tidak valid atau kata sandi salah.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard')->with('success', 'Anda telah keluar dari sistem.');
    }
}

