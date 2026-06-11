<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::find(6);
$branch = \App\Models\Branch::find($user->branch_id);

echo "User ID: " . $user->id . "\n";
echo "User Name: " . $user->name . "\n";
echo "User branch_id: " . $user->branch_id . "\n";
echo "User roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
echo "\n";

if ($branch) {
    echo "Branch ID: " . $branch->id . "\n";
    echo "Branch Name: " . $branch->name . "\n";
    echo "Branch admin_id: " . $branch->admin_id . "\n";
    echo "Branch allowed_pages: " . json_encode($branch->allowed_pages) . "\n";
    echo "Branch can_students: " . ($branch->can_students ? 'true' : 'false') . "\n";
    echo "Branch can_teachers: " . ($branch->can_teachers ? 'true' : 'false') . "\n";
    echo "Branch can_schedules: " . ($branch->can_schedules ? 'true' : 'false') . "\n";
    echo "Branch can_payments: " . ($branch->can_payments ? 'true' : 'false') . "\n";
    echo "Branch can_tryouts: " . ($branch->can_tryouts ? 'true' : 'false') . "\n";
} else {
    echo "Branch not found\n";
}
