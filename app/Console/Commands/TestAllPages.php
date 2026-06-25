<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestAllPages extends Command
{
    protected $signature = 'test:pages';
    protected $description = 'Test all GET pages for each role';

    private array $errors = [];
    private int $ok = 0;

    public function handle()
    {
        $this->info("\n🔍 Testing all pages...\n");

        $admin = User::where('email','admincabangsci@akademi.com')->first();
        $this->testRole('ADMIN', $admin, [
            '/admin/students', '/admin/students/create', '/admin/teachers',
            '/admin/teachers/create', '/admin/schedules/create',
            '/admin/courses', '/admin/categories', '/admin/packages',
            '/admin/salaries', '/admin/salaries/create', '/admin/announcements',
            '/admin/tryouts', '/admin/messages', '/admin/module',
            '/admin/module/create', '/admin/billing', '/admin/attendance',
            '/admin/attendance-history', '/admin/reschedule',
            '/admin/registration-create',
            '/admin/course-package', '/admin/course-package/create',
            '/admin/subject', '/admin/subject/create', '/admin/schedule',
            '/admin/tagihan-siswa', '/admin/riwayat-guru-mengajar',
            '/admin/riwayat-sesi', '/admin/verifikasi-pembayaran',
            '/admin/course-payments', '/admin/landing', '/admin/reports',
            '/admin/videocall', '/admin/certificates',
        ]);

        $owner = User::where('email','adminpusatsci@akademi.com')->first();
        $this->testRole('OWNER', $owner, [
            '/owner/branches', '/owner/settings',
            '/owner/analytics', '/owner/activity-log',
        ]);

        $guru = User::where('email','gurusci@gmail.com')->first();
        $this->testRole('GURU', $guru, [
            '/guru/dashboard', '/guru/attendance/history',
            '/guru/classes', '/guru/messages', '/guru/announcements',
            '/guru/schedule-agreements',
        ]);

        $siswaUser = User::where('email','andi.nugroho@siswa.com')->first();
        $this->testRole('SISWA', $siswaUser, [
            '/siswa/dashboard', '/siswa/courses', '/siswa/courses/fees',
            '/siswa/tryout', '/siswa/billing', '/siswa/announcements',
            '/siswa/messages', '/siswa/attendance', '/siswa/schedules',
            '/siswa/certificates', '/siswa/schedule-agreements',
        ]);

        $this->line("\n" . str_repeat('─', 60));
        $this->info("✅ OK: {$this->ok}   ❌ Errors: " . count($this->errors));
        if ($this->errors) {
            $this->line("\n❌ FAILED PAGES:");
            foreach ($this->errors as $e) $this->error($e);
        } else {
            $this->info("🎉 All pages passed!");
        }
    }

    private function testRole(string $role, ?User $user, array $paths): void
    {
        if (!$user) { $this->error("User for $role not found"); return; }
        $this->line("\n=== $role ({$user->email}) ===");

        foreach ($paths as $path) {
            try {
                $req = \Illuminate\Http\Request::create($path, 'GET');
                $req->setLaravelSession(app('session')->driver());
                $req->session()->put('_token', 'fake');
                $req->setUserResolver(fn() => $user);
                auth()->setUser($user);
                app()->instance('request', $req);

                $response = app(\Illuminate\Contracts\Http\Kernel::class)->handle($req);
                $status   = $response->getStatusCode();
                $body     = $response->getContent();

                if ($status >= 500) {
                    preg_match('~<title>([^<]+)</title>~', $body, $m);
                    $title = $m[1] ?? 'Server Error';
                    $this->errors[] = "[$role] $path → HTTP $status: $title";
                    $this->line("  ❌ $path → $status");
                } elseif ($status >= 400 && $status !== 302) {
                    $this->errors[] = "[$role] $path → HTTP $status";
                    $this->line("  ❌ $path → $status");
                } elseif (preg_match('~(Whoops|ErrorException|SQLSTATE[^<]{0,80}|Undefined variable|Class .* not found)~', $body, $m)) {
                    $this->errors[] = "[$role] $path → EXCEPTION: " . substr($m[1], 0, 100);
                    $this->line("  ❌ $path → EXCEPTION: " . substr($m[1], 0, 60));
                } else {
                    $this->ok++;
                    $this->line("  ✅ $path → $status");
                }
            } catch (\Throwable $e) {
                $this->errors[] = "[$role] $path → " . substr($e->getMessage(), 0, 100);
                $this->line("  ❌ $path → " . substr($e->getMessage(), 0, 80));
            }
        }
    }
}
