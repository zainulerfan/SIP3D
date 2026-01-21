@extends('layouts.app')

@section('title', 'Konfigurasi Google Drive - ' . $dosen->nama)

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-2xl mx-auto">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold">🔧 Konfigurasi Google Drive</h2>
                <p class="text-gray-600 text-sm mt-1">Hubungkan Google Drive Anda untuk menerima upload dokumentasi mahasiswa</p>
            </div>
            <a href="{{ route('dosen.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition inline-flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- Info Dosen --}}
        <div class="bg-white shadow-md rounded-2xl p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-2xl font-bold">
                    {{ strtoupper(substr($dosen->nama, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-semibold">{{ $dosen->nama }}</h3>
                    <p class="text-gray-600 text-sm">NIDN: {{ $dosen->nidn }}</p>
                    <p class="text-gray-600 text-sm">Email: {{ $dosen->email }}</p>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 border border-green-300">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-100 border border-red-300">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-100 border border-red-300">
            <div class="font-semibold text-red-800 mb-2">❌ Ada Error:</div>
            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Connection Status Card --}}
        <div class="bg-white shadow-md rounded-2xl p-8 mb-6">
            <h3 class="text-xl font-semibold mb-4">📡 Status Koneksi Google Drive</h3>

            @if ($dosen->hasGoogleDriveConnected())
            {{-- Connected State --}}
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
                <div class="flex items-center gap-3">
                    <div class="text-green-600 text-3xl">✅</div>
                    <div>
                        <p class="text-green-900 font-semibold">Google Drive Terhubung!</p>
                        <p class="text-green-700 text-sm mt-1">
                            Mahasiswa dapat mengupload dokumentasi ke akun Google Drive Anda.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Folder ID Form --}}
            <form action="{{ route('dosen.update_google_drive', $dosen->id) }}" method="POST" class="mb-6">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-lg font-semibold mb-2">
                        📁 Target Folder ID (Opsional)
                    </label>
                    <input
                        type="text"
                        name="google_drive_folder_id"
                        value="{{ old('google_drive_folder_id', $dosen->google_drive_folder_id) }}"
                        class="w-full border-2 rounded-lg p-3 {{ $errors->has('google_drive_folder_id') ? 'border-red-500' : 'border-gray-300' }}"
                        placeholder="Kosongkan untuk root folder, atau masukkan Folder ID">
                    <p class="text-gray-600 text-sm mt-2">
                        Jika dikosongkan, file akan diupload ke root folder Google Drive Anda.
                        <br>Untuk mendapatkan Folder ID, buka folder di Google Drive dan copy ID dari URL.
                    </p>
                </div>

                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition font-medium">
                    💾 Simpan Folder ID
                </button>
            </form>

            {{-- Revoke Access --}}
            <div class="pt-6 border-t">
                <h4 class="font-semibold text-gray-800 mb-2">🔌 Putuskan Koneksi</h4>
                <p class="text-gray-600 text-sm mb-4">
                    Jika Anda ingin memutuskan koneksi Google Drive, mahasiswa tidak akan bisa upload ke akun Anda.
                </p>
                <form action="{{ route('dosen.revoke_google_drive', $dosen->id) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin memutuskan koneksi Google Drive?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition font-medium">
                        🔓 Putuskan Koneksi
                    </button>
                </form>
            </div>

            @else
            {{-- Not Connected State --}}
            <div class="mb-6 p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                <div class="flex items-center gap-3">
                    <div class="text-yellow-600 text-3xl">⚠️</div>
                    <div>
                        <p class="text-yellow-900 font-semibold">Google Drive Belum Terhubung</p>
                        <p class="text-yellow-700 text-sm mt-1">
                            Mahasiswa tidak dapat mengupload dokumentasi sampai Anda menghubungkan Google Drive.
                        </p>
                    </div>
                </div>
            </div>

            <p class="text-gray-700 mb-4">
                Dengan menghubungkan Google Drive Anda, dokumentasi yang diupload mahasiswa akan langsung masuk ke akun Google Drive Anda.
            </p>

            <a href="{{ route('dosen.authorize_google_drive', $dosen->id) }}"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition font-medium text-lg">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                Hubungkan Google Drive
            </a>
            @endif
        </div>

        {{-- How It Works --}}
        <div class="bg-white shadow-md rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">ℹ️ Bagaimana Cara Kerjanya?</h3>

            <div class="space-y-4">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">1</div>
                    <div>
                        <p class="font-semibold text-gray-900">Hubungkan Akun</p>
                        <p class="text-gray-600 text-sm">Klik tombol "Hubungkan Google Drive" dan login dengan akun Google Anda.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">2</div>
                    <div>
                        <p class="font-semibold text-gray-900">Izinkan Akses</p>
                        <p class="text-gray-600 text-sm">Berikan izin untuk upload file ke Google Drive Anda.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">3</div>
                    <div>
                        <p class="font-semibold text-gray-900">Mahasiswa Upload</p>
                        <p class="text-gray-600 text-sm">Mahasiswa dapat mengupload dokumentasi yang langsung masuk ke Google Drive Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FAQ --}}
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h3 class="text-lg font-semibold mb-4">❓ Pertanyaan Umum</h3>

            <div class="space-y-4">
                <div>
                    <p class="font-semibold text-gray-900">Apakah data saya aman?</p>
                    <p class="text-gray-700 text-sm mt-1">Ya. Sistem hanya meminta izin untuk membuat file baru. Tidak ada akses ke file atau folder lain yang sudah ada.</p>
                </div>

                <div class="pt-4 border-t">
                    <p class="font-semibold text-gray-900">Bagaimana jika saya ingin mengganti folder?</p>
                    <p class="text-gray-700 text-sm mt-1">Cukup update Folder ID di form di atas. File baru akan masuk ke folder yang baru.</p>
                </div>

                <div class="pt-4 border-t">
                    <p class="font-semibold text-gray-900">Siapa yang bisa melihat file di Google Drive saya?</p>
                    <p class="text-gray-700 text-sm mt-1">Hanya Anda yang memiliki akses penuh. Anda bisa berbagi folder dengan dosen lain jika diperlukan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection