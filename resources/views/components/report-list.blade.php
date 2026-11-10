<div style="padding: 0 24px 40px; margin-top: 24px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div id="lr-cat-tabs" class="tabs-scroll">
        <button class="lr-tab active" data-cat="semua">Semua</button>
        <button class="lr-tab" data-cat="pengumuman">Fasilitas Baru</button>
        @foreach($categories as $c)
            <button class="lr-tab" data-cat="{{ strtolower($c->name) }}">{{ $c->name }}</button>
        @endforeach
        <button class="lr-tab" data-cat="arsip">Arsip</button>
    </div>
    <div style="width: 200px;">
      <select id="status-filter-select" class="input-field" style="margin: 0; padding: 10px 16px; font-weight: 600;">
          <option value="semua">Semua Status</option>
          <option value="baru">Baru Dilaporkan</option>
          <option value="diproses">Sedang Diproses</option>
          <option value="selesai">Telah Selesai</option>
      </select>
    </div>
  </div>

  <div id="lr-list" class="report-grid">
    {{-- Render Pengumuman --}}
    @foreach($announcements as $ann)
      <div class="lr-card fade-anim report-card" data-category="pengumuman" data-type="announcement" data-status="info" data-archived="false" style="border: 1px solid var(--accent)">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
          <span class="category-badge badge-pengumuman">Fasilitas Baru</span>
        </div>
        <h4 style="font-size: 16px; font-weight: 600; margin: 0 0 8px;">{{ $ann->title }}</h4>
        <p style="font-size: 13px; color: var(--text-muted); margin: 0 0 6px;">Lokasi: {{ $ann->area->name }}</p>
        <p style="font-size: 13px; margin: 0 0 6px; line-height: 1.5;">{{ $ann->description }}</p>
        @if(filled($ann->budget))
            <p style="font-size: 13px; margin: 0 0 12px; font-weight: 600;">Anggaran: {{ $ann->budget }}</p>
        @else
            <div style="margin-bottom: 6px;"></div>
        @endif
        
        @if($ann->photo_path)
            <img src="{{ asset('storage/' . $ann->photo_path) }}" class="photo-evidence" onclick="viewImage(this.src)">
        @endif

        <div style="border-top: 1px solid var(--line); margin-top: auto; padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 13px; font-weight: 500;">Oleh: {{ $ann->author->username ?? $ann->author->name }} <span style="color: var(--text-muted); font-weight: 500;">({{ $ann->author->roleLabel() }})</span></span>
            @auth
              @if(Auth::user()->isAdmin())
                <div style="display: flex; align-items: center; gap: 10px;">
                    {{-- BARU: Tombol Edit Pengumuman --}}
                    <button type="button" onclick='editAnnouncement({{ $ann->id }}, @json($ann->title), {{ $ann->area_id }}, @json($ann->description), @json($ann->budget), @json($ann->photo_path ? asset('storage/' . $ann->photo_path) : null))' class="lr-btn" style="color:var(--warning); background:none; font-size:12px; font-weight:600; padding: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        <img src="{{ asset('images/edit.png') }}" style="width: 14px; height: 14px;" onerror="this.style.display='none'"> Edit
                    </button>

                    {{-- Tombol Hapus Pengumuman (custom Delete Modal) --}}
                    <button type="button" onclick="confirmDelete('{{ route('announcement.destroy', $ann->id) }}')" class="lr-btn" style="color:var(--danger); background:none; font-size:12px; font-weight:600; padding: 4px; display: inline-flex; align-items: center; gap: 4px;">
                        <img src="{{ asset('images/bin.png') }}" style="width: 14px; height: 14px;" onerror="this.style.display='none'"> Hapus
                    </button>
                </div>
              @endif
            @endauth
        </div>
      </div>
    @endforeach

    {{-- Render Laporan Warga --}}
    @foreach($reports as $rep)
      @php
          $isMyReport = Auth::check() && Auth::id() == $rep->user_id;
          $cardBorder = $isMyReport ? '1px solid var(--primary)' : '1px solid var(--line)';
          $statusColors = ['baru' => 'status-baru', 'diproses' => 'status-diproses', 'selesai' => 'status-selesai'];
      @endphp
      <div class="lr-card fade-anim report-card" data-category="{{ strtolower($rep->category->name) }}" data-type="laporan" data-status="{{ $rep->status }}" data-archived="{{ $rep->archived_at ? 'true' : 'false' }}" style="border: {{ $cardBorder }}">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
          <span class="category-badge" style="background: {{ $rep->category->color ?? '#1D5C4A' }};">{{ $rep->category->name }}</span>
          <span style="display: inline-flex; align-items: center; gap: 10px;">
              <span class="priority-badge priority-{{ $rep->priority }}">Prioritas {{ ucfirst($rep->priority) }}</span>
              <span style="font-size: 12px; font-weight: 600; display: inline-flex; align-items: center;">
                  <span class="status-dot {{ $statusColors[$rep->status] }}"></span> {{ ucfirst($rep->status) }}
              </span>
          </span>
        </div>
        
        <h4 style="font-size: 16px; font-weight: 600; margin: 0 0 8px;">{{ $rep->title }}</h4>
        <p style="font-size: 13px; color: var(--text-muted); margin: 0 0 6px;">{{ $rep->area->name }} - {{ $rep->specific_address }}</p>
        <p style="font-size: 13px; margin: 0 0 12px; line-height: 1.5;">{{ $rep->description }}</p>
        
        @if($rep->photo_path)
            <img src="{{ asset('storage/' . $rep->photo_path) }}" class="photo-evidence" onclick="viewImage(this.src)">
        @endif

        @if($rep->handling_priority)
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px; margin-top: 12px; font-size: 12px;">
                <span class="priority-badge priority-{{ $rep->handling_priority }}">Prioritas Penanganan: {{ ucfirst($rep->handling_priority) }}</span>
                @if($rep->prioritizer)
                    <span style="color: var(--text-muted);">oleh {{ $rep->prioritizer->username }} ({{ $rep->prioritizer->roleLabel() }})</span>
                @endif
            </div>
        @endif

        @if($rep->status == 'selesai')
            <div style="background: var(--bg-main); padding: 12px; border-left: 4px solid var(--success); border-radius: 8px; margin-top: 12px; font-size: 13px;">
                <p style="margin: 0 0 4px; font-weight: 600; color: var(--success);">Telah Diselesaikan oleh {{ $rep->resolver->username ?? 'Pengurus' }}@if($rep->resolver) ({{ $rep->resolver->roleLabel() }})@endif:</p>
                <p style="margin: 0 0 8px; color: var(--text-main);">{{ $rep->resolution_note }}</p>
                @if(filled($rep->resolution_budget))
                    <p style="margin: 0 0 8px; font-weight: 600; color: var(--text-main);">Anggaran: {{ $rep->resolution_budget }}</p>
                @endif
                @if($rep->resolution_photo_path)
                    <img src="{{ asset('storage/' . $rep->resolution_photo_path) }}" class="photo-evidence" style="max-height: 150px;" onclick="viewImage(this.src)">
                @endif
            </div>
        @endif

        <div style="border-top: 1px solid var(--line); margin-top: auto; padding-top: 14px; display: flex; justify-content: space-between; align-items: center; gap: 8px;">
          <span style="font-size: 13px; font-weight: 500;">Oleh: {{ $rep->user->name }} @if($isMyReport) <b style="color:var(--primary);">(Anda)</b> @endif</span>
          
          <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: flex-end;">
              <form method="POST" action="{{ route('report.like', $rep->id) }}" style="margin:0;">
                  @csrf
                  <button type="submit" class="lr-btn" style="font-size: 12px; font-weight: 600; color: var(--primary); background: none; padding: 4px; display: inline-flex; align-items: center; gap: 4px;">
                      <img src="{{ asset('images/upvote.png') }}" style="width: 14px; height: 14px;" onerror="this.style.display='none'"> Suka ({{ $rep->likes_count }})
                  </button>
              </form>
              
              @auth
                  @if($isMyReport && $rep->status == 'baru')
                      <button type="button" onclick='editReport({{ $rep->id }}, @json($rep->title), {{ $rep->category_id }}, {{ $rep->area_id }}, @json($rep->specific_address), @json($rep->description), @json($rep->priority))' class="lr-btn" style="font-size: 12px; font-weight: 600; color: var(--warning); background: none; padding: 4px; display: inline-flex; align-items: center; gap: 4px;">
                          <img src="{{ asset('images/edit.png') }}" style="width: 14px; height: 14px;" onerror="this.style.display='none'"> Edit
                      </button>
                  @endif
                  
                  @if(($isMyReport && $rep->status == 'baru') || Auth::user()->isAdmin())
                      {{-- REVISI: Panggil custom Delete Modal --}}
                      <button type="button" onclick="confirmDelete('{{ route('report.destroy', $rep->id) }}')" class="lr-btn" style="font-size: 12px; font-weight: 600; color: var(--danger); background: none; padding: 4px; display: inline-flex; align-items: center; gap: 4px;">
                          <img src="{{ asset('images/bin.png') }}" style="width: 14px; height: 14px;" onerror="this.style.display='none'"> Hapus
                      </button>
                  @endif
                  
                  @if(Auth::user()->isAdmin())
                      <button type="button" onclick="openStatusModal({{ $rep->id }}, '{{ $rep->status }}', '{{ $rep->handling_priority ?? 'sedang' }}')" class="lr-btn" style="font-size: 12px; font-weight: 600; color: var(--warning); background: none; padding: 4px; display: inline-flex; align-items: center; gap: 4px;">
                          <img src="{{ asset('images/edit.png') }}" style="width: 14px; height: 14px;" onerror="this.style.display='none'"> Status
                      </button>
                  @endif
                  
                  <form method="POST" action="{{ route('report.archive', $rep->id) }}" style="margin:0;">
                      @csrf
                      <button type="submit" class="lr-btn" style="font-size: 12px; font-weight: 600; color: var(--text-muted); background: none; padding: 4px; display: inline-flex; align-items: center; gap: 4px;">
                          <img src="{{ asset('images/arsip.png') }}" style="width: 14px; height: 14px;" onerror="this.style.display='none'"> {{ $rep->archived_at ? 'Buka' : 'Arsip' }}
                      </button>
                  </form>
              @endauth
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>