<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nidn',
        'nama',
        'email',
        'fakultas',
        'prodi',
        'jabatan',
        'tahun',
        'status',
        'password',
        'google_drive_folder_id',
        'google_drive_access_token',
        'google_drive_refresh_token',
        'google_drive_token_expires_at',
        // TPK columns
        'skor_sinta',
        'skor_sinta_3yr',
        'jumlah_buku',
        'jumlah_hibah',
        'publikasi_scholar',
    ];

    protected $casts = [
        'google_drive_token_expires_at' => 'datetime',
    ];

    protected $hidden = [
        'google_drive_access_token',
        'google_drive_refresh_token',
    ];

    /**
     * Get all mahasiswa guided by this dosen
     */
    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_id');
    }

    /**
     * Check if Google Drive is connected
     */
    public function hasGoogleDriveConnected(): bool
    {
        return !empty($this->google_drive_refresh_token);
    }

    /**
     * Check if access token is expired
     */
    public function isGoogleDriveTokenExpired(): bool
    {
        if (!$this->google_drive_token_expires_at) {
            return true;
        }
        return $this->google_drive_token_expires_at->isPast();
    }

    /**
     * Refresh Google Drive access token
     */
    public function refreshGoogleDriveToken(): ?string
    {
        if (!$this->google_drive_refresh_token) {
            return null;
        }

        $client = new \Google\Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        try {
            $newToken = $client->fetchAccessTokenWithRefreshToken($this->google_drive_refresh_token);

            if (isset($newToken['access_token'])) {
                $this->update([
                    'google_drive_access_token' => $newToken['access_token'],
                    'google_drive_token_expires_at' => now()->addSeconds($newToken['expires_in'] ?? 3600),
                ]);
                return $newToken['access_token'];
            }
        } catch (\Exception $e) {
            Log::error('Failed to refresh Google Drive token for dosen ' . $this->id . ': ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get valid access token (refresh if needed)
     */
    public function getValidGoogleDriveToken(): ?string
    {
        if (!$this->hasGoogleDriveConnected()) {
            return null;
        }

        if ($this->isGoogleDriveTokenExpired()) {
            return $this->refreshGoogleDriveToken();
        }

        return $this->google_drive_access_token;
    }
}
