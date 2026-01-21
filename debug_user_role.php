<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$app->make(Kernel::class)->bootstrap();

echo "Checking Users and Roles...\n";
echo str_pad("ID", 5) . str_pad("Name", 30) . str_pad("Email", 40) . str_pad("Role", 15) . "\n";
echo str_repeat("-", 90) . "\n";

foreach (App\Models\User::all() as $user) {
    $role = $user->role ?? 'NULL';
    echo str_pad($user->id, 5) . str_pad(substr($user->name, 0, 28), 30) . str_pad(substr($user->email, 0, 38), 40) . str_pad($role, 15) . "\n";
}

echo "\nDone.\n";
