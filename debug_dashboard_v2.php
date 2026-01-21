<?php

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Penelitian;
use App\Models\Pengabdian;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Debugging Dashboard Data (V2) ---\n";

$users = User::all();

foreach ($users as $user) {
    echo "User: {$user->id} - {$user->name} ({->email})\n";
    
    // Find Mahasiswa linked to this user
    $m = Mahasiswa::where('user_id', $user->id)->first();
    
    if ($m) {
        echo "  -> Linked Mahasiswa: {$m->id} - {$m->nama} (NIM: {$m->nim})\n";
        
        $pCount = Penelitian::where('mahasiswa_id', $m->id)->count();
        $pengCount = Pengabdian::where('mahasiswa_id', $m->id)->count();
        
        echo "     -> Penelitian Count: $pCount\n";
        echo "     -> Pengabdian Count: $pengCount\n";
        
        if ($pCount > 0) {
            foreach(Penelitian::where('mahasiswa_id', $m->id)->get() as $p) {
                 echo "        - Penelitian: {$p->id} - {$p->judul}\n";
            }
        }
        
       if ($pengCount > 0) {
            foreach(Pengabdian::where('mahasiswa_id', $m->id)->get() as $p) {
                 echo "        - Pengabdian: {$p->id} - {$p->judul}\n";
            }
        }

    } else {
        echo "  -> NO Linked Mahasiswa found for this User.\n";
    }
    echo "------------------------------------------------\n";
}

echo "\n--- All Data in Penelitian (First 5) ---\n";
foreach(Penelitian::limit(5)->get() as $p) {
    echo "ID: {$p->id} | Judul: {$p->judul} | Mahasiswa_ID: {$p->mahasiswa_id}\n";
}

echo "\n--- All Data in Pengabdian (First 5) ---\n";
foreach(Pengabdian::limit(5)->get() as $p) {
    echo "ID: {$p->id} | Judul: {$p->judul} | Mahasiswa_ID: {$p->mahasiswa_id}\n";
}
