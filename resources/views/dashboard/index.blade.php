@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="header-area">
  <div style="display: flex; justify-content: space-between; align-items: flex-start;">
    <div>
      <div style="display: flex; align-items: center; gap: 14px;">
        <div style="width: 56px; height: 56px; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden;">
          <img src="{{ asset('images/laporwarga.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <span style="font-size: 28px; font-weight: 700;">LaporWarga</span>
      </div>
      <p style="margin: 12px 0 0; font-size: 14px; color: #CFE0D6; max-width: 500px; line-height: 1.6;">Warga Perlu Aksi Nyata. Bukan Kata Kata!</p>
      
      @auth
        <p style="margin: 8px 0 0; font-size: 13px; color: #A4C4B5; font-weight: 600;">
          Halo, {{ Auth::user()->name }} ({{ Auth::user()->role }})
        </p>
      @endauth
    </div>
    <button id="theme-toggle" class="lr-btn" style="background: rgba(255,255,255,0.15); color: #fff; border-radius: 50%; width: 44px; height: 44px; flex-shrink: 0;"></button>
  </div>
</div>

<!-- Komponen Stats & List -->
@include('components.stat-cards')
@include('components.report-list')

<!-- Footer -->
<div style="text-align: center; padding: 24px 24px 100px; border-top: 1px solid var(--line); margin-top: 20px;">
  <p style="font-size: 13px; color: var(--text-muted);">LaporWarga Sistem &copy; 2026.</p>
  <div style="display: flex; justify-content: center; gap: 12px; margin-top: 16px; flex-wrap: wrap;">
      @auth
          {{-- Tombol Profil --}}
          <button onclick="openModal('modal-profile')" class="lr-btn" style="background: var(--warning); color: #fff; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 13px;">
              <img src="{{ asset('images/user.png') }}" style="width: 14px;" onerror="this.style.display='none'"> Profil
          </button>
          
          {{-- Tombol Logout --}}
          <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
              @csrf
              <button type="submit" class="lr-btn" style="background: var(--danger); color: #fff; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 13px;">
                  <img src="{{ asset('images/logout.png') }}" style="width: 14px;" onerror="this.style.display='none'"> Logout
              </button>
          </form>
      @else
          {{-- Tombol Buka Login Admin (Jika belum login) --}}
          <button onclick="openModal('modal-login')" class="lr-btn" style="background: var(--line); color: var(--text-main); padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 13px;">
            Akses Pengurus
          </button>
      @endauth
  </div>
</div>

<!-- Tombol Buat Laporan / Pengumuman -->
<button onclick="{{ (Auth::check() && Auth::user()->isAdmin()) ? "openModal('modal-admin-announce')" : (Auth::check() ? "openCreateReport()" : "openModal('modal-login-warga')") }}" class="lr-btn fade-anim fab-btn">
  <img src="{{ asset('images/buatlaporan.png') }}" style="width: 30px; height: 30px;">
</button>
@endsection