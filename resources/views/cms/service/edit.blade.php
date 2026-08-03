@extends('layouts.cms')

@section('title', 'Edit Layanan — CMS')

@section('cms_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cms.layanan.index') }}" class="text-decoration-none text-muted">Layanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit #{{ $service->id }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-1">Edit Layanan Publik</h3>
        <p class="text-muted small mb-0">Perbarui rincian Standar Pelayanan Publik #{{ $service->id }}</p>
    </div>
    <a href="{{ route('cms.layanan.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<form action="{{ route('cms.layanan.update', $service->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Form (8 col) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <div class="mb-3">
                    <label for="title" class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control form-control-lg" value="{{ old('title', $service->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Singkat Layanan <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="3" class="form-control" required>{{ old('description', $service->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="requirements" class="form-label">Persyaratan Pelayanan (HTML List)</label>
                    <textarea name="requirements" id="requirements" rows="6" class="form-control text-editor">{{ old('requirements', $service->requirements) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="procedure" class="form-label">Prosedur & Mekanisme (HTML Ordered List)</label>
                    <textarea name="procedure" id="procedure" rows="6" class="form-control text-editor">{{ old('procedure', $service->procedure) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Meta Sidebar (4 col) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3">Atribut Standar Layanan</h5>

                <div class="mb-3">
                    <label for="service_category_id" class="form-label">Bidang / Kategori <span class="text-danger">*</span></label>
                    <select name="service_category_id" id="service_category_id" class="form-select" required>
                        <option value="">-- Pilih Bidang Layanan --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('service_category_id', $service->service_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Multiple Penanggung Jawab Section -->
                <div class="mb-4 p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Penanggung Jawab Layanan</label>
                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 fw-bold" onclick="addOfficerRow()">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </div>
                    <small class="text-muted d-block mb-3" style="font-size: 0.75rem;">Bisa ditambahkan lebih dari 1 penanggung jawab beserta keterangannya (contoh: Petugas PSAT-PDUK / PSAI).</small>

                    <div id="officerContainer">
                        @php
                            $attachedOfficers = $service->penanggungJawabList;
                        @endphp

                        @forelse($attachedOfficers as $index => $attached)
                            <div class="officer-row bg-white p-2 rounded border mb-2 position-relative">
                                <div class="mb-2">
                                    <select name="penanggung_jawab_ids[]" class="form-select form-select-sm">
                                        <option value="">-- Pilih Petugas --</option>
                                        @foreach($penanggungJawabList as $pj)
                                            <option value="{{ $pj->id }}" {{ $attached->id == $pj->id ? 'selected' : '' }}>
                                                {{ $pj->nama }} ({{ $pj->nomor_hp }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex gap-1">
                                    <input type="text" name="penanggung_jawab_keterangans[]" class="form-control form-control-sm" value="{{ $attached->pivot->keterangan ?? '' }}" placeholder="Keterangan (contoh: Petugas PSAT-PDUK)">
                                    <button type="button" class="btn btn-sm btn-outline-danger px-2" onclick="removeOfficerRow(this)" title="Hapus"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        @empty
                            <div class="officer-row bg-white p-2 rounded border mb-2 position-relative">
                                <div class="mb-2">
                                    <select name="penanggung_jawab_ids[]" class="form-select form-select-sm">
                                        <option value="">-- Pilih Petugas --</option>
                                        @foreach($penanggungJawabList as $pj)
                                            <option value="{{ $pj->id }}">{{ $pj->nama }} ({{ $pj->nomor_hp }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex gap-1">
                                    <input type="text" name="penanggung_jawab_keterangans[]" class="form-control form-control-sm" placeholder="Keterangan (contoh: Petugas PSAT-PDUK)">
                                    <button type="button" class="btn btn-sm btn-outline-danger px-2" onclick="removeOfficerRow(this)" title="Hapus"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Jangka Waktu Penyelesaian</label>
                    <input type="text" name="duration" id="duration" class="form-control" value="{{ old('duration', $service->duration) }}">
                </div>

                <div class="mb-3">
                    <label for="cost" class="form-label">Biaya / Tarif</label>
                    <input type="text" name="cost" id="cost" class="form-control" value="{{ old('cost', $service->cost) }}">
                </div>

                <div class="mb-3">
                    <label for="product" class="form-label">Produk Pelayanan / Output</label>
                    <input type="text" name="product" id="product" class="form-control" value="{{ old('product', $service->product) }}">
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label">Urutan Tampil</label>
                    <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $service->order) }}">
                </div>

                <div class="mb-4">
                    <label for="is_active" class="form-label">Status Layanan <span class="text-danger">*</span></label>
                    <select name="is_active" id="is_active" class="form-select" required>
                        <option value="1" {{ old('is_active', $service->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif (Tampil di Website)</option>
                        <option value="0" {{ old('is_active', $service->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary font-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Perbarui Layanan
                    </button>
                    <a href="{{ route('cms.layanan.index') }}" class="btn btn-light border">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    function addOfficerRow() {
        const container = document.getElementById('officerContainer');
        const firstRow = container.querySelector('.officer-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelectorAll('input, select').forEach(el => el.value = '');
        container.appendChild(newRow);
    }

    function removeOfficerRow(btn) {
        const container = document.getElementById('officerContainer');
        const rows = container.querySelectorAll('.officer-row');
        if (rows.length > 1) {
            btn.closest('.officer-row').remove();
        } else {
            const firstRow = rows[0];
            firstRow.querySelectorAll('input, select').forEach(el => el.value = '');
        }
    }
</script>
@endpush
