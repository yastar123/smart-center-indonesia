<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\StudentRegistration;
$regs = StudentRegistration::latest()->take(5)->get(['id','name','status']);
foreach ($regs as $r) {
    echo $r->id . ' | ' . $r->name . ' | ' . $r->status . PHP_EOL;
}
