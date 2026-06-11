<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $branches = \App\Models\Branch::select('id','name','allowed_pages','can_students','can_teachers','admin_id')->get()->toArray();
    echo json_encode($branches, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
} catch (\Throwable $e) {
    echo "ERR: " . $e->getMessage();
}
