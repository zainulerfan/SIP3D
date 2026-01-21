{{-- resources/views/TPK/create_alternative.blade.php --}}
@extends('layouts.app')

@section('title','Tambah Data TPK')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold mb-1">Tambah Data TPK</h4>
            <p class="text-muted mb-0">Tambah data dosen baru untuk perhitungan SAW</p>
        </div>
        <a href="{{ route('tpk.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('tpk.alternatif.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    {{-- Kode --}}
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Kode <span class="text-muted">(opsional)</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}"
                            placeholder="Contoh: D001">
                    </div>

                    {{-- Nama Dosen --}}
                    <div class="col-md-8">
                        <label class="form-label fw-medium">Nama Dosen <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                            placeholder="Masukkan nama lengkap dosen" required>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-semibold mb-3">Kriteria Penilaian</h6>

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">C1 - Skor SINTA</label>
                        <input type="number" name="skor_sinta" class="form-control" value="{{ old('skor_sinta', 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">C2 - Skor SINTA 3 Tahun</label>
                        <input type="number" name="skor_sinta_3yr" class="form-control" value="{{ old('skor_sinta_3yr', 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">C3 - Jumlah Buku</label>
                        <input type="number" name="jumlah_buku" class="form-control" value="{{ old('jumlah_buku', 0) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">C4 - Jumlah Hibah</label>
                        <input type="number" name="jumlah_hibah" class="form-control" value="{{ old('jumlah_hibah', 0) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">C5 - Publikasi Scholar (1 Tahun)</label>
                        <input type="number" name="publikasi_scholar" class="form-control" value="{{ old('publikasi_scholar', 0) }}">
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('tpk.index') }}" class="btn btn-outline-secondary px-4">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection