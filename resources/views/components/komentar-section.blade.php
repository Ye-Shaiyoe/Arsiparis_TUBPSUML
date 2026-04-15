@props(['surat'])

{{-- ==========================================
   SISTEM KOMENTAR CHAT-LIKE UNTUK SURAT
   Menggunakan Alpine.js untuk interaktivitas
   ========================================== --}}

<div class="card card-custom mt-3" x-data="komentarSystem()" x-init="init({{ $surat->id }})">
    <div class="card-body p-4">
        
        {{-- HEADER --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0" style="color:#1e3a5f;">
                <i class="bi bi-chat-dots me-2"></i>Diskusi & Komentar
            </h6>
            <span class="badge rounded-pill" style="background:#ede9fe;color:#6d28d9;font-size:11px;">
                <span x-text="totalKomentar"></span> komentar
            </span>
        </div>

        {{-- CHAT CONTAINER --}}
        <div class="komentar-container" style="max-height:500px; overflow-y:auto; padding:16px; background:#f9fafb; border-radius:12px;">
            
            {{-- Loading state --}}
            <template x-if="loading">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted" style="font-size:13px;">Memuat komentar...</p>
                </div>
            </template>

            {{-- Empty state --}}
            <template x-if="!loading && komentars.length === 0">
                <div class="text-center py-4">
                    <i class="bi bi-chat-left-text" style="font-size:48px;color:#d1d5db;"></i>
                    <p class="mt-2 text-muted" style="font-size:13px;">Belum ada komentar. Mulai diskusi di bawah!</p>
                </div>
            </template>

            {{-- Komentar list --}}
            <template x-if="!loading && komentars.length > 0">
                <div class="komentar-list">
                    <template x-for="komentar in komentars" :key="komentar.id">
                        <div class="komentar-item mb-3">
                            {{-- Root komentar --}}
                            <div class="d-flex gap-2">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:36px; height:36px; background:#e0e7ff; color:#4338ca; font-weight:600; font-size:14px;">
                                        <span x-text="getInitial(komentar.user.name)"></span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="komentar-bubble p-2 px-3" 
                                         :class="isOwnComment(komentar) ? 'bg-primary text-white' : 'bg-white'"
                                         style="border-radius:12px; max-width:80%; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                                        
                                        {{-- User info --}}
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <strong style="font-size:12px;" 
                                                  :class="isOwnComment(komentar) ? 'text-white-50' : 'text-muted'
                                                  x-text="komentar.user.name"></strong>
                                            <span style="font-size:10px;" 
                                                  :class="isOwnComment(komentar) ? 'text-white-50' : 'text-muted'"
                                                  x-text="formatTime(komentar.created_at)"></span>
                                        </div>
                                        
                                        {{-- Isi komentar --}}
                                        <div style="font-size:13px; line-height:1.5;" x-text="komentar.isi"></div>
                                        
                                        {{-- Actions --}}
                                        <div class="d-flex gap-2 mt-2">
                                            <button type="button" 
                                                    class="btn btn-link p-0" 
                                                    style="font-size:11px; text-decoration:none;"
                                                    :class="isOwnComment(komentar) ? 'text-white-50' : 'text-muted'"
                                                    @click="showReplyInput = komentar.id"
                                                    :class="showReplyInput === komentar.id ? 'fw-bold' : ''">
                                                <i class="bi bi-reply me-1"></i>Balas
                                            </button>
                                            
                                            <template x-if="canDelete(komentar)">
                                                <button type="button" 
                                                        class="btn btn-link p-0" 
                                                        style="font-size:11px; color:#ef4444; text-decoration:none;"
                                                        @click="deleteKomentar(komentar.id)">
                                                    <i class="bi bi-trash me-1"></i>Hapus
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    {{-- Reply input --}}
                                    <template x-if="showReplyInput === komentar.id">
                                        <div class="mt-2 ms-3">
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control" 
                                                       style="font-size:12px; border-radius:8px 0 0 8px;"
                                                       placeholder="Tulis balasan..."
                                                       x-model="replyText"
                                                       @keyup.enter="submitReply(komentar.id)"
                                                       :disabled="replyLoading">
                                                <button class="btn btn-primary" 
                                                        type="button"
                                                        style="font-size:12px; border-radius:0 8px 8px 0;"
                                                        @click="submitReply(komentar.id)"
                                                        :disabled="replyLoading">
                                                    <template x-if="!replyLoading">
                                                        <i class="bi bi-send"></i>
                                                    </template>
                                                    <template x-if="replyLoading">
                                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                                    </template>
                                                </button>
                                                <button class="btn btn-secondary" 
                                                        type="button"
                                                        style="font-size:12px; border-radius:0;"
                                                        @click="showReplyInput = null; replyText = ''">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    {{-- Replies --}}
                                    <template x-if="komentar.replies && komentar.replies.length > 0">
                                        <div class="ms-4 mt-2">
                                            <template x-for="reply in komentar.replies" :key="reply.id">
                                                <div class="d-flex gap-2 mb-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                             style="width:28px; height:28px; background:#fef3c7; color:#d97706; font-weight:600; font-size:11px;">
                                                            <span x-text="getInitial(reply.user.name)"></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="p-2 px-3" 
                                                             :class="isOwnComment(reply) ? 'bg-primary-subtle' : 'bg-white'"
                                                             style="border-radius:8px; max-width:85%; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <strong style="font-size:11px;" 
                                                                      :class="isOwnComment(reply) ? 'text-primary' : 'text-dark'"
                                                                      x-text="reply.user.name"></strong>
                                                                <span style="font-size:10px; color:#9ca3af;"
                                                                      x-text="formatTime(reply.created_at)"></span>
                                                            </div>
                                                            <div style="font-size:12px; line-height:1.4;" x-text="reply.isi"></div>
                                                            
                                                            {{-- Reply actions --}}
                                                            <div class="d-flex gap-2 mt-1">
                                                                <template x-if="canDelete(reply)">
                                                                    <button type="button" 
                                                                            class="btn btn-link p-0" 
                                                                            style="font-size:10px; color:#ef4444; text-decoration:none;"
                                                                            @click="deleteKomentar(reply.id)">
                                                                        <i class="bi bi-trash me-1"></i>Hapus
                                                                    </button>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        {{-- INPUT FORM --}}
        <div class="mt-3">
            <div class="input-group">
                <input type="text" 
                       class="form-control" 
                       style="font-size:13px; border-radius:12px 0 0 12px; padding:10px 14px;"
                       placeholder="Tulis komentar..."
                       x-model="newComment"
                       @keyup.enter="submitKomentar()"
                       :disabled="loadingSubmit">
                <button class="btn btn-primary" 
                        type="button"
                        style="font-size:13px; border-radius:0 12px 12px 0; padding:10px 16px;"
                        @click="submitKomentar()"
                        :disabled="loadingSubmit">
                    <template x-if="!loadingSubmit">
                        <i class="bi bi-send me-1"></i>
                    </template>
                    <template x-if="loadingSubmit">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                    </template>
                    Kirim
                </button>
            </div>
            <small class="text-muted" style="font-size:11px;">
                <i class="bi bi-info-circle me-1"></i>Komentar akan terlihat oleh semua pihak yang terlibat dalam surat ini
            </small>
        </div>
    </div>
</div>

{{-- Alpine.js Component --}}
<script>
function komentarSystem() {
    return {
        suratId: null,
        komentars: [],
        newComment: '',
        replyText: '',
        showReplyInput: null,
        loading: true,
        loadingSubmit: false,
        replyLoading: false,

        init(suratId) {
            this.suratId = suratId;
            this.loadKomentar();
            
            // Auto-refresh setiap 5 detik
            setInterval(() => {
                this.loadKomentar();
            }, 5000);
        },

        async loadKomentar() {
            try {
                const response = await fetch(`/surat/${this.suratId}/komentar`);
                const data = await response.json();
                
                if (data.success) {
                    this.komentars = data.komentars;
                }
            } catch (error) {
                console.error('Error loading komentar:', error);
            } finally {
                this.loading = false;
            }
        },

        async submitKomentar() {
            if (!this.newComment.trim()) return;

            this.loadingSubmit = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                if (!csrfToken) {
                    console.error('CSRF token not found');
                    alert('CSRF token tidak ditemukan. Refresh halaman.');
                    this.loadingSubmit = false;
                    return;
                }

                const response = await fetch(`/surat/${this.suratId}/komentar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ isi: this.newComment })
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    console.error('Server error:', errorData);
                    throw new Error(errorData.message || `HTTP ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.newComment = '';
                    await this.loadKomentar();

                    // Scroll ke bawah
                    this.$nextTick(() => {
                        const container = document.querySelector('.komentar-container');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                } else {
                    throw new Error(data.message || 'Gagal menyimpan komentar');
                }
            } catch (error) {
                console.error('Error submitting komentar:', error);
                alert('Gagal mengirim komentar: ' + error.message);
            } finally {
                this.loadingSubmit = false;
            }
        },

        async submitReply(parentId) {
            if (!this.replyText.trim()) return;

            this.replyLoading = true;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const response = await fetch(`/surat/${this.suratId}/komentar/${parentId}/reply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ isi: this.replyText })
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `HTTP ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.replyText = '';
                    this.showReplyInput = null;
                    await this.loadKomentar();
                } else {
                    throw new Error(data.message || 'Gagal menyimpan balasan');
                }
            } catch (error) {
                console.error('Error submitting reply:', error);
                alert('Gagal mengirim balasan: ' + error.message);
            } finally {
                this.replyLoading = false;
            }
        },

        async deleteKomentar(komentarId) {
            if (!confirm('Hapus komentar ini?')) return;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const response = await fetch(`/surat/${this.suratId}/komentar/${komentarId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `HTTP ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    await this.loadKomentar();
                } else {
                    throw new Error(data.message || 'Gagal menghapus komentar');
                }
            } catch (error) {
                console.error('Error deleting komentar:', error);
                alert('Gagal menghapus komentar: ' + error.message);
            }
        },

        isOwnComment(komentar) {
            // Asumsi: user ID disimpan di window object
            return komentar.user_id === window.currentUserId;
        },

        canDelete(komentar) {
            // Owner atau admin bisa hapus
            return komentar.user_id === window.currentUserId || window.isAdmin;
        },

        getInitial(name) {
            return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);
            
            if (diffMins < 1) return 'Baru saja';
            if (diffMins < 60) return `${diffMins}m lalu`;
            if (diffHours < 24) return `${diffHours}j lalu`;
            if (diffDays < 7) return `${diffDays}h lalu`;
            
            return date.toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        get totalKomentar() {
            let count = this.komentars.length;
            this.komentars.forEach(k => {
                if (k.replies) {
                    count += k.replies.length;
                }
            });
            return count;
        }
    };
}
</script>

<style>
.komentar-container::-webkit-scrollbar {
    width: 6px;
}

.komentar-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.komentar-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.komentar-container::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

.komentar-bubble {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.bg-primary-subtle {
    background-color: #dbeafe !important;
}
</style>
