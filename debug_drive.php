<?php

use Illuminate\Support\Facades\Config;
use App\Models\Dokumentasi;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- DEBUG INFO ---\n";

// 1. Cek Config
$folderId = Config::get('filesystems.disks.google.folder');
echo "Config folder ID: " . ($folderId ? $folderId : '(KOSONG)') . "\n";
echo "Config Client ID: " . substr(Config::get('filesystems.disks.google.clientId') ?? '', 0, 5) . "...\n";

// 2. Cek Upload Terakhir
$latest = Dokumentasi::latest()->first();

if ($latest) {
    echo "\n--- DOKUMENTASI TERAKHIR ---\n";
    echo "ID: " . $latest->id . "\n";
    echo "Jenis: " . $latest->jenis . "\n";
    echo "File Path: " . $latest->file_path . "\n";
    echo "Drive Link: " . $latest->drive_link . "\n";
    echo "Dibuat: " . $latest->created_at . "\n";

    // Coba extrak ID file dari path atau link
    // Path biasanya: 1aBcDeFgHiJkLmNoP...
    // Kita bisa cek metadata file tersebut ke Google Drive kalau driver jalan
    try {
        if ($latest->file_path) {
            $meta = \Illuminate\Support\Facades\Storage::disk('google')->getMetadata($latest->file_path);
            echo "\n--- METADATA GOOGLE DRIVE ---\n";
            echo "Path di Drive: " . $meta['path'] . "\n";
            // Note: Flysystem google drive adapter behavior varies on what metadata it returns
        }
    } catch (\Exception $e) {
        echo "\nGagal ambil metadata: " . $e->getMessage() . "\n";
    }
} else {
    echo "\nBelum ada data dokumentasi.\n";
}
