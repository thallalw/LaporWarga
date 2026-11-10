<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>LaporWarga</title>
  <link rel="icon" type="image/png" href="{{ asset('images/laporwarga.png') }}">
  <meta name="theme-color" content="#1D5C4A">
  
  <style>
    :root {
      --bg-main: #FFFDF0; --text-main: #12211D; --text-muted: #5C6D64;
      --card-bg: #FFFFFF; --primary: #1D5C4A; --primary-dark: #123D31;
      --accent: #E8B93D; --danger: #C1432D; --success: #2F7A5C; --warning: #B98315;
      --line: #EBE4D3; --input-bg: #FFFFFF;
      --modal-overlay: rgba(18, 33, 29, 0.75); --hover-shadow: rgba(29, 92, 74, 0.08);
    }
    [data-theme="dark"] {
      --bg-main: #0F1714; --text-main: #EAEAEA; --text-muted: #9AA6A1;
      --card-bg: #1A2421; --primary: #2F7A5C; --primary-dark: #0A1411;
      --accent: #D4A32A; --danger: #E55B45; --success: #3DB885; --warning: #D4A32A;
      --line: #2A3833; --input-bg: #141D1A;
      --modal-overlay: rgba(0, 0, 0, 0.85); --hover-shadow: rgba(47, 122, 92, 0.15);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: system-ui, -apple-system, sans-serif; background: var(--bg-main); color: var(--text-main); min-height: 100vh; transition: background 0.2s ease, color 0.2s ease; overflow-x: hidden; -webkit-font-smoothing: antialiased; }
    .lr-btn { cursor: pointer; border: none; font-family: inherit; transition: all 0.2s ease; outline: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
    .lr-btn:hover { opacity: 0.9; }
    .lr-btn:active { transform: translateY(1px); }
    .lr-card { background: var(--card-bg); border: 1px solid var(--line); border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); transition: all 0.2s ease; }
    .header-area { background: var(--primary-dark); color: #fff; padding: 32px 24px; border-bottom: 4px solid var(--primary); }
    .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; padding: 24px 24px 0; }
    .chart-panel-container { padding: 24px; display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; align-items: flex-start; }
    .chart-panel, .info-panel { padding: 24px; display: flex; flex-direction: column; }
    .info-panel { justify-content: center; position: relative; }
    .report-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
    .report-card { padding: 20px; cursor: pointer; display: flex; flex-direction: column; }
    .report-card:hover { border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 6px 16px var(--hover-shadow); }
    .stat-card { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px 16px; border-radius: 12px; background: var(--card-bg); border: 1px solid var(--line); cursor: pointer; transition: all 0.2s ease; text-align: center; }
    .stat-card:hover { border-color: var(--primary); transform: translateY(-3px); box-shadow: 0 8px 20px var(--hover-shadow); }
    .stat-card .icon-wrapper { margin-bottom: 12px; color: var(--primary); }
    .stat-card .value { font-size: 32px; font-weight: 700; margin-bottom: 4px; line-height: 1; color: var(--text-main); }
    .stat-card .label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .progress-chart-container { display: flex; flex-direction: column; gap: 16px; margin-top: 24px; }
    .progress-row { display: flex; align-items: center; gap: 16px; }
    .progress-label { width: 85px; font-size: 13px; font-weight: 600; color: var(--text-muted); text-align: right; flex-shrink: 0; }
    .progress-track { flex-grow: 1; background: var(--line); height: 8px; border-radius: 4px; overflow: hidden; }
    .progress-fill { background: var(--primary); height: 100%; border-radius: 4px; transition: width 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .progress-value { width: 85px; font-size: 13px; font-weight: 700; color: var(--text-main); flex-shrink: 0; }
    .tabs-scroll { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
    .lr-tab { border: 1px solid var(--line); background: var(--card-bg); color: var(--text-main); border-radius: 999px; padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: all 0.2s ease; }
    .lr-tab:hover:not(.active) { background: var(--line); }
    .lr-tab.active { background: var(--primary); color: #fff; border-color: var(--primary); }
    .category-badge { display: inline-flex; align-items: center; width: fit-content; padding: 4px 10px; border-radius: 6px; color: #fff; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .priority-badge { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 6px; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .priority-tinggi { background: rgba(193, 67, 45, 0.15); color: var(--danger); border: 1px solid var(--danger); }
    .priority-sedang { background: rgba(185, 131, 21, 0.15); color: var(--warning); border: 1px solid var(--warning); }
    .priority-rendah { background: rgba(29, 92, 74, 0.12); color: var(--primary); border: 1px solid var(--primary); }
    .badge-pengumuman { background: var(--accent) !important; color: var(--primary-dark) !important; border: 1px solid #D4A32A; }
    .status-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 6px; flex-shrink: 0; }
    .status-baru { background-color: var(--danger); }
    .status-diproses { background-color: var(--warning); }
    .status-selesai { background-color: var(--success); }
    .status-info { background-color: var(--accent); }
    .input-field { font-family: inherit; background: var(--input-bg); color: var(--text-main); border: 1px solid var(--line); border-radius: 10px; padding: 14px 16px; font-size: 14px; width: 100%; margin-top: 6px; transition: all 0.2s ease; }
    .input-field:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px var(--hover-shadow); }
    .bg-disabled { background: var(--line); }
    
    select.input-field { cursor: pointer; appearance: none; background-image: url("{{ asset('images/dropdown.png') }}"); background-repeat: no-repeat; background-position: right 16px center; background-size: 14px; padding-right: 40px; }
    select.input-field option { background-color: var(--card-bg); color: var(--text-main); padding: 10px; }

    .file-upload-wrapper { position: relative; width: 100%; margin-top: 6px; }
    .file-upload-input { position: absolute; left: 0; top: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10; }
    .file-upload-custom { display: flex; align-items: center; gap: 12px; padding: 14px 16px; background: var(--input-bg); border: 1px dashed var(--text-muted); border-radius: 10px; color: var(--text-muted); font-size: 14px; }
    .image-preview { width: 100%; max-height: 240px; object-fit: contain; border-radius: 8px; margin-top: 10px; display: none; background: var(--input-bg); border: 1px dashed var(--line); padding: 8px; cursor: zoom-in; }
    .photo-evidence { width: 100%; max-height: 300px; object-fit: cover; border-radius: 10px; margin-top: 12px; border: 1px solid var(--line); cursor: zoom-in; }
    .fab-btn { position: fixed; bottom: 30px; right: 30px; background: var(--accent); color: var(--primary-dark); width: 60px; height: 60px; border-radius: 50%; box-shadow: 0 8px 24px rgba(0,0,0,0.25); z-index: 10; display: flex; align-items: center; justify-content: center; }
    .modal-overlay { display: none; position: fixed; inset: 0; background: var(--modal-overlay); z-index: 100; backdrop-filter: blur(4px); overflow-y: auto; align-items: flex-start; padding: 5vh 16px; }
    .modal-content { width: 100%; max-width: 500px; margin: 0 auto 50px auto; padding: 32px 24px; background: var(--card-bg); border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
    .modal-close-btn { background: var(--input-bg); color: var(--text-main); width: 36px; height: 36px; border-radius: 50%; font-size: 18px; border: 1px solid var(--line); display: flex; align-items: center; justify-content: center; }
    .image-viewer-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 1000; align-items: center; justify-content: center; padding: 0; }
    .image-viewer-close { position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.2); color: #fff; width: 44px; height: 44px; border-radius: 50%; z-index: 1001; }
    .image-viewer-img { max-width: 100%; max-height: 100vh; object-fit: contain; }
    
    .pwd-wrapper { position: relative; width: 100%; }
    .pwd-wrapper .lr-btn-icon { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; margin-top: 3px; }
    
    .toast-container { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none; align-items: center; width: 100%; padding: 0 20px; }
    .toast { background: var(--card-bg); border: 1px solid var(--line); padding: 16px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: flex; gap: 12px; align-items: flex-start; min-width: 320px; max-width: 90vw; pointer-events: auto; animation: popInCenter 0.4s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .link-text { color: var(--primary); font-weight: 600; cursor: pointer; text-decoration: none; }
    
    @keyframes fadein { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes popin { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .fade-anim { animation: fadein 0.3s ease forwards; }
    @keyframes popInCenter { 0% { opacity: 0; transform: scale(0.85); } 100% { opacity: 1; transform: scale(1); } }
    @keyframes fadeOutCenter { 0% { opacity: 1; transform: scale(1); } 100% { opacity: 0; transform: scale(0.85); } }

    @media (max-width: 768px) {
      .chart-panel-container { grid-template-columns: 1fr; padding: 16px; gap: 20px; }
      .chart-panel, .info-panel { padding: 20px; }
      .header-area { padding: 24px 16px; }
      .stats-container { padding: 20px 16px 0; grid-template-columns: repeat(2, 1fr); gap: 12px; }
      .modal-overlay { padding: 16px; }
      .fab-btn { bottom: 20px; right: 20px; }
    }
  </style>
  
  <script>
    (function() {
      const savedTheme = localStorage.getItem("lr-theme") || "light";
      document.documentElement.setAttribute("data-theme", savedTheme);
    })();
  </script>
</head>
<body>

<div id="toast-container" class="toast-container"></div>
<div id="lr-app">
    @yield('content')
</div>

@include('components.modals')

<script>
  const $ = (id) => document.getElementById(id);
  window.openModal = function(id) { $(id).style.display = "flex"; }
  window.closeModal = function(id) { $(id).style.display = "none"; }
  window.viewImage = function(src) { $("viewer-img").src = src; $("modal-image-viewer").style.display = "flex"; };
  
  window.toggleAuthMode = function(mode) {
      if (mode === 'register') {
          $("warga-login-form").style.display = "none";
          $("warga-register-form").style.display = "block";
      } else {
          $("warga-login-form").style.display = "block";
          $("warga-register-form").style.display = "none";
      }
  };

  // Form update status: tampilkan field sesuai status yang dipilih
  window.toggleUpdateFields = function(status) {
      $('u-selesai-fields').style.display = status === 'selesai' ? 'block' : 'none';
      $('u-proses-fields').style.display = status === 'diproses' ? 'block' : 'none';
      $('u-handling-priority').required = status === 'diproses';
  };

  // Buka modal Update Status dengan status & prioritas penanganan saat ini
  window.openStatusModal = function(id, status, handlingPriority) {
      $('hidden-report-id').value = id;
      $('form-update-action').action = `/report/${id}/status`;
      document.querySelector('#form-update-action select[name="status"]').value = status;
      $('u-handling-priority').value = handlingPriority || 'sedang';
      toggleUpdateFields(status);
      openModal('modal-update');
  };

  window.togglePwd = function (inputId, btnEl) {
    const inp = $(inputId);
    const img = btnEl.querySelector('img');
    if (inp.type === "password") { 
        inp.type = "text"; 
        if (img) img.src = "{{ asset('images/hide.png') }}"; 
    } else { 
        inp.type = "password"; 
        if (img) img.src = "{{ asset('images/show.png') }}"; 
    }
  };

  window.openCreateReport = function() {
      $('form-warga-action').action = "{{ route('report.store') }}";
      $('warga-form-title').innerText = "Form Laporan Baru";
      $('form-warga-action').reset();$('warga-photo-text').textContent = "Pilih foto bukti (Wajib)...";
      $('warga-photo-input').required = true;
      const preview = document.querySelector('#modal-warga .image-preview');
      if(preview) preview.style.display = 'none';
      openModal('modal-warga');
  };

  window.editReport = function(id, title, cat, area, address, desc, priority) {
      $('form-warga-action').action = `/report/${id}/update`;
      $('warga-form-title').innerText = "Edit Laporanku";
      $('warga-title-input').value = title;
      $('warga-cat-input').value = cat;
      $('warga-area-input').value = area;
      $('warga-addr-input').value = address;
      $('warga-desc-input').value = desc;
      $('warga-priority-input').value = priority || 'sedang';
      $('warga-photo-text').textContent = "Pilih foto baru (Opsional)...";
      $('warga-photo-input').required = false; 
      const preview = document.querySelector('#modal-warga .image-preview');
      if(preview) preview.style.display = 'none';
      openModal('modal-warga');
  };

  // BARU: Edit pengumuman fasilitas (khusus admin)
  window.editAnnouncement = function(id, title, areaId, desc, budget, photoUrl) {
      $('form-edit-announce').reset();
      $('form-edit-announce').action = `/announcement/${id}/update`;
      $('edit-ann-title').value = title;
      $('edit-ann-area').value = areaId;
      $('edit-ann-desc').value = desc;
      $('edit-ann-budget').value = budget;
      $('edit-ann-photo-text').textContent = "Pilih foto baru (Opsional)...";

      const preview = document.querySelector('#modal-edit-announce .image-preview');
      if (preview) preview.style.display = 'none';

      if (photoUrl) {
          $('edit-ann-current-photo').src = photoUrl;
          $('edit-ann-current-wrap').style.display = 'block';
      } else {
          $('edit-ann-current-wrap').style.display = 'none';
      }

      openModal('modal-edit-announce');
  };

  function updateThemeIcon() {
    const isDark = document.documentElement.getAttribute("data-theme") === "dark";
    $("theme-toggle").innerHTML = `<img src="{{ asset('images') }}/${isDark ? 'sun.png' : 'moon.png'}" style="width: 24px; height: 24px;" onerror="this.style.display='none'">`;
  }

  if($("theme-toggle")){
      $("theme-toggle").addEventListener("click", function() {
        const isDark = document.documentElement.getAttribute("data-theme") === "dark";
        const newTheme = isDark ? "light" : "dark";
        document.documentElement.setAttribute("data-theme", newTheme);
        localStorage.setItem("lr-theme", newTheme);
        updateThemeIcon();
      });
      updateThemeIcon();
  }

  function showToast(title, msg, type = 'info') {
      const container = $("toast-container");
      const toast = document.createElement("div");
      toast.className = "toast";
      
      let iconColor = "var(--primary)";
      if (type === 'success') { iconColor = "var(--success)"; toast.style.borderLeft = "4px solid var(--success)"; }
      if (type === 'error') { iconColor = "var(--danger)"; toast.style.borderLeft = "4px solid var(--danger)"; }

      toast.innerHTML = `<div style="color:${iconColor}; padding-top:2px;">•</div><div><h4 style="margin:0 0 4px; font-size: 14px; font-weight: 700; color: var(--text-main);"></h4><p style="margin: 0; font-size: 13px; color: var(--text-muted); line-height: 1.4;"></p></div>`;
      toast.querySelector('h4').textContent = title;
      toast.querySelector('p').textContent = msg;
      container.appendChild(toast);
      
      setTimeout(() => {
          toast.style.animation = "fadeOutCenter 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) forwards";
          setTimeout(() => toast.remove(), 300);
      }, 5000);
  }

  document.querySelectorAll('.file-upload-input').forEach(input => {
      input.addEventListener('change', function(e) {
          const file = e.target.files[0];
          const wrapper = this.closest('.file-upload-wrapper').parentElement;
          const preview = wrapper.querySelector('.image-preview');
          const text = wrapper.querySelector('.file-upload-text');

          if (file && file.type.startsWith('image/')) {
              text.textContent = file.name;
              const reader = new FileReader();
              reader.onload = function(ev) {
                  preview.src = ev.target.result;
                  preview.style.display = "block";
              };
              reader.readAsDataURL(file);
          } else {
              text.textContent = "Pilih file gambar...";
              preview.style.display = "none";
          }
      });
  });

  window.filterReports = function() {
      const catFilter = $("lr-cat-tabs").querySelector('.active')?.dataset.cat || 'semua';
      const statusFilter = $("status-filter-select").value;
      const reports = document.querySelectorAll('.report-card');
      
      reports.forEach(card => {
          const cat = card.dataset.category;
          const type = card.dataset.type;
          const status = card.dataset.status;
          const isArchived = card.dataset.archived === "true";

          let show = true;

          if (catFilter === "arsip") {
              if (!isArchived) show = false;
          } else {
              if (isArchived) show = false;
              else if (catFilter === "pengumuman" && type !== "announcement") show = false;
              else if (catFilter !== "semua" && catFilter !== "pengumuman" && cat !== catFilter) show = false;
              
              if (statusFilter !== "semua" && status !== statusFilter) show = false;
          }

          card.style.display = show ? "flex" : "none";
      });
  };

  if($("lr-cat-tabs")) {
      $("lr-cat-tabs").querySelectorAll("button").forEach(btn => {
          btn.addEventListener("click", () => {
              $("lr-cat-tabs").querySelectorAll("button").forEach(b => b.classList.remove("active"));
              btn.classList.add("active");
              filterReports();
          });
      });
      $("status-filter-select").addEventListener("change", filterReports);
  }

    // FITUR CUSTOM DELETE MODAL
  let deleteFormAction = null;
  window.confirmDelete = function(actionUrl) {
      deleteFormAction = actionUrl;
      openModal('modal-confirm');
  };
  
  // Jika tombol 'Ya, Hapus' di dalam modal diklik, maka buat & submit formnya
  document.getElementById('btn-confirm-delete')?.addEventListener('click', function() {
      if (deleteFormAction) {
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = deleteFormAction;
          
          const csrf = document.createElement('input');
          csrf.type = 'hidden';
          csrf.name = '_token';
          csrf.value = '{{ csrf_token() }}';
          form.appendChild(csrf);
          
          document.body.appendChild(form);
          form.submit();
      }
  });
  
  // Update subtitle saat toggle daftar/login
  window.toggleAuthMode = function(mode) {
      if (mode === 'register') {
          $("warga-login-form").style.display = "none";
          $("warga-register-form").style.display = "block";
          if($("auth-subtitle")) $("auth-subtitle").innerText = "Daftar akun baru.";
      } else {
          $("warga-login-form").style.display = "block";
          $("warga-register-form").style.display = "none";
          if($("auth-subtitle")) $("auth-subtitle").innerText = "Masuk untuk melanjutkan.";
      }
  };

</script>

@if(session('success'))
    <script>showToast("Sukses", "{{ session('success') }}", "success");</script>
@endif
@if(session('error'))
    <script>showToast("Gagal", "{{ session('error') }}", "error");</script>
@endif
@if($errors->any())
    <script>showToast("Terjadi Kesalahan", "{{ $errors->first() }}", "error");</script>
@endif

{{-- Notifikasi dari tabel notifications (belum dibaca) --}}
@auth
    @if(isset($notifications) && $notifications->isNotEmpty())
        <script>
            @foreach($notifications->take(4) as $n)
                showToast(@json($n->title), @json($n->message), @json($n->type === 'success' ? 'success' : ($n->type === 'error' ? 'error' : 'info')));
            @endforeach
            @if($notifications->count() > 4)
                showToast("Notifikasi", "Dan {{ $notifications->count() - 4 }} notifikasi lainnya.", "info");
            @endif
        </script>
    @endif
@endauth

</body>
</html>