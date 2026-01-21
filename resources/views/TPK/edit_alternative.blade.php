{{-- resources/views/TPK/edit_alternative.blade.php --}}
@extends('layouts.app')

@section('title','Edit Data TPK - ' . $dosen->nama)

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Data TPK</h4>
            <p class="text-muted mb-0">Edit nilai TPK untuk: <strong>{{ $dosen->nama }}</strong></p>
        </div>
        <a href="{{ route('tpk.alternatif.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('tpk.alternatif.update', $dosen->id) }}" method="POST">
                @csrf @method('PUT')

                {{-- Info Dosen (Read-only) --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-medium text-muted">NIDN</label>
                        <input type="text" name="nidn" class="form-control" value="{{ $dosen->nidn }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium text-muted">Nama Dosen</label>
                        <input type="text" name="nama" class="form-control" value="{{ $dosen->nama }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium text-muted">Email</label>
                        <input type="text" name="email" class="form-control" value="{{ $dosen->email }}" readonly>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-graph-up me-2"></i>Nilai Kriteria TPK
                </h6>

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">C1 - Skor SINTA</label>
                        <input type="number" name="skor_sinta" class="form-control form-control-lg"
                            value="{{ old('skor_sinta', $dosen->skor_sinta ?? 0) }}" min="0">
                        <small class="text-muted">Skor akumulatif dari SINTA</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">C2 - Skor SINTA 3 Tahun</label>
                        <input type="number" name="skor_sinta_3yr" class="form-control form-control-lg"
                            value="{{ old('skor_sinta_3yr', $dosen->skor_sinta_3yr ?? 0) }}" min="0">
                        <small class="text-muted">Skor SINTA dalam 3 tahun terakhir</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">C3 - Jumlah Buku</label>
                        <input type="number" name="jumlah_buku" class="form-control form-control-lg"
                            value="{{ old('jumlah_buku', $dosen->jumlah_buku ?? 0) }}" min="0">
                        <small class="text-muted">Jumlah buku yang diterbitkan</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">C4 - Jumlah Hibah</label>
                        <input type="number" name="jumlah_hibah" class="form-control form-control-lg"
                            value="{{ old('jumlah_hibah', $dosen->jumlah_hibah ?? 0) }}" min="0">
                        <small class="text-muted">Jumlah hibah/grant yang diterima</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">C5 - Publikasi Scholar (1 Tahun)</label>
                        <input type="number" name="publikasi_scholar" class="form-control form-control-lg"
                            value="{{ old('publikasi_scholar', $dosen->publikasi_scholar ?? 0) }}" min="0">
                        <small class="text-muted">Jumlah publikasi di Google Scholar (1 tahun terakhir)</small>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('tpk.alternatif.index') }}" class="btn btn-outline-secondary px-4">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection