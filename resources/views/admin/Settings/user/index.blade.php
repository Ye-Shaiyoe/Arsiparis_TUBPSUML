@extends('layouts.admin')

@section('title', 'Data Pegawai')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- STATISTICS --}}
    <div class="stat-grid">
        <div class="stat-card blue">
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-sub">{{ $stats['total_users_registered'] }} user, {{ $stats['total_admins'] }} admin</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Total Surat</div>
            <div class="stat-value">{{ $stats['total_surats'] }}</div>
            <div class="stat-sub">{{ $stats['total_surats_selesai'] }} selesai</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-label">Surat Proses</div>
            <div class="stat-value">{{ $stats['total_surats_proses'] }}</div>
            <div class="stat-sub">Menunggu approval</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Surat Ditolak</div>
            <div class="stat-value">{{ $stats['total_surats_ditolak'] }}</div>
            <div class="stat-sub">Perlu revisi</div>
        </div>
    </div>

    {{-- FILTERS & SEARCH --}}
    <div class="card">
        <form method="GET" data-turbo="false" style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 12px; align-items: end;">
                {{-- Search --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">
                        Cari Nama / Email
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari pegawai..."
                           style="width: 100%; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                </div>

                {{-- Filter Role --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">
                        Filter Role
                    </label>
                    <select name="role" style="width: 100%; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                        <option value="">Semua Role</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin_aspirasi" {{ request('role') === 'admin_aspirasi' ? 'selected' : '' }}>Admin_Arsiparis</option>
                        <option value="admin_kasubbag_tu" {{ request('role') === 'admin_kasubbag_tu' ? 'selected' : '' }}>Admin_Kasubbag_TU</option>
                        <option value="admin_kepala_balai" {{ request('role') === 'admin_kepala_balai' ? 'selected' : '' }}>Admin_Kepala_Balai</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin (Lama)</option>
                    </select>
                </div>

                {{-- Sort --}}
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">
                        Urutkan
                    </label>
                    <select name="sort" style="width: 100%; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px;">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama</option>
                        <option value="total_surats" {{ request('sort') === 'total_surats' ? 'selected' : '' }}>Jumlah Surat</option>
                    </select>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        🔍 Cari
                    </button>
                </div>
            </div>

            @if(request()->hasAny(['search', 'role', 'sort']))
                <div style="text-align: right;">
                    <a href="{{ route('admin.users.index') }}" class="btn" style="font-size: 12px;">
                        ✕ Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- DATA PEGAWAI TABLE --}}
    <div class="card">

        {{-- Flash messages --}}
        @if(session('success'))
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:10px 16px; font-size:13px; color:#166534; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 16px; font-size:13px; color:#b91c1c; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                ❌ {{ session('error') }}
            </div>
        @endif

        <div class="section-header" style="margin-bottom: 16px;">
            <h2>📋 Data Pegawai / Pengguna</h2>
            <div style="display:flex; align-items:center; gap:12px;">
                <small>Total: {{ $users->total() }} pengguna</small>
                <button type="button" onclick="document.getElementById('modalBuatAkun').style.display='flex'"
                    class="btn btn-primary" style="font-size:13px; padding:7px 16px; display:flex; align-items:center; gap:6px;">
                    ➕ Buat Akun
                </button>
            </div>
        </div>

        @if($users->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%; text-align: center;">Profile</th>
                            <th style="width: 25%;">Nama</th>
                            <th style="width: 20%;">Role</th>
                            <th style="width: 12%; text-align: center;">Total Surat</th>
                            <th style="width: 11%; text-align: center;">Selesai</th>
                            <th style="width: 11%; text-align: center;">Ditolak</th>
                            <th style="width: 11%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td style="text-align: center;">
                                    @if($user->profile_photo)
                                        <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: auto;">
                                    @else
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #1e3a5f; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold; margin: auto;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $user->role !== 'user' ? 'badge-blue' : 'badge-gray' }}">
                                        {{ $user->getRoleLabel() }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <strong style="font-size: 14px;">{{ $user->total_surats }}</strong>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-green">{{ $user->surats_selesai }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-red">{{ $user->surats_ditolak }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center;">
                                        @if($user && $user->uuid)
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm" title="Lihat detail">
                                                👁️
                                            </a>
                                        @endif
                                        @if($user && $user->id !== auth()->id())
                                            {{-- Tombol Ubah Role (hanya untuk user biasa) --}}
                                            @if($user->role === 'user')
                                                <button type="button" class="btn btn-sm" title="Ubah Role"
                                                    style="color: #1d4ed8;"
                                                    onclick="bukaModalUbahRole('{{ $user->uuid }}', '{{ addslashes($user->name) }}', '{{ $user->role }}')">
                                                    🔑
                                                </button>
                                            @endif
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Yakin hapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm" style="color: #b91c1c;" title="Hapus">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px; color: #9ca3af;">
                <p style="font-size: 14px;">Tidak ada pengguna ditemukan</p>
            </div>
        @endif
    </div>

</div>

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 1fr 1fr 1fr"] {
            grid-template-columns: 1fr 1fr !important;
        }
        
        table { font-size: 12px; }
        thead th { padding: 8px 10px; }
        tbody td { padding: 8px 10px; }
        
        th[style*="width: 25%"],
        th[style*="width: 20%"],
        th[style*="width: 12%"],
        th[style*="width: 10%"],
        th[style*="width: 11%"] {
            width: auto !important;
        }
    }

    /* ── Modal Buat Akun ── */
    .modal-backdrop {
        position: fixed; inset: 0; z-index: 1000;
        background: rgba(0,0,0,0.5);
        display: none; align-items: center; justify-content: center;
        padding: 16px;
    }
    .modal-backdrop.active { display: flex; }
    .modal-box {
        background: #fff; border-radius: 14px;
        width: min(96vw, 500px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        overflow: hidden;
    }
    .modal-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between;
    }
    .modal-header h3 { margin:0; font-size:16px; font-weight:700; color:#111827; }
    .modal-close {
        background:none; border:none; cursor:pointer;
        color:#6b7280; font-size:20px; line-height:1; padding:2px 6px;
        border-radius:6px; transition: background .15s;
    }
    .modal-close:hover { background:#f3f4f6; color:#111827; }
    .modal-body { padding: 20px 24px; display: flex; flex-direction: column; gap: 14px; }
    .modal-footer {
        padding: 14px 24px 20px;
        display: flex; gap: 10px; justify-content: flex-end;
    }
    .mf-group { display: flex; flex-direction: column; gap: 5px; }
    .mf-label { font-size: 12px; font-weight: 600; color: #374151; }
    .mf-input {
        padding: 9px 12px; border: 1px solid #d1d5db;
        border-radius: 8px; font-size: 13px; width: 100%;
        transition: border-color .15s, box-shadow .15s;
        outline: none;
    }
    .mf-input:focus { border-color: #1e3a5f; box-shadow: 0 0 0 3px rgba(30,58,95,0.1); }
    .mf-hint { font-size: 11px; color: #9ca3af; }
    .mf-error { font-size: 11px; color: #dc2626; }
    .nip-counter-modal {
        font-size: 11px; font-weight: 600; color: #9ca3af;
        text-align: right; margin-top: 2px;
        display: none;
    }
</style>

{{-- ══ Modal Ubah Role ══ --}}
<div id="modalUbahRole" class="modal-backdrop" onclick="if(event.target===this) tutupModalRole()">
    <div class="modal-box">
        <div class="modal-header">
            <h3>🔑 Ubah Role Pengguna</h3>
            <button type="button" class="modal-close" onclick="tutupModalRole()">✕</button>
        </div>

        <form method="POST" id="formUbahRole">
            @csrf
            @method('PATCH')
            <div class="modal-body">
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:10px 14px; font-size:12px; color:#1e40af; display:flex; align-items:center; gap:8px;">
                    <span>👤</span>
                    <span>Mengubah role untuk: <strong id="labelNamaUser">—</strong></span>
                </div>

                <div class="mf-group">
                    <label class="mf-label" for="r_role">Role Baru <span style="color:#dc2626">*</span></label>
                    <select class="mf-input" id="r_role" name="role" required>
                        <option value="user">User</option>
                        <option value="admin_aspirasi">Arsiparis</option>
                        <option value="admin_kasubbag_tu">Kasubbag TU</option>
                        <option value="admin_kepala_balai">Kepala Balai</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" onclick="tutupModalRole()">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitRole">
                    💾 Simpan Role
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ Modal Buat Akun Baru ══ --}}
<div id="modalBuatAkun" class="modal-backdrop" onclick="if(event.target===this) tutupModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h3>➕ Buat Akun Pengguna</h3>
            <button type="button" class="modal-close" onclick="tutupModal()">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" id="formBuatAkun">
            @csrf
            <div class="modal-body">

                {{-- Flash error dari server (setelah redirect back) --}}
                @if($errors->any())
                    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 14px; font-size:12px; color:#b91c1c;">
                        <ul style="margin:0; padding-left:16px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Nama --}}
                <div class="mf-group">
                    <label class="mf-label" for="m_name">Nama Lengkap <span style="color:#dc2626">*</span></label>
                    <input class="mf-input" id="m_name" type="text" name="name"
                        value="{{ old('name') }}" placeholder="Nama lengkap" required maxlength="255">
                </div>

                {{-- Email --}}
                <div class="mf-group">
                    <label class="mf-label" for="m_email">Email <span style="color:#dc2626">*</span></label>
                    <input class="mf-input" id="m_email" type="email" name="email"
                        value="{{ old('email') }}" placeholder="email@domain.com" required maxlength="255">
                    <span class="mf-hint">Password acak akan dikirim ke email ini secara otomatis.</span>
                </div>

                {{-- NIP --}}
                <div class="mf-group">
                    <label class="mf-label" for="m_nip">NIP <span style="color:#9ca3af; font-weight:400;">(opsional)</span></label>
                    <input class="mf-input" id="m_nip" type="text" name="nip"
                        value="{{ old('nip') }}" placeholder="18 digit angka"
                        maxlength="18" inputmode="numeric"
                        oninput="onModalNipInput(this)">
                    <div id="m_nip_counter" class="nip-counter-modal">0/18</div>
                </div>

                {{-- Role --}}
                <div class="mf-group">
                    <label class="mf-label" for="m_role">Role <span style="color:#dc2626">*</span></label>
                    <select class="mf-input" id="m_role" name="role" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih role --</option>
                        <option value="user"               {{ old('role') === 'user'               ? 'selected' : '' }}>User</option>
                        <option value="admin_aspirasi"     {{ old('role') === 'admin_aspirasi'     ? 'selected' : '' }}>Arsiparis</option>
                        <option value="admin_kasubbag_tu"  {{ old('role') === 'admin_kasubbag_tu'  ? 'selected' : '' }}>Kasubbag TU</option>
                        <option value="admin_kepala_balai" {{ old('role') === 'admin_kepala_balai' ? 'selected' : '' }}>Kepala Balai</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn" onclick="tutupModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitBuat">
                    📧 Buat & Kirim Email
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Buka modal otomatis jika ada error validasi (setelah redirect back)
    @if($errors->any())
        document.getElementById('modalBuatAkun').style.display = 'flex';
    @endif

    function tutupModal() {
        document.getElementById('modalBuatAkun').style.display = 'none';
    }

    // ── Modal Ubah Role ──
    function bukaModalUbahRole(uuid, nama, roleSaat) {
        document.getElementById('labelNamaUser').textContent = nama;
        document.getElementById('r_role').value = roleSaat;

        // Set action form ke route updateRole dengan uuid
        const baseUrl = '{{ url("Admin/Settings/Users") }}';
        document.getElementById('formUbahRole').action = baseUrl + '/' + uuid + '/role';

        document.getElementById('modalUbahRole').style.display = 'flex';
    }

    function tutupModalRole() {
        document.getElementById('modalUbahRole').style.display = 'none';
    }

    document.getElementById('formUbahRole').addEventListener('submit', function () {
        const btn = document.getElementById('btnSubmitRole');
        btn.disabled = true;
        btn.textContent = '⏳ Menyimpan...';
    });

    // NIP counter di dalam modal
    function onModalNipInput(input) {
        input.value = input.value.replace(/\D/g, '').slice(0, 18);
        const len     = input.value.length;
        const counter = document.getElementById('m_nip_counter');

        counter.style.display = len > 0 ? 'block' : 'none';
        counter.textContent   = len + '/18';
        counter.style.color   = len === 18 ? '#16a34a' : '#9ca3af';
        input.style.borderColor = len === 18 ? '#16a34a'
                                : len > 0    ? ''
                                : '';
    }

    // Init counter jika ada old value
    const nipModalInput = document.getElementById('m_nip');
    if (nipModalInput && nipModalInput.value.length > 0) onModalNipInput(nipModalInput);

    // Prevent double submit
    document.getElementById('formBuatAkun').addEventListener('submit', function () {
        const btn = document.getElementById('btnSubmitBuat');
        btn.disabled = true;
        btn.textContent = '⏳ Memproses...';
    });
</script>
@endsection
