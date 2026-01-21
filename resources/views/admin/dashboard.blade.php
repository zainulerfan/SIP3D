@extends('admin.layout.app')

@section('title', 'Administrator Dashboard')

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
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
    min-height: 210px;
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
  .icon-yellow { background: rgba(250, 204, 21, 0.2); color: #ca8a04; }
  .icon-red { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

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

  .btn-yellow { background: #facc15; color: #1e293b; }
  .btn-yellow:hover { background: #eab308; color: #1e293b; }

  .btn-red { background: #ef4444; color: #fff; }
  .btn-red:hover { background: #dc2626; color: #fff; }
</style>
@endpush

@section('content')
<div class="container-dashboard">

  <div class="dashboard-header">
    <h4>Administrator Dashboard</h4>
    <div class="welcome-text">
      Selamat datang, <span class="fw-semibold text-primary">{{ ucfirst($displayNameSafe ?? 'Admin') }}</span>
    </div>
  </div>

  <div class="card-grid">

    <div class="action-card">
      <div>
        <div class="card-icon icon-blue"><i class="bi bi-person-badge"></i></div>
        <div class="card-title">Kelola Dosen</div>
        <div class="card-desc">Tambah, edit, dan hapus data dosen.</div>
      </div>
      <a href="{{ route('dosen.index') }}" class="btn-manage btn-blue">Kelola Dosen</a>
    </div>

    <div class="action-card">
      <div>
        <div class="card-icon icon-green"><i class="bi bi-mortarboard"></i></div>
        <div class="card-title">Kelola Mahasiswa</div>
        <div class="card-desc">Tambah, edit, dan hapus data mahasiswa.</div>
      </div>
      <a href="{{ route('mahasiswa.index') }}" class="btn-manage btn-green">Kelola Mahasiswa</a>
    </div>

    <div class="action-card">
      <div>
        <div class="card-icon icon-gray"><i class="bi bi-journal-text"></i></div>
        <div class="card-title">Kelola Penelitian</div>
        <div class="card-desc">Monitor dan kelola data penelitian.</div>
      </div>
      <a href="{{ route('penelitian.index') }}" class="btn-manage btn-gray">Kelola Penelitian</a>
    </div>

    <div class="action-card">
      <div>
        <div class="card-icon icon-yellow"><i class="bi bi-people"></i></div>
        <div class="card-title">Kelola Pengabdian</div>
        <div class="card-desc">Monitor data pengabdian kepada masyarakat.</div>
      </div>
      <a href="{{ route('pengabdian.index') }}" class="btn-manage btn-yellow">Kelola Pengabdian</a>
    </div>

    <div class="action-card">
      <div>
        <div class="card-icon icon-red"><i class="bi bi-award"></i></div>
        <div class="card-title">TPK</div>
        <div class="card-desc">Mengelola data TPK dan prestasi.</div>
      </div>
      <a href="{{ route('prestasi.index') }}" class="btn-manage btn-red">Kelola TPK</a>
    </div>

    <div class="action-card">
      <div>
        <div class="card-icon icon-blue"><i class="bi bi-gear"></i></div>
        <div class="card-title">Kelola User</div>
        <div class="card-desc">Atur role dan akun pengguna aplikasi.</div>
      </div>
      <a href="{{ route('admin.users.index') }}" class="btn-manage btn-blue">Kelola User</a>
    </div>

  </div>

</div>
@endsection
