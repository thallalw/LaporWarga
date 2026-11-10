<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $rules = [
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:4', // Nullable agar tidak wajib diisi jika hanya ingin ganti nama
        ];

        // Pengurus (RT/RW) boleh mengganti username login, harus unik
        if ($user->isAdmin()) {
            $rules['username'] = 'required|string|min:3|max:50|unique:users,username,' . $user->id;
        }

        $request->validate($rules);

        // Update nama
        $user->name = $request->name;

        if ($user->isAdmin()) {
            $user->username = trim($request->username);
        }

        // Jika form password diisi, enkripsi dan simpan yang baru
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil dan kata sandi berhasil diperbarui!');
    }
}