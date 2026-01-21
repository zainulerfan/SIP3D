<?php

use Illuminate\Support\Facades\Config;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- CEK KONEKSI GOOGLE DRIVE ---\n";

$clientId     = Config::get('filesystems.disks.google.clientId');
$clientSecret = Config::get('filesystems.disks.google.clientSecret');
$refreshToken = Config::get('filesystems.disks.google.refreshToken');
$folderId     = Config::get('filesystems.disks.google.folder');

// Handle Closure in config
if ($folderId instanceof Closure) {
    $folderId = $folderId();
}

echo "Client ID: " . substr($clientId, 0, 5) . "...\n";
echo "Folder ID Target: " . $folderId . "\n\n";

try {
    $client = new \Google\Client();
    $client->setClientId($clientId);
    $client->setClientSecret($clientSecret);
    $client->refreshToken($refreshToken);

    $service = new \Google\Service\Drive($client);

    echo "Mencoba mengakses folder...\n";
    $file = $service->files->get($folderId, ['fields' => 'id, name, mimeType, owners']);

    echo "BERHASIL! \n";
    echo "Nama Folder: " . $file->getName() . "\n";
    echo "Mime Type: " . $file->getMimeType() . "\n";
    echo "Pemilik: " . ($file->getOwners()[0]->emailAddress ?? 'Unknown') . "\n";
} catch (\Exception $e) {
    echo "GAGAL AKSES FOLDER!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Penyebab Paling Mungkin:\n";
    echo "1. Akun login di OAuth Playground TIDAK SAMA dengan pemilik folder.\n";
    echo "2. Folder ID salah.\n";
}
