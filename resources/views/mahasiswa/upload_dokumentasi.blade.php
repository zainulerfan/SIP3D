@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold mb-1">Upload Dokumentasi Penelitian</h4>
            <p class="text-muted mb-0">
                Unggah foto / video atau tempelkan link Google Drive dokumentasi penelitian.
            </p>
        </div>
        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Info penelitian --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="fw-bold text-primary mb-3">{{ $penelitian->judul }}</h5>

                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted small">Ketua Penelitian (Dosen):</span>
                            <p class="mb-0 fw-medium">{{ optional($penelitian->dosen)->nama ?? $penelitian->ketua_manual ?? '-' }}</p>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted small">Bidang:</span>
                            <p class="mb-0 fw-medium">{{ $penelitian->bidang ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="mb-2">
                        <span class="text-muted small">Tahun:</span>
                        <p class="mb-0 fw-bold fs-5">{{ $penelitian->tahun ?? '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Status:</span>
                        <p class="mb-0">
                            <span class="badge {{ $penelitian->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                {{ $penelitian->status ?? '-' }}
                            </span>
                        </p>
                    </div>
                    <p class="text-muted small mb-0">
                        Mahasiswa Dok.: {{ $penelitian->mahasiswa_dok ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form upload --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="mb-1 fw-semibold">Form Upload Dokumentasi</h5>
            <p class="text-muted small mb-0">
                Anda boleh mengupload file langsung, menempelkan link Google Drive, atau keduanya.
            </p>
        </div>
        <div class="card-body p-4">

            <form action="{{ route('mahasiswa.dokumentasi.store', $penelitian->id) }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf

                {{-- Row jenis & file upload --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Jenis Dokumentasi</label>
                        <select name="jenis" class="form-select form-select-lg" required>
                            <option value="">-- Pilih Jenis Dokumentasi --</option>
                            <option value="foto" {{ old('jenis') == 'foto' ? 'selected' : '' }}>Foto</option>
                            <option value="video" {{ old('jenis') == 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                        @error('jenis')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-medium">File Dokumentasi (Opsional jika pakai link)</label>
                        <input type="file" name="file" class="form-control form-control-lg">
                        <small class="text-muted d-block mt-1">
                            Format: <strong>JPG, JPEG, PNG, MP4</strong>, maksimal <strong>20 MB</strong>.
                        </small>
                        @error('file')
                        <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Link Google Drive --}}
                <div class="mb-4">
                    <label class="form-label fw-medium">
                        <i class="bi bi-link-45deg me-1"></i> Link Google Drive (Opsional)
                    </label>
                    <input type="url"
                        name="drive_link"
                        class="form-control form-control-lg"
                        placeholder="Tempel link Google Drive di sini, contoh: https://drive.google.com/..."
                        value="{{ old('drive_link') }}">
                    <small class="text-muted">
                        Jika file sudah diunggah ke Google Drive, cukup tempel tautannya di sini.
                    </small>
                    @error('drive_link')
                    <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div>
                        <strong>Catatan:</strong> Minimal salah satu harus diisi:
                        upload file dokumentasi <em>atau</em> link Google Drive.
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-outline-secondary btn-lg px-4">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-cloud-upload me-1"></i> Upload Dokumentasi
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection