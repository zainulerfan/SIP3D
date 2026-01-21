<?php

use App\Models\Mahasiswa;
use App\Models\Penelitian;
use App\Models\Pengabdian;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Fixing Data Assignments ---\n";

$mahasiswa = Mahasiswa::find(2); // Joko Bimantaro

if (!$mahasiswa) {
    die("Error: Mahasiswa ID 2 not found!\n");
}

echo "Target Mahasiswa: {$mahasiswa->nama} (ID: {$mahasiswa->id})\n";

// Fix Pengabdian ID 5
$p = Pengabdian::find(5);
if ($p) {
    $p->update(['mahasiswa_id' => $mahasiswa->id]);
    echo "✅ Pengabdian ID 5 assigned to {$mahasiswa->nama}\n";
} else {
    echo "❌ Pengabdian ID 5 not found.\n";
}

// Fix Penelitian ID 3
$pen = Penelitian::find(3);
if ($pen) {
    $pen->update(['mahasiswa_id' => $mahasiswa->id]);
    echo "✅ Penelitian ID 3 assigned to {$mahasiswa->nama}\n";
} else {
    echo "❌ Penelitian ID 3 not found.\n";
}
