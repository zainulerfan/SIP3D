<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\PengabdianController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TPKController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

// LOGIN
Route::get('/login', [AuthController::class, 'pilihLogin'])->name('login');
Route::get('/login/pilih', [AuthController::class, 'pilihLogin'])->name('login.pilih');

Route::get('/login/admin', [AuthController::class, 'showAdminLoginForm'])->name('login.admin');
Route::post('/login/admin', [AuthController::class, 'adminLogin'])->name('login.admin.post');

Route::get('/login/dosen', [AuthController::class, 'showDosenLoginForm'])->name('login.dosen');
Route::post('/login/dosen', [AuthController::class, 'dosenLogin'])->name('login.dosen.post');

Route::get('/login/mahasiswa', [AuthController::class, 'showMahasiswaLoginForm'])->name('login.mahasiswa');
Route::post('/login/mahasiswa', [AuthController::class, 'mahasiswaLogin'])->name('login.mahasiswa.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| GOOGLE OAUTH
|--------------------------------------------------------------------------
*/
Route::get('/auth/google/redirect/{role?}', [GoogleController::class, 'redirect'])
    ->name('login.google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('login.google.callback');

Route::get('/auth/google/confirm-role', [GoogleController::class, 'confirmRole'])
    ->name('login.google.confirm_role');

Route::post('/auth/google/confirm-role/continue', [GoogleController::class, 'confirmRoleContinue'])
    ->name('login.google.confirm_role.continue');

Route::post('/auth/google/confirm-role/cancel', [GoogleController::class, 'confirmRoleCancel'])
    ->name('login.google.confirm_role.cancel');

// Google Drive OAuth callback (separated from login OAuth)
Route::get('/auth/google/drive/callback', [DosenController::class, 'handleGoogleDriveCallback'])
    ->name('dosen.google_drive_callback');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/dosen/dashboard', [DashboardController::class, 'dosenDashboard'])
        ->middleware('role:dosen')
        ->name('dosen.dashboard');

    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'dashboard'])
        ->middleware('role:mahasiswa')
        ->name('mahasiswa.dashboard');

    /*
    |--------------------------------------------------------------------------
    | DOSEN
    |--------------------------------------------------------------------------
    */
    Route::get('/dosen/export', [DosenController::class, 'export'])->name('dosen.export');

    /*
    |--------------------------------------------------------------------------
    | DOSEN - PENELITIAN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:dosen')
        ->prefix('dosen/penelitian')
        ->name('dosen.penelitian.')
        ->group(function () {
            Route::get('/', [PenelitianController::class, 'index'])->name('index');
            Route::get('/create', [PenelitianController::class, 'create'])->name('create');
            Route::post('/', [PenelitianController::class, 'store'])->name('store');
            Route::get('/{penelitian}', [PenelitianController::class, 'show'])->name('show');
            Route::get('/{penelitian}/edit', [PenelitianController::class, 'edit'])->name('edit');
            Route::put('/{penelitian}', [PenelitianController::class, 'update'])->name('update');
            Route::delete('/{penelitian}', [PenelitianController::class, 'destroy'])->name('destroy');
            Route::get('/export', [PenelitianController::class, 'export'])->name('export');
        });

    /*
    |--------------------------------------------------------------------------
    | DOSEN - PENGABDIAN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:dosen')
        ->prefix('dosen/pengabdian')
        ->name('dosen.pengabdian.')
        ->group(function () {
            Route::get('/', [PengabdianController::class, 'index'])->name('index');
            Route::get('/create', [PengabdianController::class, 'create'])->name('create');
            Route::post('/', [PengabdianController::class, 'store'])->name('store');
            Route::get('/{pengabdian}', [PengabdianController::class, 'show'])->name('show');
            Route::get('/{pengabdian}/edit', [PengabdianController::class, 'edit'])->name('edit');
            Route::put('/{pengabdian}', [PengabdianController::class, 'update'])->name('update');
            Route::delete('/{pengabdian}', [PengabdianController::class, 'destroy'])->name('destroy');
            Route::get('/export', [PengabdianController::class, 'export'])->name('export');
        });

    /*
    |--------------------------------------------------------------------------
    | DOSEN - MAHASISWA
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:dosen')
        ->prefix('dosen/mahasiswa')
        ->name('dosen.mahasiswa.')
        ->group(function () {
            Route::get('/', [MahasiswaController::class, 'index'])->name('index');
            Route::get('/{mahasiswa}', [MahasiswaController::class, 'show'])->name('show');
        });

    // Routes untuk Google Drive configuration
    Route::get('/dosen/{dosen}/google-drive', [DosenController::class, 'editGoogleDrive'])->name('dosen.edit_google_drive');
    Route::put('/dosen/{dosen}/google-drive', [DosenController::class, 'updateGoogleDrive'])->name('dosen.update_google_drive');
    Route::get('/dosen/{dosen}/google-drive/authorize', [DosenController::class, 'authorizeGoogleDrive'])->name('dosen.authorize_google_drive');
    Route::delete('/dosen/{dosen}/google-drive/revoke', [DosenController::class, 'revokeGoogleDrive'])->name('dosen.revoke_google_drive');

    // Resource untuk dosen (harus setelah route dosen/* yang spesifik)
    Route::resource('dosen', DosenController::class);

    /*
    |--------------------------------------------------------------------------
    | PENELITIAN
    |--------------------------------------------------------------------------
    */
    Route::get('/penelitian/export', [PenelitianController::class, 'export'])->name('penelitian.export');
    Route::get('/penelitian/export.csv', [PenelitianController::class, 'exportCsv'])->name('penelitian.export.csv');
    Route::resource('penelitian', PenelitianController::class);

    /*
    |--------------------------------------------------------------------------
    | PENGABDIAN (RESOURCE UTAMA)
    |--------------------------------------------------------------------------
    */
    Route::get('/pengabdian/export', [PengabdianController::class, 'export'])->name('pengabdian.export');
    Route::get('/pengabdian/export.csv', [PengabdianController::class, 'exportCsv'])->name('pengabdian.export.csv');
    Route::resource('pengabdian', PengabdianController::class);

    /*
    |--------------------------------------------------------------------------
    | MAHASISWA
    |--------------------------------------------------------------------------
    */
    Route::resource('mahasiswa', MahasiswaController::class);

    Route::get(
        '/mahasiswa/dokumentasi/{penelitian}/create',
        [MahasiswaController::class, 'createDokumentasi']
    )
        ->name('mahasiswa.dokumentasi.create');

    Route::post(
        '/mahasiswa/dokumentasi/{penelitian}',
        [MahasiswaController::class, 'storeDokumentasi']
    )
        ->name('mahasiswa.dokumentasi.store');

    Route::get(
        '/mahasiswa/dokumentasi-pengabdian/{pengabdian}/create',
        [MahasiswaController::class, 'createDokumentasiPengabdian']
    )
        ->name('mahasiswa.dokumentasi_pengabdian.create');

    Route::post(
        '/mahasiswa/dokumentasi-pengabdian/{pengabdian}',
        [MahasiswaController::class, 'storeDokumentasiPengabdian']
    )
        ->name('mahasiswa.dokumentasi_pengabdian.store');

    /*
    |--------------------------------------------------------------------------
    | TPK (ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')
        ->prefix('admin/tpk')
        ->name('tpk.')
        ->group(function () {

            Route::get('/', [TPKController::class, 'index'])->name('index');
            Route::get('/export', [TPKController::class, 'exportCsv'])->name('export');

            Route::resource('alternatif', AlternatifController::class)->except('show');
            Route::resource('kriteria', KriteriaController::class)->except('show');


            Route::post(
                '/kriteria/update-bobot',
                [KriteriaController::class, 'updateBobot']
            )
                ->name('kriteria.updateBobot');

            Route::get(
                '/kriteria/hitung',
                [KriteriaController::class, 'hitung']
            )
                ->name('kriteria.hitung');
        });

    /*
    |--------------------------------------------------------------------------
    | KELOLA USER (ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')
        ->prefix('admin/users')
        ->name('admin.users.')
        ->group(function () {
            Route::get('/', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [\App\Http\Controllers\AdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | LEGACY PRESTASI (Legacy Support - Redirect)
    |--------------------------------------------------------------------------
    */
    Route::get('/prestasi', fn() => redirect()->route('tpk.index'))->name('prestasi.index');
    Route::get('/prestasi/create', fn() => redirect()->route('tpk.alternatif.create'))->name('prestasi.create');
});
