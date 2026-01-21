{{-- resources/views/TPK/index.blade.php --}}
@extends('layouts.app')

@section('title', 'TPK - Count (SAW)')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold mb-1">TPK - Penilaian Kinerja Dosen (SAW)</h4>
            <p class="text-muted mb-0">Sistem Pendukung Keputusan menggunakan data dosen yang terdaftar</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('tpk.export') }}" class="btn btn-success">
                <i class="bi bi-download me-1"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- 1) Data TPK (Dosen) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-people me-2"></i>Data Dosen
                <span class="badge bg-primary ms-2">{{ $dosensTable->count() }} dosen</span>
            </h5>
            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('tpk.index') }}" class="d-flex">
                    <input name="q" class="form-control form-control-sm me-2" placeholder="Cari dosen..."
                        value="{{ request('q') }}" style="width:180px;">
                </form>
                <a href="{{ route('tpk.alternatif.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit Data TPK
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="50">No.</th>
                            <th>Nama Dosen</th>
                            <th class="text-center" width="100">SINTA</th>
                            <th class="text-center" width="100">SINTA 3Yr</th>
                            <th class="text-center" width="80">Buku</th>
                            <th class="text-center" width="80">Hibah</th>
                            <th class="text-center" width="90">Scholar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosensTable as $i => $d)
                        <tr>
                            <td class="ps-4">{{ $i+1 }}</td>
                            <td>
                                <div class="fw-medium">{{ $d->nama }}</div>
                                <small class="text-muted">{{ $d->nidn ?? '-' }}</small>
                            </td>
                            <td class="text-center">{{ number_format($d->skor_sinta ?? 0) }}</td>
                            <td class="text-center">{{ number_format($d->skor_sinta_3yr ?? 0) }}</td>
                            <td class="text-center">{{ $d->jumlah_buku ?? 0 }}</td>
                            <td class="text-center">{{ $d->jumlah_hibah ?? 0 }}</td>
                            <td class="text-center">{{ $d->publikasi_scholar ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data dosen. <a href="{{ route('dosen.index') }}">Tambah dosen</a> terlebih dahulu.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 2) Bobot Kriteria --}}
    @php
    $weightsArr = $weights ?? [];
    $total_bobot = $weightsArr ? array_sum(array_values($weightsArr)) : 0;
    @endphp

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-sliders me-2"></i>Bobot Kriteria</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('tpk.kriteria.hitung') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-calculator me-1"></i> Hitung Otomatis
                </a>
                <a href="{{ route('tpk.kriteria.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil-square me-1"></i> Edit Bobot
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            @foreach($weightsArr as $key => $val)
                            <th>{{ $key }}</th>
                            @endforeach
                            <th class="bg-primary text-white">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($weightsArr as $key => $val)
                            <td class="fw-semibold">{{ number_format($val, 2) }}</td>
                            @endforeach
                            <td class="fw-bold text-primary">{{ number_format($total_bobot, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 3) Normalization --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-graph-up me-2"></i>Normalisasi</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Dosen</th>
                            @foreach($criteria_labels as $label)
                            <th class="text-center">{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($normalized as $row)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $row['label'] }}</td>
                            @foreach($criteria as $col)
                            <td class="text-center">{{ number_format($row[$col] ?? 0, 3) }}</td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($criteria)+1 }}" class="text-center py-4 text-muted">
                                Tidak ada data.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 4) Weighted Matrix --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-table me-2"></i>Weighted Matrix</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Dosen</th>
                            @foreach($criteria_labels as $label)
                            <th class="text-center">{{ $label }}</th>
                            @endforeach
                            <th class="text-center bg-success text-white">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($weighted as $row)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $row['label'] }}</td>
                            @foreach($criteria as $col)
                            <td class="text-center">{{ number_format($row[$col] ?? 0, 3) }}</td>
                            @endforeach
                            <td class="text-center fw-bold text-success">{{ number_format($row['score'], 3) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($criteria)+2 }}" class="text-center py-4 text-muted">
                                Tidak ada data.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 5) Final Results & Ranking --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-trophy me-2"></i>Hasil Akhir & Ranking</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="100">Ranking</th>
                            <th>Nama Dosen</th>
                            <th class="text-end pe-4" width="150">Score (V)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $r)
                        <tr>
                            <td class="ps-4">
                                @if($r['rank'] == 1)
                                <span class="badge bg-warning text-dark fs-6"><i class="bi bi-trophy-fill me-1"></i>#{{ $r['rank'] }}</span>
                                @elseif($r['rank'] == 2)
                                <span class="badge bg-secondary fs-6">#{{ $r['rank'] }}</span>
                                @elseif($r['rank'] == 3)
                                <span class="badge bg-danger fs-6">#{{ $r['rank'] }}</span>
                                @else
                                <span class="badge bg-primary fs-6">#{{ $r['rank'] }}</span>
                                @endif
                            </td>
                            <td class="fw-medium">{{ $r['label'] }}</td>
                            <td class="text-end pe-4 fw-bold">{{ number_format($r['score'], 4) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                Belum ada hasil. Pastikan ada data dosen dengan nilai TPK.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Info:</strong> TPK sekarang terintegrasi dengan data Dosen.
        Untuk mengubah nilai TPK, klik <a href="{{ route('tpk.alternatif.index') }}">Edit Data TPK</a>.
    </div> -->

</div>
@endsection