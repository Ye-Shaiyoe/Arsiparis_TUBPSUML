{{-- Partial untuk mengelola permintaan hapus surat di admin --}}
@php
    $deleteRequests = \App\Models\SuratDeleteRequest::where('surat_id', $surat->id)->latest()->get();
@endphp

@if($deleteRequests->isNotEmpty())
<div class="card mt-3">
    <div class="card-header" style="background:#fef3c7;border-bottom:1px solid #fcd34d;">
        <h6 class="mb-0" style="color:#92400e;font-size:14px;font-weight:600;">
            <i class="bi bi-exclamation-triangle"></i> Permintaan Hapus Surat
        </h6>
    </div>
    <div class="card-body">
        @foreach($deleteRequests as $request)
        <div class="border rounded p-3 mb-3" style="background:{{ $request->status === 'pending' ? '#fffbeb' : ($request->status === 'disetujui' ? '#f0fdf4' : '#fef2f2') }};">
            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <strong style="font-size:13px;color:#111827;">{{ $request->user->name }}</strong>
                        @if($request->status === 'pending')
                            <span class="badge rounded-pill" style="background:#fef3c7;color:#92400e;font-size:10px;">⏳ Pending</span>
                        @elseif($request->status === 'disetujui')
                            <span class="badge rounded-pill" style="background:#dcfce7;color:#15803d;font-size:10px;">✓ Disetujui</span>
                        @else
                            <span class="badge rounded-pill" style="background:#fee2e2;color:#b91c1c;font-size:10px;">✗ Ditolak</span>
                        @endif
                    </div>
                    <small class="text-muted">{{ $request->created_at->format('d M Y, H:i') }}</small>
                </div>
            </div>

            <div class="mb-3" style="font-size:13px;">
                <div style="color:#6b7280;font-size:11px;font-weight:600;margin-bottom:3px;">ALASAN PENGAJUAN</div>
                <div style="color:#374151;background:#fff;padding:8px;border-radius:6px;border-left:3px solid #f59e0b;">
                    {{ $request->alasan }}
                </div>
            </div>

            @if($request->admin_catatan)
            <div class="mb-3" style="font-size:13px;">
                <div style="color:#6b7280;font-size:11px;font-weight:600;margin-bottom:3px;">CATATAN ADMIN</div>
                <div style="color:#374151;background:#fff;padding:8px;border-radius:6px;border-left:3px solid #3b82f6;">
                    {{ $request->admin_catatan }}
                </div>
                @if($request->admin)
                <small class="text-muted mt-1 d-block">
                    <i class="bi bi-person-check"></i> {{ $request->admin->name }} · {{ $request->admin_approved_at?->format('d M Y, H:i') }}
                </small>
                @endif
            </div>
            @endif

            @if($request->isPending())
            {{-- Form Approve/Reject untuk pending request --}}
            <div class="d-flex gap-2 flex-wrap">
                {{-- Approve Button --}}
                <button type="button" class="btn btn-sm btn-success" 
                        style="font-size:12px;border-radius:6px;"
                        data-bs-toggle="modal" 
                        data-bs-target="#approveModal{{ $request->id }}">
                    <i class="bi bi-check-circle"></i> Setujui Hapus
                </button>

                {{-- Reject Button --}}
                <button type="button" class="btn btn-sm btn-danger" 
                        style="font-size:12px;border-radius:6px;"
                        data-bs-toggle="modal" 
                        data-bs-target="#rejectModal{{ $request->id }}">
                    <i class="bi bi-x-circle"></i> Tolak
                </button>
            </div>

            @push('modals')
            {{-- Modal Approve --}}
            <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('admin.surat.approveDelete', $request) }}" method="POST">
                            @csrf
                            <div class="modal-header" style="background:#dcfce7;border-bottom:1px solid #86efac;">
                                <h5 class="modal-title" style="color:#15803d;">
                                    <i class="bi bi-check-circle"></i> Setujui Penghapusan
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p style="font-size:14px;">Apakah Anda yakin ingin <strong>menyetujui</strong> penghapusan surat ini?</p>
                                <div class="mb-3">
                                    <label class="form-label" style="font-size:13px;">
                                        Catatan (Opsional)
                                    </label>
                                    <textarea name="admin_catatan" class="form-control" rows="2" 
                                              placeholder="Tambahkan catatan..." 
                                              style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);"></textarea>
                                </div>
                                <div class="alert alert-warning" style="font-size:12px;">
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    Surat akan <strong>langsung dihapus</strong> setelah disetujui.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:13px;">Batal</button>
                                <button type="submit" class="btn btn-success" style="font-size:13px;">
                                    <i class="bi bi-check-circle"></i> Ya, Hapus Surat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Reject --}}
            <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('admin.surat.rejectDelete', $request) }}" method="POST">
                            @csrf
                            <div class="modal-header" style="background:#fee2e2;border-bottom:1px solid #fca5a5;">
                                <h5 class="modal-title" style="color:#b91c1c;">
                                    <i class="bi bi-x-circle"></i> Tolak Penghapusan
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p style="font-size:14px;">Berikan alasan penolakan:</p>
                                <div class="mb-3">
                                    <label class="form-label" style="font-size:13px;">
                                        Alasan Penolakan <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="admin_catatan" class="form-control" rows="3" 
                                              placeholder="Jelaskan alasan penolakan..." 
                                              required 
                                              style="font-size:13px;background:var(--bg-secondary);color:var(--text-primary);border-color:var(--border-color);"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size:13px;">Batal</button>
                                <button type="submit" class="btn btn-danger" style="font-size:13px;">
                                    <i class="bi bi-x-circle"></i> Tolak Permintaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endpush
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
