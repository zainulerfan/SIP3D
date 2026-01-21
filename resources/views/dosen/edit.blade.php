@extends('layouts.app')

@section('title', 'Edit Dosen')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-2xl p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Edit Data Dosen</h2>
            <a href="{{ route('dosen.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition inline-flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- Tampilkan error validasi --}}
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dosen.update', $dosen->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="block mb-1 font-medium">NIDN</label>
                <input type="text" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $dosen->nama) }}" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', $dosen->email) }}" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Fakultas</label>
                <input type="text" name="fakultas" value="{{ old('fakultas', $dosen->fakultas) }}" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Program Studi</label>
                <input type="text" name="prodi" value="{{ old('prodi', $dosen->prodi) }}" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Jabatan Akademik</label>
                <input type="text" name="jabatan" value="{{ old('jabatan', $dosen->jabatan) }}" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Tahun Masuk</label>
                <input type="number" name="tahun" value="{{ old('tahun', $dosen->tahun) }}" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Status</label>
                <select name="status" class="w-full border rounded-lg p-2" required>
                    <option value="">Pilih status</option>
                    <option value="Aktif" {{ old('status', $dosen->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Nonaktif" {{ old('status', $dosen->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Google Drive Folder ID (Opsional)</label>
                <input type="text" name="google_drive_folder_id" value="{{ old('google_drive_folder_id', $dosen->google_drive_folder_id) }}" class="w-full border rounded-lg p-2" placeholder="Contoh: 1A2B3C4D5E6F7G8H">
                <small class="text-gray-500">ID folder Google Drive untuk upload dokumentasi mahasiswa. Bisa diisi nanti di halaman konfigurasi terpisah.</small>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('dosen.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
