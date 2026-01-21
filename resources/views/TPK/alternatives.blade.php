{{-- resources/views/TPK/alternatives.blade.php --}}
@extends('layouts.app')

@section('title','Edit Data TPK Dosen')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit Data TPK Dosen</h4>
            <p class="text-muted mb-0">Kelola nilai TPK untuk setiap dosen</p>
        </div>
        <a href="{{ route('tpk.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke TPK
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Search --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-3">
            <form action="{{ route('tpk.alternatif.index') }}" method="GET" class="d-flex">
                <input name="q" type="search" class="form-control me-2" placeholder="Cari nama dosen atau NIDN..."
                    value="{{ request('q') }}" style="max-width:300px;">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
                @if(request('q'))
                <a href="{{ route('tpk.alternatif.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">
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
                            <th class="text-center pe-4" width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosens as $i => $d)
                        <tr>
                            <td class="ps-4">{{ ($dosens->firstItem() ?? 0) + $i }}</td>
                            <td>
                                <div class="fw-medium">{{ $d->nama }}</div>
                                <small class="text-muted">NIDN: {{ $d->nidn ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">{{ number_format($d->skor_sinta ?? 0) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">{{ number_format($d->skor_sinta_3yr ?? 0) }}</span>
                            </td>
                            <td class="text-center">{{ $d->jumlah_buku ?? 0 }}</td>
                            <td class="text-center">{{ $d->jumlah_hibah ?? 0 }}</td>
                            <td class="text-center">{{ $d->publikasi_scholar ?? 0 }}</td>
                            <td class="text-center pe-4">
                                <a href="{{ route('tpk.alternatif.edit', $d->id) }}" class="btn btn-sm btn-primary" title="Edit TPK">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data dosen. <a href="{{ route('dosen.index') }}">Tambah dosen</a> terlebih dahulu.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($dosens, 'links') && $dosens->hasPages())
        <div class="card-footer bg-white">
            {{ $dosens->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <div class="alert alert-info mt-4">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Info:</strong> Klik tombol <strong>Edit</strong> untuk mengubah nilai TPK (SINTA, Buku, Hibah, dll) untuk setiap dosen.
    </div>

</div>
@endsection