@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@push('styles')
<style>
  body {
    background-color: #f4f6f9;
  }

  .container-dashboard {
    max-width: 1100px;
    margin: 40px auto 80px;
    padding: 0 16px;
  }

  .dashboard-header {
    margin-bottom: 28px;
  }

  .dashboard-header h4 {
    font-weight: 700;
    margin-bottom: 4px;
  }

  .welcome-text {
    color: #6b7280;
  }

  .card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 24px;
  }

  .action-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 24px 22px 22px;
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.05);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 200px;
  }

  .action-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
  }

  .card-icon {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 14px;
  }

  .icon-blue { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
  .icon-green { background: rgba(16, 185, 129, 0.15); color: #10b981; }
  .icon-gray { background: rgba(107, 114, 128, 0.15); color: #6b7280; }

  .card-title {
    font-weight: 700;
    font-size: 1.05rem;
    margin-bottom: 6px;
  }

  .card-desc {
    color: #6b7280;
    font-size: 0.92rem;
    line-height: 1.5;
    margin-bottom: 18px;
  }

  .btn-manage {
    border-radius: 10px;
    border: none;
    font-weight: 700;
    padding: 10px 24px;
    width: 100%;
    text-align: center;
    display: block;
    text-decoration: none;
    transition: background 0.2s ease, transform 0.15s ease;
  }

  .btn-manage:hover {
    transform: scale(1.02);
    text-decoration: none;
  }

  .btn-blue { background: #3b82f6; color: #fff; }
  .btn-blue:hover { background: #2563eb; color: #fff; }

  .btn-green { background: #10b981; color: #fff; }
  .btn-green:hover { background: #059669; color: #fff; }

  .btn-gray { background: #6b7280; color: #fff; }
  .btn-gray:hover { background: #4b5563; color: #fff; }
</style>
@endpush

@section('content')
<div class="container-dashboard">

  <div class="dashboard-header">
    <h4>Dashboard Dosen</h4>
    <div class="welcome-text">
      Selamat datang, <span class="fw-semibold text-primary">{{ ucfirst($displayName ?? 'Dosen') }}</span>
    </div>
  </div>

  <div class="card-grid">

    <div class="action-card">
      <div>
        <div class="card-icon icon-blue">
          <i class="bi bi-journal-text"></i>
        </div>
        <div class="card-title">Kelola Penelitian</div>
        <div class="card-desc">Kelola data penelitian Anda dengan mudah dan terstruktur.</div>
      </div>
      <a href="{{ route('dosen.penelitian.index') }}" class="btn-manage btn-blue">Kelola Penelitian</a>
    </div>

    <div class="action-card">
      <div>
        <div class="card-icon icon-green">
          <i class="bi bi-people"></i>
        </div>
        <div class="card-title">Kelola Pengabdian</div>
        <div class="card-desc">Kelola kegiatan pengabdian kepada masyarakat Anda.</div>
      </div>
      <a href="{{ route('dosen.pengabdian.index') }}" class="btn-manage btn-green">Kelola Pengabdian</a>
    </div>

    <div class="action-card">
      <div>
        <div class="card-icon icon-gray">
          <i class="bi bi-mortarboard"></i>
        </div>
        <div class="card-title">Lihat Mahasiswa</div>
        <div class="card-desc">Lihat daftar mahasiswa bimbingan Anda.</div>
      </div>
      <a href="{{ route('dosen.mahasiswa.index') }}" class="btn-manage btn-gray">Lihat Daftar</a>
    </div>

  </div>

</div>
@endsection