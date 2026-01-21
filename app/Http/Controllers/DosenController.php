<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Exports\DosenExport;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    // Dashboard (opsional)
    public function dashboard()
    {
        $dosens = Dosen::all();
        return view('dosen.dashboard', compact('dosens'));
    }

    // Index: tampilkan data
    public function index()
    {
        // gunakan paginate jika dataset besar: Dosen::orderBy('nama')->paginate(10)
        $dosen = Dosen::orderBy('nama')->get();
        return view('dosen.index', compact('dosen'));
    }

    // Form tambah
    public function create()
    {
        $dosen = new Dosen();
        $button = 'Simpan';
        return view('dosen.create', compact('dosen', 'button'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|unique:dosens,nidn',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email',
            'fakultas' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tahun' => 'required|integer',
            'status' => 'required|string',
        ]);

        Dosen::create($validated);

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil ditambahkan.');
    }

    // Form edit
    public function edit(Dosen $dosen)
    {
        $button = 'Update';
        return view('dosen.create', compact('dosen', 'button')); // reuse form
    }

    // Update data
    public function update(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'nidn' => 'required|unique:dosens,nidn,' . $dosen->id,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'fakultas' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tahun' => 'required|integer',
            'status' => 'required|string',
            'google_drive_folder_id' => 'nullable|string|max:255',
        ]);

        $dosen->update($validated);

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui.');
    }

    // Hapus data
    public function destroy(Dosen $dosen)
    {
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil dihapus.');
    }

    // Export Excel
    public function export()
    {
        $fileName = 'Data_Dosen_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new DosenExport, $fileName);
    }

    // ========================================
    // 🔧 Google Drive OAuth Configuration
    // ========================================

    /**
     * Show Google Drive configuration page
     */
    public function editGoogleDrive(Dosen $dosen)
    {
        return view('dosen.edit_google_drive', compact('dosen'));
    }

    /**
     * Update Google Drive folder ID only
     */
    public function updateGoogleDrive(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'google_drive_folder_id' => 'nullable|string|max:255',
        ]);

        $dosen->update($validated);

        return redirect()->route('dosen.edit_google_drive', $dosen->id)
            ->with('success', '✅ Google Drive folder berhasil diatur!');
    }

    /**
     * Redirect to Google OAuth for Google Drive authorization
     */
    public function authorizeGoogleDrive(Dosen $dosen)
    {
        // Store dosen ID in session for callback
        session(['google_drive_dosen_id' => $dosen->id]);

        $client = new \Google\Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('app.url') . '/auth/google/drive/callback');
        $client->addScope(\Google\Service\Drive::DRIVE_FILE);
        $client->setAccessType('offline');
        $client->setPrompt('consent'); // Force consent to get refresh token

        return redirect($client->createAuthUrl());
    }

    /**
     * Handle Google Drive OAuth callback
     */
    public function handleGoogleDriveCallback(Request $request)
    {
        $dosenId = session('google_drive_dosen_id');

        if (!$dosenId) {
            return redirect()->route('dosen.index')
                ->with('error', '❌ Session expired. Silakan coba lagi.');
        }

        $dosen = Dosen::find($dosenId);

        if (!$dosen) {
            return redirect()->route('dosen.index')
                ->with('error', '❌ Dosen tidak ditemukan.');
        }

        if ($request->has('error')) {
            return redirect()->route('dosen.edit_google_drive', $dosen->id)
                ->with('error', '❌ Anda membatalkan proses otorisasi.');
        }

        if (!$request->has('code')) {
            return redirect()->route('dosen.edit_google_drive', $dosen->id)
                ->with('error', '❌ Authorization code tidak ditemukan.');
        }

        try {
            $client = new \Google\Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->setRedirectUri(config('app.url') . '/auth/google/drive/callback');

            $tokens = $client->fetchAccessTokenWithAuthCode($request->code);

            if (isset($tokens['error'])) {
                return redirect()->route('dosen.edit_google_drive', $dosen->id)
                    ->with('error', '❌ Gagal mendapatkan token: ' . ($tokens['error_description'] ?? $tokens['error']));
            }

            // Save tokens to dosen
            $dosen->update([
                'google_drive_access_token' => $tokens['access_token'],
                'google_drive_refresh_token' => $tokens['refresh_token'] ?? $dosen->google_drive_refresh_token,
                'google_drive_token_expires_at' => now()->addSeconds($tokens['expires_in'] ?? 3600),
            ]);

            // Clean up session
            session()->forget('google_drive_dosen_id');

            return redirect()->route('dosen.edit_google_drive', $dosen->id)
                ->with('success', '✅ Google Drive berhasil terhubung! Sekarang mahasiswa dapat mengupload dokumentasi ke akun Google Drive Anda.');
        } catch (\Exception $e) {
            return redirect()->route('dosen.edit_google_drive', $dosen->id)
                ->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    /**
     * Revoke Google Drive access
     */
    public function revokeGoogleDrive(Dosen $dosen)
    {
        try {
            if ($dosen->google_drive_access_token) {
                $client = new \Google\Client();
                $client->revokeToken($dosen->google_drive_access_token);
            }
        } catch (\Exception $e) {
            // Ignore revoke errors, just clear local tokens
        }

        $dosen->update([
            'google_drive_access_token' => null,
            'google_drive_refresh_token' => null,
            'google_drive_token_expires_at' => null,
            'google_drive_folder_id' => null,
        ]);

        return redirect()->route('dosen.edit_google_drive', $dosen->id)
            ->with('success', '✅ Akses Google Drive berhasil dicabut.');
    }
}
