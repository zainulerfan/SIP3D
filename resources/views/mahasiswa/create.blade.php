@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-2xl p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Tambah Mahasiswa</h2>
            <a href="{{ route('mahasiswa.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition inline-flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- Tampilkan pesan error validasi --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Tambah Mahasiswa --}}
        <form action="{{ route('mahasiswa.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block mb-1 font-medium">NIM</label>
                <input type="text" name="nim" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="nama" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded-lg p-2" required placeholder="Minimal 6 karakter">
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Fakultas</label>
                <input type="text" name="fakultas" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Program Studi</label>
                <input type="text" name="prodi" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Angkatan</label>
                <input type="number" name="angkatan" class="w-full border rounded-lg p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Status</label>
                <select name="status" class="w-full border rounded-lg p-2" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('mahasiswa.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection