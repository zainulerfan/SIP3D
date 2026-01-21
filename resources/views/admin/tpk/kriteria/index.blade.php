@extends('layouts.app')

@section('title', 'Manage Kriteria TPK')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manage Kriteria TPK</h4>
            <p class="text-muted mb-0">
                Kelola bobot kriteria untuk perhitungan SAW.
            </p>
        </div>
        <a href="{{ route('tpk.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke TPK
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Card Kriteria --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Daftar Kriteria</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('tpk.kriteria.hitung') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-calculator me-1"></i> Hitung Bobot Otomatis
                </a>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditBobot">
                    <i class="bi bi-pencil-square me-1"></i> Edit Bobot
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="80">Kode</th>
                            <th>Nama Kriteria</th>
                            <th class="text-center" width="120">Bobot</th>
                            <th class="text-center" width="120">Tipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kriterias as $i => $k)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-primary">C{{ $i + 1 }}</span>
                            </td>
                            <td class="fw-medium">{{ $k->nama_kriteria }}</td>
                            <td class="text-center">
                                <span class="badge bg-success fs-6">{{ number_format($k->bobot, 2) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $k->tipe == 'benefit' ? 'bg-info' : 'bg-warning' }}">
                                    {{ ucfirst($k->tipe ?? 'benefit') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Belum ada data kriteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td class="ps-4" colspan="2"><strong>Total Bobot</strong></td>
                            <td class="text-center">
                                <strong class="text-primary fs-5">
                                    {{ number_format($kriterias->sum('bobot'), 2) }}
                                </strong>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="alert alert-info d-flex align-items-start">
        <i class="bi bi-info-circle-fill me-3 fs-4"></i>
        <div>
            <strong>Info:</strong> Total bobot harus sama dengan <strong>1.00</strong> agar perhitungan SAW benar.
            <br>Klik "Hitung Bobot Otomatis" untuk membagi bobot secara merata.
        </div>
    </div>

</div>

<!-- MODAL EDIT BOBOT -->
<div class="modal fade" id="modalEditBobot" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('tpk.kriteria.updateBobot') }}">
                @csrf

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Bobot Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info py-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Total bobot harus sama dengan <strong>1.000</strong>
                    </div>

                    {{-- INPUT BOBOT --}}
                    @foreach($kriterias as $i => $k)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <span class="badge bg-primary me-2">C{{ $i + 1 }}</span>
                            {{ $k->nama_kriteria }}
                        </label>
                        <input type="number"
                            step="0.01"
                            min="0"
                            max="1"
                            name="bobot[{{ $k->id }}]"
                            value="{{ old('bobot.'.$k->id, $k->bobot) }}"
                            class="form-control bobot-input">
                    </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Total Bobot:</strong>
                        <strong id="totalBobot" class="fs-4 text-success">1.000</strong>
                    </div>

                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">
                        <i class="bi bi-save me-1"></i> Simpan Bobot
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- SCRIPT HITUNG TOTAL --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.bobot-input');
        const totalText = document.getElementById('totalBobot');
        const btn = document.getElementById('btnSimpan');

        function hitungTotal() {
            let total = 0;
            inputs.forEach(i => total += parseFloat(i.value) || 0);

            totalText.innerText = total.toFixed(3);

            // Disable tombol kalau total tidak sama dengan 1
            if (total.toFixed(3) != "1.000") {
                btn.disabled = true;
                totalText.classList.remove('text-success');
                totalText.classList.add('text-danger');
            } else {
                btn.disabled = false;
                totalText.classList.remove('text-danger');
                totalText.classList.add('text-success');
            }
        }

        inputs.forEach(i => i.addEventListener('input', hitungTotal));
        hitungTotal();
    });
</script>
@endsection