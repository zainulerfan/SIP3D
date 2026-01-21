@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@push('styles')
<style>
  body {
    background-color: #f4f6f9;
  }

  .page-wrapper {
    max-width: 1100px;
    margin: 60px auto 100px;
    padding: 0 16px;
  }

  .card-box {
    background: #ffffff;
    border-radius: 16px;
    padding: 28px 26px 24px;
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.05);
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
  }

  .card-header h4 {
    font-weight: 700;
    margin: 0;
  }

  .header-actions {
    display: flex;
    gap: 10px;
  }

  .btn-back {
    background: #6b7280;
    color: #fff;
    border-radius: 10px;
    padding: 8px 16px;
    text-decoration: none;
    font-weight: 600;
  }

  .btn-back:hover {
    background: #4b5563;
    color: #fff;
  }

  .btn-add {
    background: #2563eb;
    color: #fff;
    border-radius: 10px;
    padding: 8px 18px;
    text-decoration: none;
    font-weight: 600;
  }

  .btn-add:hover {
    background: #1d4ed8;
    color: #fff;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
  }

  thead tr {
    background: #f3f4f6;
  }

  th, td {
    padding: 10px 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    vertical-align: middle;
  }

  th {
    font-weight: 700;
    color: #374151;
  }

  .badge-active {
    background: #dcfce7;
    color: #16a34a;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
  }

  .aksi a {
    font-weight: 600;
    margin: 0 6px;
    text-decoration: none;
  }

  .aksi .edit {
    color: #2563eb;
  }

  .aksi .hapus {
    color: #ef4444;
  }

  .aksi .edit:hover { color: #1d4ed8; }
  .aksi .hapus:hover { color: #dc2626; }
</style>
@endpush

@section('content')
<div class="page-wrapper">

  <div class="card-box">

    <div class="card-header">
      <h4>Data Mahasiswa</h4>
      <div class="header-actions">
        <a href="{{ url()->previous() }}" class="btn-back">← Kembali</a>
        <a href="{{ route('mahasiswa.create') }}" class="btn-add">+ Tambah Mahasiswa</a>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>NIM</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Prodi</th>
          <th>Angkatan</th>
          <th>Status</th>
          <th style="text-align:center;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>2023001</td>
          <td>Rida</td>
          <td>mahasiswa1@example.com</td>
          <td>Teknik Informatika</td>
          <td>2023</td>
          <td><span class="badge-active">Aktif</span></td>
          <td class="aksi" style="text-align:center;">
            <a href="#" class="edit">Edit</a>
            <a href="#" class="hapus">Hapus</a>
          </td>
        </tr>

        {{-- Loop data mahasiswa di sini --}}
        {{-- @foreach($mahasiswas as $m)
        <tr>
          <td>{{ $m->nim }}</td>
          <td>{{ $m->nama }}</td>
          <td>{{ $m->email }}</td>
          <td>{{ $m->prodi }}</td>
          <td>{{ $m->angkatan }}</td>
          <td><span class="badge-active">{{ $m->status }}</span></td>
          <td class="aksi" style="text-align:center;">
            <a href="{{ route('mahasiswa.edit', $m->id) }}" class="edit">Edit</a>
            <a href="#" class="hapus">Hapus</a>
          </td>
        </tr>
        @endforeach --}}
      </tbody>
    </table>

  </div>
</div>
@endsection