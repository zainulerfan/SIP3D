<?php

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Penelitian;
use App\Models\Pengabdian;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Debugging Dashboard Data ---\n";

$users = User::whereHas('mahasiswa')->with('mahasiswa')->get();

foreach ($users as $user) {
    echo "User: {$user->id} - {$user->name} ({->email})\n";
    $m = $user->mahasiswa; // Accessing via relationship if exists?
    // Wait, the User model I saw earlier did NOT have a 'mahasiswa' method.
    // Let's check if relationship exists.
    
    // Manual lookup since I didn't see the relation in User.php snippet
    $m = Mahasiswa::where('user_id', $user->id)->first();
    
    if ($m) {
        echo "  -> Linked Mahasiswa: {$m->id} - {$m->nama} (NIM: {$m->nim}) (User_ID: {$m->user_id})\n";
        
        $pCount = Penelitian::where('mahasiswa_id', $m->id)->count();
        $pengCount = Pengabdian::where('mahasiswa_id', $m->id)->count();
        
        echo "     -> Penelitian Count: $pCount\n";
        echo "     -> Pengabdian Count: $pengCount\n";
        
        if ($pCount == 0) {
             // Check if there are ANY records referencing this generic name?
             // Maybe matching by name?
        }
    } else {
        echo "  -> NO Linked Mahasiswa found for this User.\n";
    }
    echo "------------------------------------------------\n";
}

echo "\n--- All Data in Penelitian ---\n";
foreach(Penelitian::all() as $p) {
    echo "ID: {$p->id} | Judul: {$p->judul} | Mahasiswa_ID: {$p->mahasiswa_id}\n";
}

echo "\n--- All Data in Pengabdian ---\n";
foreach(Pengabdian::all() as $p) {
    echo "ID: {$p->id} | Judul: {$p->judul} | Mahasiswa_ID: {$p->mahasiswa_id}\n";
}
