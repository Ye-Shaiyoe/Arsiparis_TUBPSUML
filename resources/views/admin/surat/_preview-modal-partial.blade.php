<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Word Content Styling for Modal */
    .word-preview-content {
        font-family: 'Lora', 'Georgia', serif !important;
        color: #000 !important;
        line-height: 1.5 !important;
    }
    .word-preview-content table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-bottom: 1rem !important;
    }
    .word-preview-content table td, 
    .word-preview-content table th {
        border: 1px solid #000 !important;
        padding: 5px 8px !important;
    }
    
    /* Docx Preview Customization */
    .docx-wrapper {
        background: transparent !important;
        padding: 20px 0 !important;
    }
    .docx {
        box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<!-- JSZip and docx-preview for exact rendering -->
<script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
<script src="https://unpkg.com/docx-preview/dist/docx-preview.js"></script>

{{-- ========== MODAL PREVIEW FILE ========== --}}
<div id="previewModal" style="display:none; position:fixed; inset:0; z-index:9999;
            background:rgba(10,15,30,.85); backdrop-filter:blur(8px);
            animation:fadeIn .2s ease;" onclick="handleOverlayClick(event)">

    <div id="previewModalBox" style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
                width:95vw; max-width:1200px; height:92vh;
                background:#1a1c1e; border-radius:12px; overflow:hidden;
                display:flex; flex-direction:column;
                box-shadow:0 32px 80px rgba(0,0,0,.5);">

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:12px 20px; background:#2d2f31; color:#fff; flex-shrink:0; border-bottom:1px solid #3f4143;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:32px; height:32px; background:#2563eb; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:16px;">📄</div>
                <div>
                    <div id="previewTitle" style="font-size:14px; font-weight:600;">Preview Dokumen</div>
                    <div id="previewSubtitle" style="font-size:11px; color:#94a3b8; margin-top:1px;"></div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <a id="previewDownloadBtn" href="#" style="padding:7px 16px; background:rgba(255,255,255,.1);
                          border:1px solid rgba(255,255,255,.2); border-radius:6px;
                          color:#fff; font-size:12px; font-weight:500; text-decoration:none;
                          transition:all .2s;" onmouseover="this.style.background='rgba(255,255,255,.2)'"
                    onmouseout="this.style.background='rgba(255,255,255,.1)'">
                    ⬇ Download
                </a>
                <button onclick="closePreview()" style="background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2);
                                border-radius:6px; color:#fff; padding:7px 12px; cursor:pointer;
                                font-size:14px; font-weight:600; transition:all .2s;"
                    onmouseover="this.style.background='rgba(255,255,255,.2)'"
                    onmouseout="this.style.background='rgba(255,255,255,.1)'">✕</button>
            </div>
        </div>


        {{-- Body --}}
        <div id="previewBody" style="flex:1; overflow:hidden; position:relative; background:#1a1c1e;">

            {{-- Loading indicator --}}
            <div id="previewLoader" style="position:absolute; inset:0; display:flex; flex-direction:column;
                        align-items:center; justify-content:center; gap:16px; z-index:10; background:#1a1c1e;">
                <div style="width:40px; height:40px; border:3px solid rgba(255,255,255,0.1);
                            border-top-color:#2563eb; border-radius:50%;
                            animation:spin .8s linear infinite;"></div>
                <div style="font-size:13px; color:#94a3b8;">Menyiapkan preview...</div>
            </div>

            {{-- Iframe untuk PDF --}}
            <iframe id="previewPdfFrame" src="about:blank"
                style="display:none; width:100%; height:100%; border:none;"></iframe>

            {{-- Container untuk Word (High Fidelity) --}}
            <div id="previewWordHtml" style="display:none; width:100%; height:100%; overflow:auto; padding:0;
                        background:#1a1c1e;">
                <div id="wordDocContent" style="display:flex; justify-content:center;">
                    <!-- docx-preview will render here -->
                </div>
            </div>

            {{-- Container untuk Image --}}
            <div id="previewImageContainer" style="display:none; width:100%; height:100%; overflow:auto;
                        display:flex; align-items:center; justify-content:center; background:#000;">
                <img id="previewImage" style="max-width:95%; max-height:95%; object-fit:contain;
                            border-radius:4px; box-shadow:0 8px 32px rgba(0,0,0,.5);">
            </div>

            {{-- Pesan tidak bisa preview --}}
            <div id="previewNoSupport" style="display:none; position:absolute; inset:0; align-items:center;
                        justify-content:center; flex-direction:column; gap:16px; font-size:14px; color:#94a3b8;">
                <div style="font-size:48px;">📁</div>
                <div id="previewNoSupportMsg">Format file tidak dapat di-preview langsung.</div>
                <a id="previewFallbackDownload" href="#" class="btn btn-primary" style="background:#2563eb; color:#fff; padding:10px 20px; border-radius:6px; text-decoration:none;">⬇ Download File</a>
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

        // header buttons
        el('previewDownloadBtn').href = previewUrl.replace('/preview/', '/download/');
        el('previewTitle').textContent = title;
        el('previewSubtitle').textContent = '.' + ext.toUpperCase() + ' — High Fidelity Preview';

        el('previewModal').style.display = 'block';
        document.body.style.overflow = 'hidden';

        const isWord = ['docx', 'doc'].includes(ext.toLowerCase());

        if (isWord) {
            // Use docx-preview for high fidelity
            const rawUrl = previewUrl + '?raw=1&v=' + new Date().getTime();
            fetch(rawUrl)
                .then(res => res.arrayBuffer())
                .then(buffer => {
                    docx.renderAsync(buffer, el('wordDocContent'), null, {
                        inWrapper: true,
                        ignoreLastRenderedPageBreak: false,
                        experimental: true,
                        renderHeaders: true,
                        renderFooters: true,
                        renderFonts: true,
                        breakPages: true,
                    }).then(() => {
                        el('previewLoader').style.display = 'none';
                        showOnly('previewWordHtml');
                    });
                })
                .catch(err => {
                    console.error(err);
                    el('previewLoader').style.display = 'none';
                    el('previewNoSupportMsg').textContent = 'Gagal merender dokumen Word.';
                    showOnly('previewNoSupport');
                });
        } else {
            // fetch metadata for other types
            fetch(previewUrl.replace('/preview/', '/preview-content/') + '?v=' + new Date().getTime())
                .then(res => res.json())
                .then(data => {
                    el('previewLoader').style.display = 'none';
                    if (data.type === 'pdf') {
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
