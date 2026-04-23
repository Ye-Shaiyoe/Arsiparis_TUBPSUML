<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

{{-- ========== MODAL PREVIEW FILE ========== --}}
<div id="previewModal" style="display:none; position:fixed; inset:0; z-index:9999;
            background:rgba(10,15,30,.75); backdrop-filter:blur(6px);
            animation:fadeIn .2s ease;" onclick="handleOverlayClick(event)">

    <div id="previewModalBox" style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
                width:92vw; max-width:1100px; height:90vh;
                background:var(--bg-secondary); border-radius:16px; overflow:hidden;
                display:flex; flex-direction:column;
                box-shadow:0 32px 80px rgba(0,0,0,.45);">

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:14px 20px; background:#1e3a5f; color:#fff; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:20px;">📄</span>
                <div>
                    <div id="previewTitle" style="font-size:14px; font-weight:600;">Preview Dokumen</div>
                    <div id="previewSubtitle" style="font-size:11px; color:#93c5fd; margin-top:1px;"></div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                {{-- Tombol Edit (hanya tampil untuk admin_aspirasi di tahap 2, saat preview Word) --}}
                @if(Auth::user()->role === 'admin_aspirasi' && $surat->tahap_sekarang === 2)
                    <button id="btnToggleEdit" onclick="toggleEditMode()" style="display:none; padding:7px 16px; background:rgba(251,191,36,.2);
                                   border:1px solid rgba(251,191,36,.5); border-radius:8px;
                                   color:#fbbf24; font-size:12px; font-weight:600; cursor:pointer;
                                   transition:all .2s; gap:6px; align-items:center;"
                        onmouseover="this.style.background='rgba(251,191,36,.35)'"
                        onmouseout="this.style.background='rgba(251,191,36,.2)'">
                        <span id="btnToggleEditIcon">✏️</span>
                        <span id="btnToggleEditLabel">Edit Dokumen</span>
                    </button>
                    <button id="btnSaveEdit" onclick="saveEdit()" style="display:none; padding:7px 16px; background:rgba(16,185,129,.25);
                                   border:1px solid rgba(16,185,129,.5); border-radius:8px;
                                   color:#10b981; font-size:12px; font-weight:600; cursor:pointer;
                                   transition:all .2s;" onmouseover="this.style.background='rgba(16,185,129,.45)'"
                        onmouseout="this.style.background='rgba(16,185,129,.25)'">
                        💾 Simpan Perubahan
                    </button>
                @endif

                <a id="previewDownloadBtn" href="#" style="padding:7px 16px; background:rgba(255,255,255,.15);
                          border:1px solid rgba(255,255,255,.3); border-radius:8px;
                          color:#fff; font-size:12px; font-weight:500; text-decoration:none;
                          transition:background .2s;" onmouseover="this.style.background='rgba(255,255,255,.25)'"
                    onmouseout="this.style.background='rgba(255,255,255,.15)'">
                    ⬇ Download
                </a>
                <button onclick="closePreview()" style="background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.3);
                               border-radius:8px; color:#fff; padding:7px 12px; cursor:pointer;
                               font-size:14px; font-weight:600; transition:background .2s;"
                    onmouseover="this.style.background='rgba(255,255,255,.25)'"
                    onmouseout="this.style.background='rgba(255,255,255,.15)'">✕</button>
            </div>
        </div>


        {{-- Body --}}
        <div id="previewBody" style="flex:1; overflow:hidden; position:relative; background:var(--bg-tertiary);">

            {{-- Loading indicator --}}
            <div id="previewLoader" style="position:absolute; inset:0; display:flex; flex-direction:column;
                        align-items:center; justify-content:center; gap:16px; z-index:2;">
                <div style="width:48px; height:48px; border:4px solid #e2e8f0;
                            border-top-color:#1e3a5f; border-radius:50%;
                            animation:spin .8s linear infinite;"></div>
                <div style="font-size:13px; color:#64748b;">Memuat dokumen…</div>
            </div>

            {{-- Iframe untuk PDF --}}
            <iframe id="previewPdfFrame" src="about:blank"
                style="display:none; width:100%; height:100%; border:none;"></iframe>

            {{-- Container untuk Word HTML (read-only) --}}
            <div id="previewWordHtml" style="display:none; width:100%; height:100%; overflow:auto; padding:24px;
                        background:var(--bg-tertiary);">
                <div id="wordDocContent" style="max-width:850px; margin:0 auto; background:var(--bg-secondary);
                            padding:40px; border-radius:4px; box-shadow:0 2px 8px rgba(0,0,0,0.1);
                            font-family:'Calibri','Arial',sans-serif; font-size:11pt; line-height:1.5;
                            color:var(--text-primary); outline:none; transition:border .2s;">
                </div>
            </div>

            {{-- Container untuk Image --}}
            <div id="previewImageContainer" style="display:none; width:100%; height:100%; overflow:auto;
                        display:flex; align-items:center; justify-content:center; background:#1e293b;">
                <img id="previewImage" style="max-width:95%; max-height:95%; object-fit:contain;
                            border-radius:8px; box-shadow:0 8px 32px rgba(0,0,0,.3);">
            </div>

            {{-- Pesan tidak bisa preview --}}
            <div id="previewNoSupport" style="display:none; position:absolute; inset:0; align-items:center;
                        justify-content:center; flex-direction:column; gap:16px; font-size:14px; color:#64748b;">
                <div style="font-size:48px;">📁</div>
                <div id="previewNoSupportMsg">Format file tidak dapat di-preview langsung.</div>
                <a id="previewFallbackDownload" href="#" class="btn btn-primary">⬇ Download File</a>
            </div>
        </div>
    </div>
</div>

<script>
    // ─── state ────────────────────────────────────────────────────────────────────
    let currentDownloadUrl = '';
    let currentTipe = '';       // 'word' | 'lampiran'

    // ─── helpers ──────────────────────────────────────────────────────────────────
    function el(id) { return document.getElementById(id); }

    function showOnly(which) {
        ['previewPdfFrame', 'previewWordHtml', 'previewImageContainer', 'previewNoSupport']
            .forEach(id => {
                const e = el(id);
                if (id === which) {
                    e.style.display = (id === 'previewImageContainer' || id === 'previewNoSupport') ? 'flex' : 'block';
                } else {
                    e.style.display = 'none';
                }
            });
    }

    // ─── openPreview ──────────────────────────────────────────────────────────────
    function openPreview(tipe, previewUrl, title, ext) {
        currentTipe = tipe;

        // reset visuals
        el('previewLoader').style.display = 'flex';
        el('previewPdfFrame').src = 'about:blank';
        el('wordDocContent').innerHTML = '';
        el('previewImage').src = '';
        showOnly(null);
        el('previewLoader').style.display = 'flex';

        // header buttons
        el('previewDownloadBtn').href = previewUrl.replace('/preview/', '/download/');
        el('previewTitle').textContent = title;
        el('previewSubtitle').textContent = '.' + ext.toUpperCase() + ' — klik ✕ untuk tutup';

        // set contenteditable off
        el('wordDocContent').contentEditable = 'false';
        el('wordDocContent').style.border = 'none';

        el('previewModal').style.display = 'block';
        document.body.style.overflow = 'hidden';

        // fetch content
        fetch(previewUrl.replace('/preview/', '/preview-content/'))
            .then(res => res.json())
            .then(data => {
                el('previewLoader').style.display = 'none';
                if (data.type === 'html') {
                    el('wordDocContent').innerHTML = data.content;
                    showOnly('previewWordHtml');
                } else if (data.type === 'pdf') {
                    el('previewPdfFrame').src = data.url;
                    showOnly('previewPdfFrame');
                } else if (data.type === 'image') {
                    el('previewImage').src = data.url;
                    showOnly('previewImageContainer');
                } else {
                    el('previewNoSupportMsg').textContent = data.error || 'Format tidak didukung.';
                    showOnly('previewNoSupport');
                }
            })
            .catch(() => {
                el('previewLoader').style.display = 'none';
                el('previewNoSupportMsg').textContent = 'Gagal memuat preview.';
                showOnly('previewNoSupport');
            });
    }

    // ─── closePreview ─────────────────────────────────────────────────────────────
    function closePreview() {
        el('previewModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function handleOverlayClick(e) {
        if (e.target === el('previewModal')) closePreview();
    }

    // ─── Toast helper ─────────────────────────────────────────────────────────────
    function showToast(message, type) {
        const colors = { success: '#10b981', danger: '#ef4444', info: '#3b82f6' };
        const toast = document.createElement('div');
        toast.style.cssText = `
        position:fixed; bottom:24px; right:24px; z-index:99999;
        background:${colors[type] || colors.info}; color:#fff;
        padding:12px 20px; border-radius:10px; font-size:13px; font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,.3); animation:fadeIn .3s ease;
        max-width:360px;
    `;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }
</script>