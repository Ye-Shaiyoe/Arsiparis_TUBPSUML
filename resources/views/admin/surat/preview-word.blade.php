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
            --bg-color: #020617; /* slate-950 */
            --bg-toolbar: rgba(15, 23, 42, 0.85); /* slate-900 with opacity */
            --border-color: rgba(255, 255, 255, 0.08);
            --paper-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            --primary-color: #4361ee; /* Modern Electric Blue */
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            line-height: 1.5;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header-toolbar {
            background: var(--bg-toolbar);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 100;
            flex-shrink: 0;
        }

        .doc-info { display: flex; align-items: center; gap: 1rem; }
        .doc-icon {
            width: 38px; height: 38px; background: var(--primary-color); color: white;
            border-radius: 8px; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 15px rgba(67, 97, 238, 0.3);
        }
        .doc-title { font-weight: 600; font-size: 0.95rem; color: var(--text-primary); }

        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1.25rem; border-radius: 8px;
            font-weight: 600; font-size: 0.875rem; cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-color);
            text-decoration: none; 
            background: rgba(255, 255, 255, 0.05); 
            color: var(--text-primary);
        }
        .btn:hover { 
            background: rgba(255, 255, 255, 0.1); 
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }
        .btn:active {
            transform: translateY(0);
        }

        .main-viewport {
            flex: 1;
            overflow-y: auto;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, rgba(67, 97, 238, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 0%, rgba(30, 41, 59, 0.1) 0px, transparent 50%);
        }

        #docx-container { width: 100%; display: flex; justify-content: center; }
        
        /* Docx Preview Styling Override */
        .docx-wrapper { background: transparent !important; padding: 0 !important; }
        .docx { 
            box-shadow: var(--paper-shadow) !important; 
            margin-bottom: 3rem !important;
            background: white !important;
            color: black !important;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #loading-overlay {
            position: absolute; inset: 0; background: var(--bg-color);
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; z-index: 50;
        }

        .spinner {
            width: 44px; height: 44px; border: 4px solid rgba(255,255,255,0.05);
            border-top-color: var(--primary-color); border-radius: 50%;
            animation: spin 1s cubic-bezier(0.55, 0.15, 0.45, 0.85) infinite; 
            margin-bottom: 1.25rem;
            box-shadow: 0 0 20px rgba(67, 97, 238, 0.1);
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
                <div style="font-size: 0.75rem; color: var(--text-secondary);">High-Fidelity Client-Side Preview</div>
            </div>
        </div>

        <div class="actions">
            <button onclick="window.close()" class="btn">Tutup</button>
        </div>
    </header>

    <div class="main-viewport">
        <div id="loading-overlay">
            <div class="spinner"></div>
            <div style="font-weight: 500; font-size: 0.95rem; color: var(--text-secondary); letter-spacing: 0.5px;">Memuat dokumen...</div>
        </div>

        <div id="docx-container"></div>
    </div>

    <script>
        const docxContainer = document.getElementById('docx-container');
        const loadingOverlay = document.getElementById('loading-overlay');

        // Menggunakan relative URL (argumen ketiga = false) agar terhindar dari mixed content (http/https) di server produksi
        const rawUrl = "{{ route('admin.surat.preview', [$surat, $tipe], false) }}?raw=1&v=" + new Date().getTime();

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
                        <div style="text-align:center; color:#ef4444; padding:32px; background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 12px; max-width: 400px;">
                            <div style="font-size:48px; margin-bottom:12px;">❌</div>
                            <div style="font-weight:700; font-size: 1.1rem; margin-bottom: 6px; color: #fca5a5;">Gagal memuat dokumen</div>
                            <div style="font-size:12px; opacity:0.8; margin-bottom: 20px; line-height: 1.5;">${error.message}</div>
                            <button onclick="location.reload()" class="btn" style="background:#ef4444; border-color:#ef4444; color:white; font-weight:700; width:100%; justify-content:center;">Coba Lagi</button>
                        </div>
                    `;
                });
        }

        // Jalankan pemuatan
        loadDocx();
    </script>
</body>
</html>
