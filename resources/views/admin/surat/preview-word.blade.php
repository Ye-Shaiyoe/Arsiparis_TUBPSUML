<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview & Edit Dokumen - {{ $surat->judul }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Lora:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <!-- JSZip and docx-preview for exact rendering -->
    <script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
    <script src="https://unpkg.com/docx-preview/dist/docx-preview.js"></script>
    <style>
        :root {
            --bg-color: #1a1c1e;
            --paper-shadow: 0 10px 25px rgba(0,0,0,0.3);
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --warning-color: #f59e0b;
            --warning-hover: #d97706;
            --success-color: #10b981;
            --success-hover: #059669;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: #e2e8f0;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header-toolbar {
            position: relative;
            z-index: 100;
            background: #2d2f31;
            border-bottom: 1px solid #3f4143;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .doc-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .doc-icon {
            width: 36px;
            height: 36px;
            background: #2563eb;
            color: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doc-title {
            font-weight: 600;
            font-size: 0.95rem;
            color: #f8fafc;
        }

        .actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-outline {
            background: #3f4143;
            border-color: #4f5153;
            color: #f8fafc;
        }

        .btn-outline:hover {
            background: #4f5153;
        }

        .btn-warning {
            background-color: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background-color: var(--warning-hover);
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: var(--success-hover);
        }

        .main-viewport {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
            display: flex;
            justify-content: center;
            background-color: #1a1c1e;
            position: relative;
        }

        /* Container for docx-preview */
        #docx-container {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .docx-wrapper {
            background: transparent !important;
            padding: 0 !important;
        }

        .docx {
            box-shadow: var(--paper-shadow) !important;
            margin-bottom: 2rem !important;
        }

        .docx img {
            max-width: none !important;
        }

        /* Container for HTML editor (hidden by default) */
        #editor-container {
            display: none;
            width: 210mm;
            min-height: 297mm;
            background: white;
            padding: 25mm 20mm;
            box-shadow: var(--paper-shadow);
            color: #000;
            margin-bottom: 2rem;
            outline: none;
        }

        .paper-content {
            font-family: 'Lora', serif;
            font-size: 11pt;
            line-height: 1.5;
        }

        .paper-content table {
            width: 100% !important;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .paper-content table td, 
        .paper-content table th {
            border: 1px solid #000;
            padding: 5px 8px;
        }

        /* Loader */
        #loading-overlay {
            position: absolute;
            inset: 0;
            background: var(--bg-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255,255,255,0.1);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Edit Notice */
        .edit-notice {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: #2563eb;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            display: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translate(-50%, 20px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }
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
                <div style="font-size: 0.75rem; color: #94a3b8;" id="view-mode-label">Mode: High-Fidelity Preview (Word View)</div>
            </div>
        </div>

        <div class="actions">
            @if(Auth::user()->isAdmin() && $surat->tahap_sekarang == 2)
                <button id="btn-edit" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit Dokumen
                </button>
                <button id="btn-save" class="btn btn-success" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Perubahan
                </button>
            @endif
            <button onclick="window.close()" class="btn btn-outline">Tutup</button>
        </div>
    </header>

    <div class="main-viewport">
        <div id="loading-overlay">
            <div class="spinner"></div>
            <div>Menyiapkan dokumen...</div>
        </div>

        <!-- Exact Word Preview Container -->
        <div id="docx-container"></div>

        <!-- HTML Editor Container (Hidden by default) -->
        <div id="editor-container" contenteditable="false" class="paper-content">
            {!! $htmlContent !!}
        </div>
    </div>

    <div id="edit-notice" class="edit-notice">
        <b>Mode Edit Aktif</b>: Tampilan disesuaikan untuk pengeditan teks. Klik Simpan untuk memperbarui file Word.
    </div>

    <script>
        const docxContainer = document.getElementById('docx-container');
        const editorContainer = document.getElementById('editor-container');
        const loadingOverlay = document.getElementById('loading-overlay');
        const btnEdit = document.getElementById('btn-edit');
        const btnSave = document.getElementById('btn-save');
        const editNotice = document.getElementById('edit-notice');
        const modeLabel = document.getElementById('view-mode-label');

        // 1. Render high-fidelity preview using docx-preview
        const rawUrl = "{{ route('admin.surat.preview', [$surat, $tipe]) }}?raw=1";
        
        fetch(rawUrl)
            .then(response => {
                if (!response.ok) throw new Error('Gagal mengambil file');
                return response.arrayBuffer();
            })
            .then(buffer => {
                docx.renderAsync(buffer, docxContainer, null, {
                    className: "docx",
                    inWrapper: true,
                    ignoreLastRenderedPageBreak: false,
                    experimental: true,
                    trimXmlDeclaration: true,
                    useMinifiedXml: false,
                    renderHeaders: true,
                    renderFooters: true,
                    renderFonts: true,
                    breakPages: true,
                }).then(() => {
                    loadingOverlay.style.display = 'none';
                });
            })
            .catch(error => {
                console.error('Docx preview error:', error);
                loadingOverlay.innerHTML = '<div style="color:#ef4444">Gagal merender dokumen. Gunakan mode edit untuk melihat konten.</div>';
                setTimeout(() => { loadingOverlay.style.display = 'none'; }, 2000);
            });

        // 2. Handle Edit mode switch
        if (btnEdit) {
            btnEdit.addEventListener('click', function() {
                // Hide exact preview, show HTML editor
                docxContainer.style.display = 'none';
                editorContainer.style.display = 'block';
                editorContainer.contentEditable = "true";
                editorContainer.focus();
                
                // Update UI
                this.style.display = 'none';
                btnSave.style.display = 'inline-flex';
                editNotice.style.display = 'block';
                modeLabel.textContent = 'Mode: Edit Teks (HTML View)';
                modeLabel.style.color = '#fbbf24';
            });
        }

        // 3. Handle Save
        if (btnSave) {
            btnSave.addEventListener('click', function() {
                let content = editorContainer.innerHTML;
                
                btnSave.disabled = true;
                btnSave.innerHTML = '<svg class="spinner" style="width:16px;height:16px;margin:0;border-width:2px;" viewBox="0 0 24 24"></svg> Menyimpan...';

                fetch("{{ route('admin.surat.updateContent', [$surat, $tipe]) }}", {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({content: content})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Dokumen berhasil diperbarui');
                        location.reload();
                    } else {
                        alert('Gagal: ' + (data.error || 'Terjadi kesalahan'));
                        btnSave.disabled = false;
                        btnSave.innerHTML = 'Simpan Perubahan';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menyimpan dokumen');
                    btnSave.disabled = false;
                    btnSave.innerHTML = 'Simpan Perubahan';
                });
            });
        }

        // Keyboard shortcuts
        editorContainer.addEventListener('keydown', function(e) {
            if (e.ctrlKey) {
                if (e.key === 'b') { document.execCommand('bold', false, null); e.preventDefault(); }
                if (e.key === 'i') { document.execCommand('italic', false, null); e.preventDefault(); }
                if (e.key === 'u') { document.execCommand('underline', false, null); e.preventDefault(); }
            }
        });
    </script>
</body>
</html>
