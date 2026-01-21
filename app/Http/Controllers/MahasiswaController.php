<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Dokumentasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    // =============================
    // 🏠 Dashboard Mahasiswa
    // =============================
    public function dashboard()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        $penelitians = Penelitian::when($mahasiswa, function ($q) use ($mahasiswa) {
            $q->where('mahasiswa_id', $mahasiswa->id);
        })
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $pengabdians = Pengabdian::when($mahasiswa, function ($q) use ($mahasiswa) {
            $q->where('mahasiswa_id', $mahasiswa->id);
        })
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $fotoCount  = Dokumentasi::where('jenis', 'foto')->count();
        $videoCount = Dokumentasi::where('jenis', 'video')->count();
        $total      = $fotoCount + $videoCount;

        return view('mahasiswa.dashboard', compact(
            'fotoCount',
            'videoCount',
            'total',
            'penelitians',
            'pengabdians'
        ));
    }

    // =============================
    // 📤 Upload Dokumentasi
    // =============================
    public function createDokumentasi(Penelitian $penelitian)
    {
        return view('mahasiswa.upload_dokumentasi', compact('penelitian'));
    }

    public function storeDokumentasi(Request $request, Penelitian $penelitian)
    {
        $data = $request->validate([
            'jenis'      => 'required|in:foto,video',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:20480',
            'drive_link' => 'nullable|url|max:255',
        ]);

        if (!$request->hasFile('file') && !$request->filled('drive_link')) {
            return back()->withErrors([
                'file' => 'Silakan upload file atau isi link Google Drive.'
            ])->withInput();
        }

        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            return back()->withErrors([
                'mahasiswa' => 'Data mahasiswa untuk akun ini belum terdaftar.'
            ]);
        }

        $path = null;
        $driveLink = $data['drive_link'] ?? null;

        if ($request->hasFile('file')) {
            try {
                // Get Google Drive folder from penelitian
                $folderLink = $penelitian->google_drive_folder;

                if (!$folderLink) {
                    return back()->withErrors([
                        'file' => 'Penelitian ini belum memiliki folder Google Drive. Hubungi dosen untuk mengatur folder.'
                    ])->withInput();
                }

                // Upload to Google Drive folder
                $uploadResult = $this->uploadToGoogleDrive($request->file('file'), $folderLink);
                $path = $uploadResult['path'];
                $driveLink = $uploadResult['link'];
            } catch (\Exception $e) {
                return back()->withErrors([
                    'file' => 'Gagal mengupload ke Google Drive. Error: ' . $e->getMessage()
                ])->withInput();
            }
        }

        Dokumentasi::create([
            'penelitian_id' => $penelitian->id,
            'mahasiswa_id'  => $mahasiswa->id,
            'jenis'         => $data['jenis'],
            'file_path'     => $path,
            'drive_link'    => $driveLink,
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', '✅ Dokumentasi berhasil diupload ke Google Drive!');
    }

    // =============================
    // 📤 Upload Dokumentasi Pengabdian
    // =============================
    public function createDokumentasiPengabdian(Pengabdian $pengabdian)
    {
        return view('mahasiswa.upload_dokumentasi_pengabdian', compact('pengabdian'));
    }

    public function storeDokumentasiPengabdian(Request $request, Pengabdian $pengabdian)
    {
        $data = $request->validate([
            'jenis'      => 'required|in:foto,video',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:20480',
            'drive_link' => 'nullable|url|max:255',
        ]);

        if (!$request->hasFile('file') && !$request->filled('drive_link')) {
            return back()->withErrors([
                'file' => 'Silakan upload file atau isi link Google Drive.'
            ])->withInput();
        }

        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        if (!$mahasiswa) {
            return back()->withErrors([
                'mahasiswa' => 'Data mahasiswa untuk akun ini belum terdaftar.'
            ]);
        }

        $path = null;
        $driveLink = $data['drive_link'] ?? null;

        if ($request->hasFile('file')) {
            try {
                // Get Google Drive folder from pengabdian
                $folderLink = $pengabdian->google_drive_folder;

                if (!$folderLink) {
                    return back()->withErrors([
                        'file' => 'Pengabdian ini belum memiliki folder Google Drive. Hubungi dosen untuk mengatur folder.'
                    ])->withInput();
                }

                // Upload to Google Drive folder
                $uploadResult = $this->uploadToGoogleDrive($request->file('file'), $folderLink);
                $path = $uploadResult['path'];
                $driveLink = $uploadResult['link'];
            } catch (\Exception $e) {
                return back()->withErrors([
                    'file' => 'Gagal mengupload ke Google Drive. Error: ' . $e->getMessage()
                ])->withInput();
            }
        }

        Dokumentasi::create([
            'pengabdian_id' => $pengabdian->id,
            'mahasiswa_id'  => $mahasiswa->id,
            'jenis'         => $data['jenis'],
            'file_path'     => $path,
            'drive_link'    => $driveLink,
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', '✅ Dokumentasi Pengabdian berhasil diupload ke Google Drive!');
    }

    // =============================
    // 📋 CRUD Mahasiswa
    // =============================
    public function index()
    {
        $user = Auth::user();

        // Tampilkan semua mahasiswa untuk dosen dan admin
        // Dosen bisa melihat semua mahasiswa yang terdaftar di sistem
        $mahasiswa = Mahasiswa::latest()->get();

        return view('mahasiswa.index', compact('mahasiswa'));
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    // 🔥🔥🔥 INI BAGIAN YANG DIPERBAIKI 🔥🔥🔥
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'                  => 'required|unique:mahasiswas',
            'nama'                 => 'required|string|max:100',
            'email'                => 'required|email|unique:users',
            'password'             => 'required|string|min:6|confirmed',
            'fakultas'             => 'required|string|max:100',
            'prodi'                => 'required|string|max:100',
            'angkatan'             => 'required|digits:4',
            'status'               => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Create user account untuk mahasiswa
        $user = User::create([
            'name'     => $validated['nama'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role'     => 'mahasiswa',
        ]);

        // Create mahasiswa record
        Mahasiswa::create([
            'user_id'  => $user->id,
            'nim'      => $validated['nim'],
            'nama'     => $validated['nama'],
            'email'    => $validated['email'],
            'fakultas' => $validated['fakultas'],
            'prodi'    => $validated['prodi'],
            'angkatan' => $validated['angkatan'],
            'status'   => $validated['status'],
        ]);

        return redirect()->route('mahasiswa.index')
            ->with('success', '✅ Data mahasiswa berhasil ditambahkan! Email: ' . $validated['email']);
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nim'                  => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama'                 => 'required|string|max:100',
            'email'                => 'required|email|unique:users,email,' . $mahasiswa->user_id,
            'password'             => 'nullable|string|min:6|confirmed',
            'fakultas'             => 'required|string|max:100',
            'prodi'                => 'required|string|max:100',
            'angkatan'             => 'required|digits:4',
            'status'               => 'required|in:Aktif,Tidak Aktif',
        ]);

        // Update user account jika ada
        if ($mahasiswa->user) {
            $userData = [
                'name'  => $validated['nama'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $userData['password'] = bcrypt($validated['password']);
            }

            $mahasiswa->user->update($userData);
        }

        // Update mahasiswa record
        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', '✅ Data mahasiswa berhasil diperbarui!');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', '🗑️ Data mahasiswa berhasil dihapus!');
    }
    private function uploadToGoogleDrive($file, $folderId = null)
    {
        // 1. Setup Google Client
        $clientId = config('services.google_drive.client_id');
        $clientSecret = config('services.google_drive.client_secret');
        $refreshToken = config('services.google_drive.refresh_token');
        $folderEnv = $folderId ?? config('services.google_drive.folder');

        if (!$clientId || !$clientSecret || !$refreshToken) {
            throw new \Exception('Google Drive credentials not configured.');
        }

        // Parse Folder ID if URL
        $parsedFolderId = $folderEnv;
        if (filter_var($folderEnv, FILTER_VALIDATE_URL)) {
            // Parse URL to get path
            $parsedUrl = parse_url($folderEnv);
            $path = $parsedUrl['path'] ?? '';
            $parts = explode('/', trim($path, '/'));
            $parsedFolderId = end($parts);

            // Also try to extract from query if it's a different format
            if (empty($parsedFolderId) && isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $query);
                if (isset($query['id'])) {
                    $parsedFolderId = $query['id'];
                }
            }
        }

        // Remove any trailing query parameters (like ?usp=sharing)
        if (strpos($parsedFolderId, '?') !== false) {
            $parsedFolderId = explode('?', $parsedFolderId)[0];
        }

        $client = new \Google\Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->refreshToken($refreshToken);

        $service = new \Google\Service\Drive($client);

        $filename = $file->hashName();
        $content = file_get_contents($file->getRealPath());
        $mimeType = $file->getMimeType();

        // 2. Metadata File
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $filename,
            'parents' => [$parsedFolderId]
        ]);

        // 3. Upload File
        $createdFile = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id, webViewLink'
        ]);

        return [
            'path' => $createdFile->id,
            'link' => $createdFile->webViewLink
        ];
    }
}
