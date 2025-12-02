<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$counts = DB::table('motors')
    ->select('status', DB::raw('count(*) as cnt'))
    ->groupBy('status')
    ->pluck('cnt', 'status')
    ->toArray();

echo "Motor counts by status:\n";
foreach ($counts as $status => $cnt) {
    echo " - $status: $cnt\n";
}

$pending = DB::table('motors')->where('status','pending')->count();
$tersedia = DB::table('motors')->where('status','tersedia')->count();
$verified = DB::table('motors')->where('status','verified')->count();

echo "\nSummary:\n";
echo "pending: $pending\n";
echo "tersedia: $tersedia\n";
echo "verified: $verified\n";
