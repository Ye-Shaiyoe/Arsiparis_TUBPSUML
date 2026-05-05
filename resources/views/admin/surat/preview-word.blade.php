<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Dokumen - {{ $surat->judul }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Lora:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- JSZip and docx-preview -->
    <script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
    <script src="https://unpkg.com/docx-preview/dist/docx-preview.js"></script>
    
    <style>
        :root {
            --bg-color: #1a1c1e;
            --paper-shadow: 0 10px 25px rgba(0,0,0,0.3);
            --primary-color: #2563eb;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: #e2e8f0;
            line-height: 1.5;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header-toolbar {
            background: #2d2f31;
            border-bottom: 1px solid #3f4143;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 100;
            flex-shrink: 0;
        }

        .doc-info { display: flex; align-items: center; gap: 1rem; }
        .doc-icon {
            width: 36px; height: 36px; background: #2563eb; color: white;
            border-radius: 6px; display: flex; align-items: center; justify-content: center;
        }
        .doc-title { font-weight: 600; font-size: 0.95rem; color: #f8fafc; }

        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1.25rem; border-radius: 6px;
            font-weight: 500; font-size: 0.875rem; cursor: pointer;
            transition: all 0.2s; border: 1px solid #4f5153;
            text-decoration: none; background: #3f4143; color: #f8fafc;
        }
        .btn:hover { background: #4f5153; }

        .main-viewport {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #1a1c1e;
        }

        #docx-container { width: 100%; display: flex; justify-content: center; }
        
        /* Docx Preview Styling */
        .docx-wrapper { background: transparent !important; padding: 0 !important; }
        .docx { 
            box-shadow: var(--paper-shadow) !important; 
            margin-bottom: 2rem !important;
            background: white !important;
            color: black !important;
        }

        #loading-overlay {
            position: absolute; inset: 0; background: var(--bg-color);
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; z-index: 50;
        }

        .spinner {
            width: 40px; height: 40px; border: 4px solid rgba(255,255,255,0.1);
            border-top-color: var(--primary-color); border-radius: 50%;
            animation: spin 1s linear infinite; margin-bottom: 1rem;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <header class="header-toolbar">
        <div class="doc-info">
            <div class="doc-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div>
                <div class="doc-title">{{ $fileName ?? 'Dokumen Surat' }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8;">High-Fidelity Client-Side Preview</div>
            </div>
        </div>

        <div class="actions">
            <button onclick="window.close()" class="btn">Tutup</button>
        </div>
    </header>

    <div class="main-viewport">
        <div id="loading-overlay">
            <div class="spinner"></div>
            <div>Memuat dokumen...</div>
        </div>

        <div id="docx-container"></div>
    </div>

    <script>
        const docxContainer = document.getElementById('docx-container');
        const loadingOverlay = document.getElementById('loading-overlay');

        // URL untuk ambil file mentah (raw)
        const rawUrl = "{{ route('admin.surat.preview', [$surat, $tipe]) }}?raw=1&v=" + new Date().getTime();

        function loadDocx() {
            fetch(rawUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengambil file (Status: ' + response.status + ')');
                    return response.arrayBuffer();
                })
                .then(buffer => {
                    // Render DOCX menggunakan library client-side
                    docx.renderAsync(buffer, docxContainer, null, {
                        className: "docx",
                        inWrapper: true,
                        ignoreLastRenderedPageBreak: false,
                        experimental: true,
                        renderHeaders: true,
                        renderFooters: true,
                        renderFonts: true,
                        breakPages: true,
                    }).then(() => {
                        loadingOverlay.style.display = 'none';
                    });
                })
                .catch(error => {
                    console.error('Preview error:', error);
                    loadingOverlay.innerHTML = `
                        <div style="text-align:center; color:#ef4444; padding:20px;">
                            <div style="font-size:48px; margin-bottom:10px;">❌</div>
                            <div style="font-weight:600;">Gagal memuat dokumen</div>
                            <div style="font-size:12px; margin-top:5px; opacity:0.8;">${error.message}</div>
                            <button onclick="location.reload()" style="margin-top:15px; padding:8px 16px; background:#3b82f6; color:white; border:none; border-radius:6px; cursor:pointer;">Coba Lagi</button>
                        </div>
                    `;
                });
        }

        // Jalankan pemuatan
        loadDocx();
    </script>
</body>
</html>
