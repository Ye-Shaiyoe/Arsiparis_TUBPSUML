<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Surat</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .toolbar { margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        #editor { padding: 20px; border: 1px solid #ccc; min-height: 500px; background: white; }
        #editor[contenteditable="true"] { border: 2px solid #3b82f6; }
        .btn { padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-success { background: #10b981; color: white; }
    </style>
</head>
<body>
    @if(Auth::user()->isAdmin() && $surat->tahap_sekarang == 2)
        <div class="toolbar">
            <button id="btn-edit" class="btn btn-warning">Edit Dokumen</button>
            <button id="btn-save" class="btn btn-success" style="display:none;">Simpan Perubahan</button>
        </div>
        <div id="editor" contenteditable="false">
            {!! $htmlContent !!}
        </div>

        <script>
            document.getElementById('btn-edit').addEventListener('click', function() {
                document.getElementById('editor').contentEditable = "true";
                this.style.display = 'none';
                document.getElementById('btn-save').style.display = 'inline-block';
            });

            document.getElementById('btn-save').addEventListener('click', function() {
                let content = document.getElementById('editor').innerHTML;
                fetch("{{ route('admin.surat.updateContent', [$surat, $tipe]) }}", {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({content: content})
                }).then(response => {
                    if (response.ok) {
                        alert('Dokumen berhasil diperbarui');
                        location.reload();
                    } else {
                        alert('Gagal menyimpan dokumen');
                    }
                });
            });
        </script>
    @else
        <div>{!! $htmlContent !!}</div>
    @endif
</body>
</html>
