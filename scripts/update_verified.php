<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Count existing 'verified'
$found = \App\Models\Motor::where('status', 'verified')->count();
$updated = 0;
if ($found > 0) {
    $updated = \App\Models\Motor::where('status', 'verified')->update(['status' => 'tersedia']);
}

echo "Found: $found\n";
echo "Updated: $updated\n";
