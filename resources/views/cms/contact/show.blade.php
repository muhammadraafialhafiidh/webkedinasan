@extends('layouts.cms')

@section('title', 'Detail Pesan — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.pesan.index') }}" class="text-decoration-none text-muted">Pesan Masuk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail #{{ $message->id }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Detail Pesan Masuk #{{ $message->id }}</h3>
        <p class="text-muted small mb-0">Diterima pada {{ $message->created_at ? $message->created_at->format('d F Y \j\a\m H:i') : '' }}</p>
    </div>
    <a href="{{ route('cms.pesan.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Inbox
    </a>
</div>

<div class="row g-4">
    <!-- Message Content (8 col) -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <div class="border-bottom pb-3 mb-3">
                <span class="badge bg-light text-primary border mb-2 fs-6">Subjek: {{ $message->subject }}</span>
                <h4 class="fw-bold text-dark mb-0">{{ $message->subject }}</h4>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-muted small">Isi Pesan / Pertanyaan:</label>
                <div class="p-3 bg-light rounded-3 border leading-relaxed text-dark" style="font-size: 1rem; line-height: 1.7;">
                    {!! nl2br(e($message->message)) !!}
                </div>
            </div>            @if($message->reply)
                <div class="alert alert-success border-0 p-4 rounded-3 mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-reply-fill me-1"></i>Balasan Telah Dikirim ({{ $message->replied_at ? $message->replied_at->format('d/m/Y H:i') : '' }}):</h6>
                    <div class="text-dark">{!! nl2br(e($message->clean_reply)) !!}</div>
                </div>
            @endif

            <!-- Reply Form -->
            <form action="{{ route('cms.pesan.reply', $message->id) }}" method="POST" id="replyForm" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="reply" class="form-label fw-bold text-primary">
                        Tulis Balasan Pesan <span class="text-danger">*</span>
                    </label>
                    <textarea name="reply" id="reply" rows="5" class="form-control @error('reply') is-invalid @enderror" placeholder="Tuliskan pesan balasan resmi untuk pengirim...">{{ old('reply', $message->clean_reply) }}</textarea>
                    <div id="replyFeedback" class="invalid-feedback @error('reply') d-block @enderror fw-semibold mt-1">
                        <i class="bi bi-exclamation-circle-fill me-1"></i>
                        <span id="replyFeedbackText">{{ $errors->first('reply') ?? 'Balasan wajib diisi.' }}</span>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-primary font-bold px-4 shadow-sm" id="btnSubmitReply">
                        <i class="bi bi-send-fill me-1"></i> Simpan & Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sender Details Sidebar (4 col) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-person-lines-fill me-2"></i>Informasi Pengirim</h5>
            <div class="d-flex flex-column gap-3 small">
                <div>
                    <label class="text-muted d-block fw-semibold">Nama Lengkap</label>
                    <div class="fw-bold text-dark fs-6">{{ $message->name }}</div>
                </div>
                <div>
                    <label class="text-muted d-block fw-semibold">Alamat Email</label>
                    <a href="mailto:{{ $message->email }}" class="text-primary fw-semibold">{{ $message->email }}</a>
                </div>
                <div>
                    <label class="text-muted d-block fw-semibold">Nomor Telepon</label>
                    <div class="fw-semibold text-dark">{{ $message->phone ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-muted d-block fw-semibold">Status Pesan</label>
                    @if($message->is_read)
                        <span class="badge bg-success">Sudah Dibaca</span>
                    @else
                        <span class="badge bg-danger">Belum Dibaca</span>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <form action="{{ route('cms.pesan.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini secara permanen?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold">
                    <i class="bi bi-trash me-1"></i> Hapus Pesan Ini
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Kirim Balasan -->
<div class="modal fade" id="confirmReplyModal" tabindex="-1" aria-labelledby="confirmReplyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-primary" id="confirmReplyModalLabel">
                    <i class="bi bi-send-check-fill me-2"></i>Konfirmasi Kirim Balasan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <p class="text-dark fs-6 mb-3">
                    Apakah Anda yakin ingin mengirim balasan ini?
                </p>

                <!-- Email Tujuan -->
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="small text-muted fw-semibold mb-1">Email tujuan:</div>
                    <div class="fw-bold text-primary fs-6 text-break">
                        <i class="bi bi-envelope-at-fill me-1"></i>{{ $message->email }}
                    </div>
                </div>

                <!-- Isi Balasan Terkini -->
                <div class="mb-3">
                    <div class="small text-muted fw-semibold mb-1">Isi balasan:</div>
                    <div id="modalReplyPreview" class="p-3 bg-light rounded-3 border text-dark" style="font-size: 0.92rem; line-height: 1.6; white-space: pre-wrap; word-break: break-word; max-height: 160px; overflow-y: auto;"></div>
                </div>

                <div class="alert alert-info border-0 d-flex align-items-center gap-2 mb-0 py-2 px-3 small">
                    <i class="bi bi-info-circle-fill fs-5 text-info flex-shrink-0"></i>
                    <span>Balasan akan disimpan dan dikirim melalui email kepada pengirim pesan.</span>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal" id="btnCancelReply">
                    Batalkan
                </button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" id="btnConfirmSendReply">
                    <i class="bi bi-send-fill me-1"></i> Ya, Kirim Balasan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('replyForm');
        const replyTextarea = document.getElementById('reply');
        const replyFeedback = document.getElementById('replyFeedback');
        const replyFeedbackText = document.getElementById('replyFeedbackText');
        const btnSubmitReply = document.getElementById('btnSubmitReply');
        const confirmModalEl = document.getElementById('confirmReplyModal');
        const modalReplyPreview = document.getElementById('modalReplyPreview');
        const btnConfirmSendReply = document.getElementById('btnConfirmSendReply');
        const btnCancelReply = document.getElementById('btnCancelReply');

        let confirmModal = null;
        if (confirmModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            confirmModal = new bootstrap.Modal(confirmModalEl);
        }

        let isSubmitting = false;

        // Normalisasi HTML dari Rich Text Editor menjadi plain text berparagraf
        function htmlToCleanText(htmlOrText) {
            if (!htmlOrText) return '';
            let str = htmlOrText
                .replace(/<\/(p|div|h[1-6]|li|tr|blockquote)>/gi, '\n\n')
                .replace(/<br\s*\/?>/gi, '\n')
                .replace(/<\/td>/gi, ' ');

            const doc = new DOMParser().parseFromString(str, 'text/html');
            let text = doc.body.textContent || '';

            text = text.replace(/[ \t]+/g, ' ');
            text = text.replace(/(\r\n|\n|\r)/g, '\n');
            text = text.replace(/\n{3,}/g, '\n\n');

            return text.trim();
        }

        function getReplyRawValue() {
            if (window.ckEditors && window.ckEditors['reply']) {
                return window.ckEditors['reply'].getData();
            }
            return replyTextarea ? replyTextarea.value : '';
        }

        // Klik tombol "Simpan & Kirim Balasan"
        if (btnSubmitReply && replyTextarea) {
            btnSubmitReply.addEventListener('click', function (e) {
                e.preventDefault();

                // Pastikan sinkronisasi dari CKEditor ke textarea jika aktif
                if (window.ckEditors && window.ckEditors['reply']) {
                    replyTextarea.value = window.ckEditors['reply'].getData();
                }

                const rawVal = getReplyRawValue();
                const cleanVal = htmlToCleanText(rawVal);

                if (!cleanVal) {
                    // Validasi Balasan Kosong: Tampilkan feedback & toast (JANGAN buka konfirmasi)
                    replyTextarea.classList.add('is-invalid');
                    if (replyFeedback) {
                        replyFeedback.classList.add('d-block');
                        if (replyFeedbackText) {
                            replyFeedbackText.textContent = 'Balasan wajib diisi.';
                        }
                    }
                    
                    if (window.ckEditors && window.ckEditors['reply']) {
                        window.ckEditors['reply'].editing.view.focus();
                    } else {
                        replyTextarea.focus();
                    }

                    if (typeof window.showCmsToast === 'function') {
                        window.showCmsToast('danger', 'Balasan wajib diisi.', 'Perhatian!');
                    }
                    return;
                }

                // Jika valid, hilangkan error dan update isi balasan preview di modal
                replyTextarea.classList.remove('is-invalid');
                if (replyFeedback) {
                    replyFeedback.classList.remove('d-block');
                }

                // Masukkan teks bersih terkini ke modal secara aman
                if (modalReplyPreview) {
                    modalReplyPreview.textContent = cleanVal;
                }

                if (confirmModal) {
                    confirmModal.show();
                } else if (confirm('Apakah Anda yakin ingin mengirim balasan ini ke ' + @json($message->email) + '?')) {
                    form.submit();
                }
            });
        }

        // Hilangkan status invalid saat user mulai mengetik
        if (replyTextarea) {
            replyTextarea.addEventListener('input', function () {
                if (this.value.trim().length > 0) {
                    this.classList.remove('is-invalid');
                    if (replyFeedback) {
                        replyFeedback.classList.remove('d-block');
                    }
                }
            });
        }

        // Klik "Ya, Kirim Balasan" di dalam modal konfirmasi
        if (btnConfirmSendReply && form) {
            btnConfirmSendReply.addEventListener('click', function () {
                if (isSubmitting) return;
                isSubmitting = true;

                // Cegah double submit & tampilkan indikator loading
                btnConfirmSendReply.disabled = true;
                if (btnCancelReply) {
                    btnCancelReply.disabled = true;
                }
                btnConfirmSendReply.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mengirim...';

                // Submit form
                form.submit();
            });
        }
    });
</script>
@endpush

@endsection
