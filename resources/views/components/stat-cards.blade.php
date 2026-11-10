@php
  // Hitung Data Server Side
  $totalLaporan = $reports->count();
  $prosesLaporan = $reports->where('status', 'diproses')->count() + $reports->where('status', 'baru')->count();
  $selesaiLaporan = $reports->where('status', 'selesai')->count();
  
  // REVISI: Mengubah perhitungan menjadi total seluruh akun warga yang terdaftar di database
  $partisipasi = \App\Models\User::where('role', 'warga')->count();
@endphp

<div id="lr-stats" class="stats-container">
  <div class="stat-card fade-anim" onclick="document.getElementById('status-filter-select').value = 'semua'; filterReports();">
    <div class="icon-wrapper"><img src="{{ asset('images/doc.png') }}" width="20" onerror="this.style.display='none'"></div>
    <div class="value">{{ $totalLaporan }}</div>
    <span class="label">Total Laporan</span>
  </div>
  <div class="stat-card fade-anim" onclick="document.getElementById('status-filter-select').value = 'diproses'; filterReports();">
    <div class="icon-wrapper"><img src="{{ asset('images/process.png') }}" width="20" onerror="this.style.display='none'"></div>
    <div class="value">{{ $prosesLaporan }}</div>
    <span class="label">Sedang Diproses</span>
  </div>
  <div class="stat-card fade-anim" onclick="document.getElementById('status-filter-select').value = 'selesai'; filterReports();">
    <div class="icon-wrapper"><img src="{{ asset('images/check.png') }}" width="20" onerror="this.style.display='none'"></div>
    <div class="value">{{ $selesaiLaporan }}</div>
    <span class="label">Terselesaikan</span>
  </div>
  <div class="stat-card fade-anim">
    <div class="icon-wrapper"><img src="{{ asset('images/user.png') }}" width="20" onerror="this.style.display='none'"></div>
    <div class="value">{{ $partisipasi }}</div>
    <span class="label">Partisipasi Warga</span>
  </div>
</div>