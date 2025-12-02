<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;

 $motors = \App\Models\Motor::all();
 if ($motors->isEmpty()) {
     echo "No motors found\n";
     exit(0);
 }

 foreach ($motors as $motor) {
     echo "---- Motor #{$motor->id} ({$motor->merk}) ----\n";
     echo "photo field: " . $motor->photo . "\n";
     echo "Storage::url: " . Storage::url($motor->photo) . "\n";
     /** @var \Illuminate\Filesystem\FilesystemAdapter $publicDisk */
     $publicDisk = Storage::disk('public');
     echo "disk public url: " . $publicDisk->url($motor->photo) . "\n";

     // check file exists via public path
     $publicPath = public_path('storage/' . $motor->photo);
     echo "public path: " . $publicPath . "\n";
     echo "public file exists: " . (file_exists($publicPath) ? 'yes' : 'no') . "\n";

     $storagePath = storage_path('app/public/' . $motor->photo);
     echo "storage path: " . $storagePath . "\n";
     echo "storage file exists: " . (file_exists($storagePath) ? 'yes' : 'no') . "\n";
 }
