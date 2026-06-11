<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate the sidebar logic
$user = \App\Models\User::find(6);

$isOwner = $user && method_exists($user, 'hasRole') && $user->hasRole('owner');
$allowedPages = null;

if (! $isOwner && $user && method_exists($user, 'hasRole') && $user->hasRole('admin')) {
    $impersonateBranchId = session('impersonate.branch_id');
    $branch = null;
    if ($impersonateBranchId) {
        $branch = \App\Models\Branch::find($impersonateBranchId);
    }
    if (! $branch && ! empty($user->branch_id)) {
        $branch = \App\Models\Branch::find($user->branch_id);
    }
    if (! $branch) {
        $branch = \App\Models\Branch::where('admin_id', $user->id)->first();
    }

    if ($branch) {
        $allowedPages = $branch->allowed_pages ?? [];
        if (! is_array($allowedPages) || empty($allowedPages)) {
            $allowedPages = [];
            if ($branch->can_students) $allowedPages[] = 'student';
            if ($branch->can_teachers) $allowedPages[] = 'teacher';
            if ($branch->can_schedules) $allowedPages[] = 'schedule';
            if ($branch->can_payments) $allowedPages[] = 'payment';
            if ($branch->can_tryouts) $allowedPages[] = 'tryout';
        }
    } else {
        $allowedPages = [];
    }
}

echo "isOwner: " . ($isOwner ? 'true' : 'false') . "\n";
echo "allowedPages: " . json_encode($allowedPages) . "\n";
echo "\n";

// Check each menu item condition
$menuItems = [
    'student' => 'Siswa',
    'teacher' => 'Guru',
    'module' => 'Modul Belajar',
    'package' => 'Paket Belajar',
    'course' => 'Mata Pelajaran',
    'course_fee' => 'Biaya Mapel',
    'class' => 'Kelas',
    'schedule' => 'Jadwal',
    'certificate' => 'Sertifikat',
    'payment' => 'Pembayaran',
    'salary' => 'Gaji Guru',
    'report' => 'Laporan',
    'landing' => 'Landing Page',
    'announcement' => 'Pengumuman',
    'message' => 'Pesan Aplikasi',
    'videocall' => 'Video Call',
    'tryout' => 'Tryout CBT',
];

foreach ($menuItems as $key => $label) {
    $condition = $isOwner || in_array($key, (array)$allowedPages);
    echo $label . ": " . ($condition ? 'SHOWN' : 'HIDDEN') . "\n";
}
