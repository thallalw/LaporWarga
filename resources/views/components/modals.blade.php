{{-- Viewer Gambar --}}
<div id="modal-image-viewer" class="modal-overlay image-viewer-overlay" onclick="closeModal('modal-image-viewer')">
    <button class="lr-btn image-viewer-close"><img src="{{ asset('images/close.png') }}" width="20"></button>
    <img id="viewer-img" src="" class="image-viewer-img">
</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div id="modal-confirm" class="modal-overlay confirm-modal" style="align-items: center; z-index: 1000;">
  <div class="modal-content fade-anim" style="max-width: 320px; text-align: center; padding: 32px 24px; margin: auto;">
    <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 50%; background: rgba(193, 67, 45, 0.1); color: var(--danger); margin-bottom: 20px;">
      <img src="{{ asset('images/error.png') }}" style="width: 32px;" onerror="this.style.display='none'">
    </div>
    <h3 style="font-size: 20px; margin: 0 0 8px; font-weight: 700;">Hapus Data?</h3>
    <p style="font-size: 14px; color: var(--text-muted); margin: 0 0 24px; line-height: 1.5;">Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin ingin menghapusnya?</p>
    <div style="display: flex; gap: 12px;">
      <button onclick="closeModal('modal-confirm')" class="lr-btn" style="flex: 1; background: var(--line); color: var(--text-main); padding: 12px; border-radius: 10px; font-weight: 600;">Batal</button>
      <button id="btn-confirm-delete" class="lr-btn" style="flex: 1; background: var(--danger); color: #fff; padding: 12px; border-radius: 10px; font-weight: 600;">Ya, Hapus</button>
    </div>
  </div>
</div>

{{-- MODAL LOGIN / REGISTER WARGA --}}
<div id="modal-login-warga" class="modal-overlay">
  <div class="modal-content fade-anim" style="max-width: 420px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
      <div style="display: flex; align-items: center; gap: 12px;">
         <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(29, 92, 74, 0.1); border-radius: 50%; color: var(--primary);">
            <img src="{{ asset('images/user.png') }}" style="width: 24px;" onerror="this.style.display='none'">
         </div>
         <div>
           <h3 style="font-size: 18px; margin: 0 0 4px; font-weight: 700;">Portal Warga</h3>
           <p style="font-size: 13px; color: var(--text-muted); margin: 0;" id="auth-subtitle">Masuk untuk melanjutkan.</p>
         </div>
      </div>
      <button type="button" onclick="closeModal('modal-login-warga')" class="lr-btn modal-close-btn">
        <img src="{{ asset('images/close.png') }}" style="width: 18px;">
      </button>
    </div>

    <!-- Login Form -->
    <form id="warga-login-form" method="POST" action="{{ route('login') }}">
        @csrf
        <label class="input-label">Nama Lengkap</label>
        <input type="text" name="name" class="input-field" required>
        
        <label class="input-label" style="margin-top:10px;">Blok Asal</label>
        <select name="block" class="input-field" required>
            @foreach(\App\Models\Area::all() as $area)
                <option value="{{ $area->name }}">{{ $area->name }}</option>
            @endforeach
        </select>

        <label class="input-label" style="margin-top:10px;">Kata Sandi</label>
        <div class="pwd-wrapper" style="margin-bottom: 24px;">
            <input type="password" id="login-warga-pwd" name="password" class="input-field" required style="padding-right: 48px; margin-top:0;">
            <button type="button" onclick="togglePwd('login-warga-pwd', this)" class="lr-btn-icon"><img src="{{ asset('images/show.png') }}" style="width: 20px;"></button>
        </div>
        
        <button type="submit" class="lr-btn" style="width: 100%; background: var(--primary); color: #fff; padding: 14px; border-radius: 10px;">Masuk</button>
        <p style="text-align: center; margin-top: 16px; font-size: 13px;">Belum punya akun? <span class="link-text" onclick="toggleAuthMode('register')">Daftar di sini</span></p>
    </form>

    <!-- Register Form -->
    <form id="warga-register-form" style="display: none;" method="POST" action="{{ route('register') }}">
        @csrf
        <label class="input-label">Nama Lengkap</label>
        <input type="text" name="name" class="input-field" required>
        
        <label class="input-label" style="margin-top:10px;">Blok Asal</label>
        <select name="block" class="input-field" required>
            @foreach(\App\Models\Area::all() as $area)
                <option value="{{ $area->name }}">{{ $area->name }}</option>
            @endforeach
        </select>
        
        <label class="input-label" style="margin-top:10px;">Kata Sandi Baru</label>
        <div class="pwd-wrapper" style="margin-bottom: 24px;">
            <input type="password" id="reg-warga-pwd" name="password" class="input-field" required style="padding-right: 48px; margin-top:0;">
            <button type="button" onclick="togglePwd('reg-warga-pwd', this)" class="lr-btn-icon"><img src="{{ asset('images/show.png') }}" style="width: 20px;"></button>
        </div>
        
        <button type="submit" class="lr-btn" style="width: 100%; background: var(--primary); color: #fff; padding: 14px; border-radius: 10px;">Buat Akun</button>
        <p style="text-align: center; margin-top: 16px; font-size: 13px;">Sudah punya akun? <span class="link-text" onclick="toggleAuthMode('login')">Masuk di sini</span></p>
    </form>
  </div>
</div>

{{-- MODAL LOGIN ADMIN RT/RW --}}
<div id="modal-login" class="modal-overlay">
  <div class="modal-content fade-anim" style="max-width: 400px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
      <div style="display: flex; align-items: center; gap: 12px;">
         <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(29, 92, 74, 0.1); border-radius: 50%; color: var(--primary);">
            <img src="{{ asset('images/AvatarAdmin.png') }}" style="width: 32px;" onerror="this.style.display='none'">
         </div>
         <div>
           <h3 style="font-size: 18px; margin: 0 0 4px; font-weight: 700;">Portal Pengurus</h3>
           <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Pilih peran, lalu masukkan username Anda.</p>
         </div>
      </div>
      <button type="button" onclick="closeModal('modal-login')" class="lr-btn modal-close-btn">
        <img src="{{ asset('images/close.png') }}" style="width: 18px;">
      </button>
    </div>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <label class="input-label">Peran Akses</label>
      <select name="role" class="input-field" style="margin-bottom: 16px;" required>
          <option value="rt">Ketua RT</option>
          <option value="rw">Ketua RW</option>
      </select>
      <label class="input-label">Username</label>
      <input type="text" name="username" class="input-field" placeholder="Masukkan username" autocomplete="username" style="margin-bottom: 16px;" required>
      <label class="input-label">Kata Sandi</label>
      <div class="pwd-wrapper" style="margin-bottom: 24px;">
          <input type="password" id="login-admin-pwd" name="password" class="input-field" required style="padding-right: 48px; margin-top:0;">
          <button type="button" onclick="togglePwd('login-admin-pwd', this)" class="lr-btn-icon"><img src="{{ asset('images/show.png') }}" style="width: 20px;"></button>
      </div>
      <button type="submit" class="lr-btn" style="width:100%; background: var(--primary); color: #fff; padding: 14px; border-radius: 10px;">Masuk</button>
    </form>
  </div>
</div>

@auth
{{-- MODAL FORM LAPORAN WARGA --}}
@if(Auth::user()->isWarga())
<div id="modal-warga" class="modal-overlay">
  <div class="modal-content fade-anim">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
      <div style="display: flex; align-items: center; gap: 12px;">
         <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(232, 185, 61, 0.15); border-radius: 50%;">
            <img src="{{ asset('images/buatlaporan.png') }}" style="width: 24px;" onerror="this.style.display='none'">
         </div>
         <h3 id="warga-form-title" style="font-weight: 700; font-size: 20px; margin:0;">Form Laporan Baru</h3>
      </div>
      <button type="button" onclick="closeModal('modal-warga')" class="lr-btn modal-close-btn">
        <img src="{{ asset('images/close.png') }}" style="width: 18px;">
      </button>
    </div>
    
    <form id="form-warga-action" method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
      @csrf
      <label>Judul Masalah</label>
      <input type="text" id="warga-title-input" name="title" class="input-field" required>
      
      <div style="display:flex; gap:16px; margin-top:10px;">
        <div style="flex:1;">
          <label>Kategori</label>
          <select id="warga-cat-input" name="category_id" class="input-field" required>
              @foreach(\App\Models\Category::all() as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
              @endforeach
          </select>
        </div>
        <div style="flex:1;">
          <label>Lokasi Kejadian</label>
          <select id="warga-area-input" name="area_id" class="input-field" required>
              @foreach(\App\Models\Area::all() as $a)
                  <option value="{{ $a->id }}">{{ $a->name }}</option>
              @endforeach
          </select>
        </div>
      </div>

      <label style="margin-top:10px; display:block;">Prioritas</label>
      <select id="warga-priority-input" name="priority" class="input-field" required>
          <option value="tinggi">Tinggi</option>
          <option value="sedang" selected>Sedang</option>
          <option value="rendah">Rendah</option>
      </select>

      <label style="margin-top:10px; display:block;">Alamat Spesifik Titik Lokasi</label>
      <input type="text" id="warga-addr-input" name="specific_address" class="input-field" required>
      
      <label style="margin-top:10px; display:block;">Deskripsi Detail</label>
      <textarea id="warga-desc-input" name="description" class="input-field" rows="3" required></textarea>
      
      <div class="file-upload-wrapper">
        <input type="file" id="warga-photo-input" name="photo" accept="image/*" class="file-upload-input" required>
        <div class="file-upload-custom"><span class="file-upload-text" id="warga-photo-text">Pilih foto bukti (Wajib)...</span></div>
        <img class="image-preview">
      </div>
      
      <button type="submit" class="lr-btn" style="width: 100%; margin-top:20px; background: var(--primary); color: #fff; padding: 14px; border-radius: 10px; font-weight: 600;">Simpan Laporan</button>
    </form>
  </div>
</div>
@endif

{{-- MODAL PENGURUS UPDATE STATUS & PENGUMUMAN --}}
@if(Auth::user()->isAdmin())
<div id="modal-update" class="modal-overlay">
  <div class="modal-content fade-anim">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
      <div style="display: flex; align-items: center; gap: 12px;">
         <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(185, 131, 21, 0.15); border-radius: 50%;">
            <img src="{{ asset('images/process.png') }}" style="width: 24px;" onerror="this.style.display='none'">
         </div>
         <h3 style="font-weight: 700; font-size: 20px; margin:0;">Update Status</h3>
      </div>
      <button type="button" onclick="closeModal('modal-update')" class="lr-btn modal-close-btn">
        <img src="{{ asset('images/close.png') }}" style="width: 18px;">
      </button>
    </div>
    
    <form id="form-update-action" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="hidden-report-id">
      <select name="status" class="input-field" style="margin-bottom: 20px;" required onchange="toggleUpdateFields(this.value)">
          <option value="baru">Baru Dilaporkan</option>
          <option value="diproses">Sedang Ditangani</option>
          <option value="selesai">Penyelesaian Selesai</option>
      </select>
      
      <div id="u-proses-fields" style="display: none; background: var(--input-bg); padding: 15px; border: 1px solid var(--line); margin-bottom: 20px;">
        <label>Prioritas Penanganan (RT/RW)</label>
        <select id="u-handling-priority" name="handling_priority" class="input-field">
            <option value="tinggi">Tinggi</option>
            <option value="sedang" selected>Sedang</option>
            <option value="rendah">Rendah</option>
        </select>
      </div>

      <div id="u-selesai-fields" style="display: none; background: var(--input-bg); padding: 15px; border: 1px solid var(--line); margin-bottom: 20px;">
        <label>Catatan Penanganan</label>
        <textarea name="res_note" class="input-field" rows="2"></textarea>
        <label style="margin-top:10px; display:block;">Anggaran Penyelesaian</label>
        <input type="text" name="res_budget" class="input-field" maxlength="255" placeholder="Contoh: Rp 500.000 / Swadaya warga">
        <div class="file-upload-wrapper">
          <input type="file" name="res_photo" accept="image/*" class="file-upload-input">
          <div class="file-upload-custom"><span class="file-upload-text">Pilih foto penyelesaian...</span></div>
          <img class="image-preview">
        </div>
      </div>
      <button type="submit" class="lr-btn" style="width: 100%; background: var(--primary); color: #fff; padding: 14px; border-radius: 10px; font-weight: 600;">Simpan Perubahan</button>
    </form>
  </div>
</div>

<div id="modal-admin-announce" class="modal-overlay">
  <div class="modal-content fade-anim">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
      <h3 style="font-weight: 700; font-size: 20px; margin: 0;">Pengumuman Fasilitas Baru</h3>
      <button type="button" onclick="closeModal('modal-admin-announce')" class="lr-btn modal-close-btn">
        <img src="{{ asset('images/close.png') }}" style="width: 18px;">
      </button>
    </div>
    <form method="POST" action="{{ route('announcement.store') }}" enctype="multipart/form-data">
      @csrf
      <label>Nama Fasilitas</label>
      <input type="text" name="title" class="input-field" required>
      <label style="margin-top:10px; display:block;">Lokasi</label>
      <select name="area_id" class="input-field" required>
          @foreach(\App\Models\Area::all() as $a)
              <option value="{{ $a->id }}">{{ $a->name }}</option>
          @endforeach
      </select>
      <label style="margin-top:10px; display:block;">Deskripsi</label>
      <textarea name="description" class="input-field" rows="3" required></textarea>
      <label style="margin-top:10px; display:block;">Anggaran</label>
      <input type="text" name="budget" class="input-field" maxlength="255" placeholder="Contoh: Rp 2.500.000 / Dana kas RW" required>
      
      <div class="file-upload-wrapper">
        <input type="file" name="photo" accept="image/*" class="file-upload-input" required>
        <div class="file-upload-custom"><span class="file-upload-text">Pilih foto fasilitas...</span></div>
        <img class="image-preview">
      </div>
      
      <button type="submit" class="lr-btn" style="width: 100%; margin-top:20px; background: var(--accent); color: var(--primary-dark); padding: 14px; border-radius: 10px; font-weight:bold;">Umumkan</button>
    </form>
  </div>
</div>

{{-- EDIT PENGUMUMAN FASILITAS --}}
<div id="modal-edit-announce" class="modal-overlay">
  <div class="modal-content fade-anim">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
      <h3 style="font-weight: 700; font-size: 20px; margin: 0;">Edit Pengumuman Fasilitas</h3>
      <button type="button" onclick="closeModal('modal-edit-announce')" class="lr-btn modal-close-btn">
        <img src="{{ asset('images/close.png') }}" style="width: 18px;">
      </button>
    </div>
    <form id="form-edit-announce" method="POST" action="" enctype="multipart/form-data">
      @csrf
      <label>Nama Fasilitas</label>
      <input type="text" id="edit-ann-title" name="title" class="input-field" required>

      <label style="margin-top:10px; display:block;">Lokasi</label>
      <select id="edit-ann-area" name="area_id" class="input-field" required>
          @foreach(\App\Models\Area::all() as $a)
              <option value="{{ $a->id }}">{{ $a->name }}</option>
          @endforeach
      </select>

      <label style="margin-top:10px; display:block;">Deskripsi</label>
      <textarea id="edit-ann-desc" name="description" class="input-field" rows="3" required></textarea>

      <label style="margin-top:10px; display:block;">Anggaran</label>
      <input type="text" id="edit-ann-budget" name="budget" class="input-field" maxlength="255" required>

      {{-- Foto saat ini --}}
      <div id="edit-ann-current-wrap" style="display: none; margin-top: 12px;">
        <label style="display:block;">Foto Saat Ini</label>
        <img id="edit-ann-current-photo" src="" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 10px; border: 1px solid var(--line); margin-top: 6px;">
      </div>

      <label style="margin-top:12px; display:block;">Ganti Foto</label>
      <div class="file-upload-wrapper">
        <input type="file" id="edit-ann-photo-input" name="photo" accept="image/*" class="file-upload-input">
        <div class="file-upload-custom"><span class="file-upload-text" id="edit-ann-photo-text">Pilih foto baru...</span></div>
        <img class="image-preview">
      </div>

      <button type="submit" class="lr-btn" style="width: 100%; margin-top:20px; background: var(--accent); color: var(--primary-dark); padding: 14px; border-radius: 10px; font-weight:bold;">Simpan Perubahan</button>
    </form>
  </div>
</div>
@endif

{{-- PROFIL PENGGUNA --}}
<div id="modal-profile" class="modal-overlay">
  <div class="modal-content fade-anim" style="max-width: 400px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
      <div style="display: flex; align-items: center; gap: 12px;">
         <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; background: rgba(232, 185, 61, 0.15); border-radius: 50%;">
            <img src="{{ asset('images/user.png') }}" style="width: 24px;" onerror="this.style.display='none'">
         </div>
         <h3 style="font-weight: 700; font-size: 20px; margin:0;">Profil Pengguna</h3>
      </div>
      <button type="button" onclick="closeModal('modal-profile')" class="lr-btn modal-close-btn">
        <img src="{{ asset('images/close.png') }}" style="width: 18px;" onerror="this.style.display='none'">
      </button>
    </div>

    <div style="background: rgba(193, 67, 45, 0.1); border-left: 4px solid var(--danger); padding: 10px 12px; margin-bottom: 20px; border-radius: 4px;">
      <p style="margin: 0; font-size: 12px; color: var(--danger); line-height: 1.5;">
        <b>Catatan Penting:</b> Jika Anda mengganti nama, username, atau kata sandi, pastikan Anda mengingatnya untuk keperluan login berikutnya.
      </p>
    </div>

    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      <div style="margin-bottom: 16px;">
          <label style="font-size: 13px; font-weight: 600; color: var(--text-main);">Nama Lengkap / Pengurus</label>
          <input type="text" name="name" class="input-field" value="{{ Auth::user()->name }}" required style="margin-top: 6px;">
      </div>

      <div style="margin-bottom: 16px;">
          <label style="font-size: 13px; font-weight: 600; color: var(--text-main);">Kata Sandi Baru</label>
          <div class="pwd-wrapper">
              <input type="password" id="prof-pwd" name="password" class="input-field" placeholder="Kosongkan jika tidak diubah" style="padding-right: 48px; margin-top: 6px;">
              <button type="button" onclick="togglePwd('prof-pwd', this)" class="lr-btn-icon" style="margin-top: 3px;">
                  <img src="{{ asset('images/show.png') }}" style="width: 20px;" onerror="this.style.display='none'">
              </button>
          </div>
      </div>

      @if(Auth::user()->isAdmin())
      <div style="margin-bottom: 16px;">
          <label style="font-size: 13px; font-weight: 600; color: var(--text-main);">Username Login</label>
          <input type="text" name="username" class="input-field" value="{{ Auth::user()->username }}" required maxlength="50" autocomplete="username" style="margin-top: 6px;">
      </div>
      @endif

      <div style="margin-bottom: 24px;">
          <label style="font-size: 13px; font-weight: 600; color: var(--text-main);">Info Hak Akses</label>
          <div class="input-field bg-disabled" style="margin-top: 6px; padding: 14px 16px;">
              {{ Auth::user()->isWarga() ? 'Warga domisili ' . (Auth::user()->area->name ?? '') : 'Pengurus / Ketua ' . strtoupper(Auth::user()->role) }}
          </div>
      </div>

      <button type="submit" class="lr-btn" style="width: 100%; background: var(--warning); color: #fff; padding: 14px; border-radius: 10px; font-size: 14px; font-weight: 600;">
          <img src="{{ asset('images/check.png') }}" style="width: 16px;" onerror="this.style.display='none'"> Simpan Perubahan
      </button>
    </form>
  </div>
</div>
@endauth