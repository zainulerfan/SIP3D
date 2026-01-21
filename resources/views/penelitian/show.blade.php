@extends('layouts.app')

@section('title', 'Detail Penelitian')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white fw-semibold">
                    <i class="bi bi-eye me-1"></i> Detail Data Penelitian
                </div>

                <div class="card-body">

                    <h4 class="mb-3 text-primary fw-bold">{{ $penelitian->judul }}</h4>

                    <table class="table table-bordered">
                        <tr>
                            <th class="bg-light w-25">Bidang</th>
                            <td>{{ $penelitian->bidang }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Tahun</th>
                            <td>{{ $penelitian->tahun }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Status</th>
                            <td>
                                @if($penelitian->status == 'Aktif')
                                <span class="badge bg-success">Aktif</span>
                                @elseif($penelitian->status == 'Selesai')
                                <span class="badge bg-primary">Selesai</span>
                                @else
                                <span class="badge bg-secondary">{{ $penelitian->status }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Tanggal Pelaksanaan</th>
                            <td>
                                {{ \Carbon\Carbon::parse($penelitian->tanggal_mulai)->format('d M Y') }}
                                s/d
                                {{ $penelitian->tanggal_selesai ? \Carbon\Carbon::parse($penelitian->tanggal_selesai)->format('d M Y') : 'Sekarang' }}
                            </td>
                        </tr>
                    </table>

                    {{-- Data Ketua --}}
                    <div class="mt-4">
                        <h6 class="fw-bold border-bottom pb-2">Ketua Penelitian</h6>
                        <p class="mb-0 card-text">
                            <strong>Nama:</strong> {{ $penelitian->dosen->nama ?? '-' }} <br>
                            <strong>NIDN:</strong> {{ $penelitian->dosen->nidn ?? '-' }} <br>
                            <strong>Prodi:</strong> {{ $penelitian->dosen->prodi ?? '-' }}
                        </p>
                    </div>

                    {{-- Data Anggota --}}
                    <div class="mt-4">
                        <h6 class="fw-bold border-bottom pb-2">Anggota Peneliti (Dosen)</h6>

                        @if($penelitian->anggotaDosens->isEmpty())
                        <em class="text-muted">Tidak ada anggota dosen.</em>
                        @else
                        <ul class="list-group list-group-flush">
                            @foreach($penelitian->anggotaDosens as $anggota)
                            <li class="list-group-item px-0">
                                {{ $anggota->nama }} <span class="text-muted">({{ $anggota->nidn }})</span>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    {{-- Data Mahasiswa --}}
                    <div class="mt-4">
                        <h6 class="fw-bold border-bottom pb-2">Mahasiswa Dokumentasi</h6>
                        <p class="mb-0 card-text">
                            @if($penelitian->mahasiswa)
                            <strong>Nama:</strong> {{ $penelitian->mahasiswa->nama }} <br>
                            <strong>NIM:</strong> {{ $penelitian->mahasiswa->nim ?? '-' }} <br>
                            <strong>Prodi:</strong> {{ $penelitian->mahasiswa->prodi ?? '-' }}
                            @else
                            <em class="text-muted">Tidak ada mahasiswa ditugaskan.</em>
                            @endif
                        </p>
                    </div>

                </div>

                <div class="card-footer bg-white text-end">
                    <a href="{{ Auth::user()->role === 'dosen' ? route('dosen.penelitian.index') : route('penelitian.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ Auth::user()->role === 'dosen' ? route('dosen.penelitian.edit', $penelitian->id) : route('penelitian.edit', $penelitian->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection