<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    /**
     * Redirect ke Google (simpan intent role di session bila ada)
     * Contoh URL:
     * /auth/google/redirect/admin
     * /auth/google/redirect/dosen
     */
    public function redirect(Request $request, $role = null)
    {
        if ($role) {
            $request->session()->put('login_intent', $role);
        } else {
            $request->session()->forget('login_intent');
        }

        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Callback dari Google
     */
    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login.pilih')
                ->withErrors(['google' => 'Gagal login dengan Google.']);
        }

        // Ambil intent login
        $intent = $request->session()->pull('login_intent');

        // Cari user berdasarkan email
        $user = User::where('email', $googleUser->getEmail())->first();

        /**
         * =========================================
         * JIKA USER SUDAH ADA
         * =========================================
         */
        if ($user) {

            // Jika ada intent dan tidak sama dengan role user → konfirmasi
            if ($intent && $user->role !== $intent) {

                $request->session()->put('google_temp_user', [
                    'email' => $user->email,
                ]);

                $request->session()->put('current_role', $user->role);
                $request->session()->put('intent_role', $intent);

                return redirect()->route('login.google.confirm_role');
            }

            // 🔥 FIX UTAMA: pastikan active_role selalu di-set
            Auth::login($user);

            // Sync Profile
            $this->syncProfileData($user, $user->role);

            session(['active_role' => $user->role]);

            return $this->redirectByActiveRole();
        }

        /**
         * =========================================
         * JIKA USER BARU
         * =========================================
         */
        // KEAMANAN: Hapus logika $intent ?? 'mahasiswa'
        // Semua user baru via Google Login WAJIB jadi 'mahasiswa' agar tidak ada yang iseng jadi Admin.
        $roleToAssign = 'mahasiswa';

        // Jika user mencoba login lewat form admin/dosen, beri peringatan
        if ($intent && $intent !== 'mahasiswa') {
            session()->flash('warning', 'Demi keamanan, pendaftaran akun baru otomatis ditetapkan sebagai <b>Mahasiswa</b>. Jika Anda Dosen/Admin, silakan hubungi Administrator.');
        }

        $user = User::create([
            'name'     => $googleUser->getName(),
            'email'    => $googleUser->getEmail(),
            'password' => bcrypt(\Illuminate\Support\Str::random(24)),
            'role'     => $roleToAssign,
        ]);

        Auth::login($user);

        // Sync Profile
        $this->syncProfileData($user, $user->role);

        session(['active_role' => $user->role]);

        return $this->redirectByActiveRole();
    }

    /**
     * Halaman konfirmasi role
     */
    public function confirmRole(Request $request)
    {
        $currentRole = $request->session()->get('current_role');
        $intent = $request->session()->get('intent_role');

        if (!$currentRole || !$intent) {
            return redirect()->route('login.pilih');
        }

        return view('auth.confirm_role', compact('currentRole', 'intent'));
    }

    /**
     * Lanjutkan setelah user memilih role
     */
    public function confirmRoleContinue(Request $request)
    {
        $temp = $request->session()->get('google_temp_user');
        $currentRole = $request->session()->get('current_role');
        $intent = $request->session()->get('intent_role');

        if (!$temp || !isset($temp['email'])) {
            return redirect()->route('login.pilih')
                ->withErrors(['google' => 'Sesi login tidak valid.']);
        }

        $user = User::where('email', $temp['email'])->first();
        if (!$user) {
            return redirect()->route('login.pilih')
                ->withErrors(['google' => 'Akun tidak ditemukan.']);
        }

        Auth::login($user);

        // BLOCKER: Tidak boleh ganti role via Login Google
        // User minta: "kan gaboleh klo ga dari admin"
        // Jadi opsi 'intent' untuk update role DIHAPUS.

        // Jika user memaksa pilih intent (misal via inspect element), abaikan dan tetap pakai current role
        // Atau return error.

        $finalRole = $currentRole ?? $user->role;

        // Sync Profile untuk role saat ini
        $this->syncProfileData($user, $finalRole);

        session(['active_role' => $finalRole]);

        // Bersihkan session sementara
        $request->session()->forget([
            'google_temp_user',
            'current_role',
            'intent_role',
            'login_intent',
        ]);

        return $this->redirectByActiveRole();
    }

    /**
     * Batalkan konfirmasi
     */
    public function confirmRoleCancel(Request $request)
    {
        $request->session()->forget([
            'google_temp_user',
            'current_role',
            'intent_role',
            'login_intent',
        ]);

        return redirect()->route('login.pilih');
    }

    /**
     * =========================================
     * 🔥 HELPER FINAL (ANTI SALAH ROLE)
     * =========================================
     */
    protected function redirectByActiveRole()
    {
        $role = session('active_role', Auth::user()->role);

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'dosen':
                return redirect()->route('dosen.dashboard');

            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
        }
    }

    /**
     * Pastikan data profile (Dosen/Mahasiswa) ada saat user login/ganti role
     */
    protected function syncProfileData($user, $role)
    {
        if ($role === 'dosen') {
            \App\Models\Dosen::firstOrCreate(
                ['email' => $user->email],
                [
                    'nama' => $user->name,
                    'nidn' => '-', // Dummy, user harus update profil nanti
                    'jabatan' => '-',
                    'fakultas' => '-',
                    'prodi' => '-',
                    'tahun' => date('Y'),
                    'status' => 'Aktif',
                ]
            );
        } elseif ($role === 'mahasiswa') {
            // Cek apakah tabel mahasiswas punya user_id (tergantung migrasi)
            // Di Seeder tadi ada user_id, tapi di model Mahasiswa juga ada user_id.
            // Kita pakai email sebagai kunci aman jika user_id nullable/tidak konsisten.

            // Cek struktur tabel mahasiswa dari seeder: punya user_id & email.
            // AUTOMATIC NIM & ANGKATAN EXTRACTION
            $nim = '-';
            $angkatan = date('Y');

            $emailLower = strtolower(trim($user->email));
            $domain = '@mhs.politala.ac.id';

            // Cek jika email berakhiran domain kampus (case-insensitive)
            if (str_ends_with($emailLower, $domain)) {
                // Ambil bagian depan sebelum @
                $parts = explode('@', $emailLower);
                $nimCandidate = $parts[0];

                // Jika numeric, anggap itu NIM
                if (is_numeric($nimCandidate)) {
                    $nim = $nimCandidate;

                    // Ambil 2 digit pertama sebagai tahun (contoh 24xxxx -> 2024)
                    if (strlen($nim) >= 2) {
                        $prefix = substr($nim, 0, 2);
                        $angkatan = '20' . $prefix;
                    }
                }
            }

            $data = [
                'nama' => $user->name,
                'email' => $user->email,
                'nim' => $nim,
                'fakultas' => '-',
                'prodi' => '-',
                'angkatan' => $angkatan,
                'status' => 'Aktif',
            ];

            $data['user_id'] = $user->id;

            // Logika baru: Cek dulu datanya
            $mhs = \App\Models\Mahasiswa::where('email', $user->email)->first();

            if ($mhs) {
                // Jika mahasiswa sudah ada, update data
                $updates = [];

                // CRITICAL: Update user_id agar terhubung ke akun baru (jika akun lama dihapus tapi data mhs tertinggal)
                $updates['user_id'] = $user->id;

                if ($nim !== '-') $updates['nim'] = $nim;
                if ($angkatan !== '-') $updates['angkatan'] = $angkatan;

                // Jangan override prodi/fakultas jika sudah ada isinya
                if ($mhs->prodi == '-' && $data['prodi'] != '-') $updates['prodi'] = $data['prodi'];

                if (!empty($updates)) {
                    $mhs->update($updates);
                }
            } else {
                // Jika belum ada, buat baru
                \App\Models\Mahasiswa::create($data);
            }
        }
    }
}
