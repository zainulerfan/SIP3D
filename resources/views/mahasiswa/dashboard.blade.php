@extends('layouts.app')

@section('content')
<div class="container py-4">

    @php
    // Supaya tidak error kalau controller belum kirim
    $penelitians = $penelitians ?? collect();
    $pengabdians = $pengabdians ?? collect();
    @endphp

    {{-- Heading --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-1">SIP3D - Dashboard Mahasiswa</h3>
        <p class="text-muted mb-0">
            Upload dokumentasi foto dan video kegiatan penelitian serta pengabdian masyarakat.
        </p>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning mb-4">
        {!! session('warning') !!}
    </div>
    @endif

    {{-- Kartu statistik --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width:80px; height:80px; background:#e9f9ef;">
                        <i class="bi bi-image text-success" style="font-size:2rem;"></i>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $fotoCount ?? 0 }}</h2>
                    <p class="text-muted mb-0">Foto Dokumentasi</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width:80px; height:80px; background:#f3e9ff;">
                        <i class="bi bi-camera-video text-purple" style="font-size:2rem; color:#8b5cf6;"></i>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $videoCount ?? 0 }}</h2>
                    <p class="text-muted mb-0">Video Dokumentasi</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-4">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width:80px; height:80px; background:#e9f1ff;">
                        <i class="bi bi-file-earmark-text text-primary" style="font-size:2rem;"></i>
                    </div>
                    <h2 class="fw-bold mb-1">{{ ($fotoCount ?? 0) + ($videoCount ?? 0) }}</h2>
                    <p class="text-muted mb-0">Total Dokumentasi</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- PENELITIAN --}}
    {{-- ===================== --}}
    <div class="mb-5">
        <h5 class="fw-bold mb-1">Penelitian yang Ditugaskan</h5>
        <p class="text-muted small mb-3">
            Daftar penelitian di mana Anda menjadi penanggung jawab dokumentasi.
        </p>

        <div class="card shadow-sm border-0">
            @if($penelitians->isEmpty())
            <div class="card-body py-4">
                <div class="alert alert-info mb-0">
                    Belum ada penelitian yang menugaskan Anda sebagai mahasiswa dokumentasi.
                </div>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Judul Penelitian</th>
                            <th>Ketua</th>
                            <th>Bidang</th>
                            <th width="80">Tahun</th>
                            <th width="80">Status</th>
                            <th class="text-end pe-4" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penelitians as $penelitian)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $penelitian->judul }}</td>
                            <td>{{ optional($penelitian->dosen)->nama ?? $penelitian->ketua_manual ?? '-' }}</td>
                            <td>{{ $penelitian->bidang ?? '-' }}</td>
                            <td>{{ $penelitian->tahun ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $penelitian->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $penelitian->status ?? '-' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('mahasiswa.dokumentasi.create', $penelitian->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-upload me-1"></i> Upload
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ===================== --}}
    {{-- PENGABDIAN --}}
    {{-- ===================== --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Pengabdian yang Ditugaskan</h5>
        <p class="text-muted small mb-3">
            Daftar pengabdian di mana Anda menjadi penanggung jawab dokumentasi.
        </p>

        <div class="card shadow-sm border-0">
            @if($pengabdians->isEmpty())
            <div class="card-body py-4">
                <div class="alert alert-info mb-0">
                    Belum ada pengabdian yang menugaskan Anda sebagai mahasiswa dokumentasi.
                </div>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Judul Pengabdian</th>
                            <th>Ketua</th>
                            <th>Bidang</th>
                            <th width="80">Tahun</th>
                            <th width="80">Status</th>
                            <th class="text-end pe-4" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengabdians as $pengabdian)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $pengabdian->judul }}</td>
                            <td>{{ $pengabdian->ketua_pengabdian }}</td>
                            <td>{{ $pengabdian->bidang }}</td>
                            <td>{{ $pengabdian->tahun }}</td>
                            <td>
                                <span class="badge {{ $pengabdian->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $pengabdian->status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('mahasiswa.dokumentasi_pengabdian.create', $pengabdian->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-upload me-1"></i> Upload
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection