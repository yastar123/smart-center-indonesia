<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email','adminpusatsci@akademi.com')->first();
if (! $user) {
    echo "missing\n";
    exit(1);
}

echo (password_verify('password', $user->password) ? "ok\n" : "bad\n");
