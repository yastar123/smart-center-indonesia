<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Student;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Module;
use App\Models\Package;
use App\Models\Course;
use App\Models\CourseFee;
use App\Models\SchoolClass;
use App\Models\Schedule;
use App\Models\Certificate;
use App\Models\Payment;
use App\Models\Salary;
use App\Models\Announcement;
use App\Models\ChatMessage;
use App\Models\Tryout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;
use PDF;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount('students')->latest()->get();

        // Build a simple list of pages (top-level permission keys) to show in the access form
        try {
            $pages = Permission::all()->pluck('name')
                ->map(function ($n) { return explode('.', $n)[0]; })
                ->unique()
                ->values()
                ->sort()
                ->toArray();
        } catch (\Throwable $e) {
            $pages = [];
        }

        // Build grouped menu structure matching sidebar (label, url, count)
        try {
            $counts = [];
            $counts['students'] = Student::count();
            $counts['teachers'] = class_exists(Teacher::class) ? Teacher::count() : 0;
            $counts['modules'] = class_exists(Module::class) ? Module::count() : 0;
            $counts['packages'] = class_exists(Package::class) ? Package::count() : 0;
            $counts['courses'] = class_exists(Course::class) ? Course::count() : 0;
            $counts['course_fees'] = class_exists(CourseFee::class) ? CourseFee::count() : 0;
            $counts['classes'] = class_exists(SchoolClass::class) ? SchoolClass::count() : 0;
            $counts['schedules'] = class_exists(Schedule::class) ? Schedule::count() : 0;
            $counts['certificates'] = class_exists(Certificate::class) ? Certificate::count() : 0;
            $counts['payments'] = class_exists(Payment::class) ? Payment::count() : 0;
            $counts['salaries'] = class_exists(Salary::class) ? Salary::count() : 0;
            $counts['announcements'] = class_exists(Announcement::class) ? Announcement::count() : 0;
            $counts['messages'] = class_exists(ChatMessage::class) ? ChatMessage::count() : 0;
            $counts['tryouts'] = class_exists(Tryout::class) ? Tryout::count() : 0;
        } catch (\Throwable $e) {
            $counts = [];
        }

        $menuStructure = [
            ['section' => 'AKADEMIK', 'items' => [
                ['key' => 'student', 'label' => 'Siswa', 'url' => url('/admin/students'), 'count' => $counts['students'] ?? 0],
                ['key' => 'teacher', 'label' => 'Guru', 'url' => url('/admin/teachers'), 'count' => $counts['teachers'] ?? 0],
                ['key' => 'module', 'label' => 'Modul Belajar', 'url' => url('/admin/modules'), 'count' => $counts['modules'] ?? 0],
                ['key' => 'package', 'label' => 'Paket Belajar', 'url' => url('/admin/packages'), 'count' => $counts['packages'] ?? 0],
                ['key' => 'course', 'label' => 'Mata Pelajaran', 'url' => url('/admin/courses'), 'count' => $counts['courses'] ?? 0],
                ['key' => 'course_fee', 'label' => 'Biaya Mapel', 'url' => url('/admin/courses/fees'), 'count' => $counts['course_fees'] ?? 0],
                ['key' => 'class', 'label' => 'Kelas', 'url' => url('/admin/classes'), 'count' => $counts['classes'] ?? 0],
                ['key' => 'schedule', 'label' => 'Jadwal', 'url' => url('/admin/schedules'), 'count' => $counts['schedules'] ?? 0],
                ['key' => 'certificate', 'label' => 'Sertifikat', 'url' => url('/admin/certificates'), 'count' => $counts['certificates'] ?? 0],
            ]],
            ['section' => 'KEUANGAN', 'items' => [
                ['key' => 'salary', 'label' => 'Gaji Guru', 'url' => url('/admin/salaries'), 'count' => $counts['salaries'] ?? 0],
                ['key' => 'report', 'label' => 'Laporan Keuangan', 'url' => url('/admin/reports'), 'count' => 0],
            ]],
            ['section' => 'LANDING PAGE', 'items' => [
                ['key' => 'landing', 'label' => 'Kelola Landing Page', 'url' => url('/admin/landing'), 'count' => 0],
            ]],
            ['section' => 'KOMUNIKASI', 'items' => [
                ['key' => 'announcement', 'label' => 'Pengumuman', 'url' => url('/admin/announcements'), 'count' => $counts['announcements'] ?? 0],
                ['key' => 'message', 'label' => 'Pesan Aplikasi', 'url' => url('/admin/messages'), 'count' => $counts['messages'] ?? 0],
                ['key' => 'videocall', 'label' => 'Video Call', 'url' => url('/admin/videocall'), 'count' => 0],
            ]],
            ['section' => 'TRYOUT CBT', 'items' => [
                ['key' => 'tryout', 'label' => 'Tryout UTBK/PTN', 'url' => url('/admin/tryouts'), 'count' => $counts['tryouts'] ?? 0],
            ]],
        ];

        return view('owner.branches.index', [
            'branches' => $branches,
            'total' => Branch::count(),
            'active' => Branch::where('status', 'active')->count(),
            'students' => Student::count(),
            'pages' => $pages,
            'menuStructure' => $menuStructure,
        ]);
    }

    public function __construct()
    {
        // allow only owner (Admin Pusat) to manage branches
        $this->middleware(function ($request, $next) {
            if (! auth()->check() || ! auth()->user()->hasRole('owner')) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function create()
    {
        $pages = [];
        try {
            $pages = Permission::all()->pluck('name')
                ->map(function ($n) { return explode('.', $n)[0]; })
                ->unique()
                ->values()
                ->sort()
                ->toArray();
        } catch (\Throwable $e) {
            $pages = [];
        }

        $counts = [];
        try {
            $counts['students'] = Student::count();
            $counts['teachers'] = class_exists(Teacher::class) ? Teacher::count() : 0;
            $counts['modules'] = class_exists(Module::class) ? Module::count() : 0;
            $counts['packages'] = class_exists(Package::class) ? Package::count() : 0;
            $counts['courses'] = class_exists(Course::class) ? Course::count() : 0;
            $counts['course_fees'] = class_exists(CourseFee::class) ? CourseFee::count() : 0;
            $counts['classes'] = class_exists(SchoolClass::class) ? SchoolClass::count() : 0;
            $counts['schedules'] = class_exists(Schedule::class) ? Schedule::count() : 0;
            $counts['certificates'] = class_exists(Certificate::class) ? Certificate::count() : 0;
            $counts['payments'] = class_exists(Payment::class) ? Payment::count() : 0;
            $counts['salaries'] = class_exists(Salary::class) ? Salary::count() : 0;
            $counts['announcements'] = class_exists(Announcement::class) ? Announcement::count() : 0;
            $counts['messages'] = class_exists(ChatMessage::class) ? ChatMessage::count() : 0;
            $counts['tryouts'] = class_exists(Tryout::class) ? Tryout::count() : 0;
        } catch (\Throwable $e) {
            $counts = [];
        }

        $menuStructure = [
            ['section' => 'AKADEMIK', 'items' => [
                ['key' => 'student', 'label' => 'Siswa', 'url' => url('/admin/students'), 'count' => $counts['students'] ?? 0],
                ['key' => 'teacher', 'label' => 'Guru', 'url' => url('/admin/teachers'), 'count' => $counts['teachers'] ?? 0],
                ['key' => 'module', 'label' => 'Modul Belajar', 'url' => url('/admin/modules'), 'count' => $counts['modules'] ?? 0],
                ['key' => 'package', 'label' => 'Paket Belajar', 'url' => url('/admin/packages'), 'count' => $counts['packages'] ?? 0],
                ['key' => 'course', 'label' => 'Mata Pelajaran', 'url' => url('/admin/courses'), 'count' => $counts['courses'] ?? 0],
                ['key' => 'course_fee', 'label' => 'Biaya Mapel', 'url' => url('/admin/courses/fees'), 'count' => $counts['course_fees'] ?? 0],
                ['key' => 'class', 'label' => 'Kelas', 'url' => url('/admin/classes'), 'count' => $counts['classes'] ?? 0],
                ['key' => 'schedule', 'label' => 'Jadwal', 'url' => url('/admin/schedules'), 'count' => $counts['schedules'] ?? 0],
                ['key' => 'certificate', 'label' => 'Sertifikat', 'url' => url('/admin/certificates'), 'count' => $counts['certificates'] ?? 0],
            ]],
            ['section' => 'KEUANGAN', 'items' => [
                ['key' => 'salary', 'label' => 'Gaji Guru', 'url' => url('/admin/salaries'), 'count' => $counts['salaries'] ?? 0],
                ['key' => 'report', 'label' => 'Laporan Keuangan', 'url' => url('/admin/reports'), 'count' => 0],
            ]],
            ['section' => 'LANDING PAGE', 'items' => [
                ['key' => 'landing', 'label' => 'Kelola Landing Page', 'url' => url('/admin/landing'), 'count' => 0],
            ]],
            ['section' => 'KOMUNIKASI', 'items' => [
                ['key' => 'announcement', 'label' => 'Pengumuman', 'url' => url('/admin/announcements'), 'count' => $counts['announcements'] ?? 0],
                ['key' => 'message', 'label' => 'Pesan Aplikasi', 'url' => url('/admin/messages'), 'count' => $counts['messages'] ?? 0],
                ['key' => 'videocall', 'label' => 'Video Call', 'url' => url('/admin/videocall'), 'count' => 0],
            ]],
            ['section' => 'TRYOUT CBT', 'items' => [
                ['key' => 'tryout', 'label' => 'Tryout UTBK/PTN', 'url' => url('/admin/tryouts'), 'count' => $counts['tryouts'] ?? 0],
            ]],
        ];

        return view('owner.branches.form', [
            'branch' => null,
            'title' => 'Tambah Cabang',
            'pages' => $pages,
            'menuStructure' => $menuStructure,
        ]);
    }

    public function edit(Branch $branch)
    {
        $pages = [];
        try {
            $pages = Permission::all()->pluck('name')
                ->map(function ($n) { return explode('.', $n)[0]; })
                ->unique()
                ->values()
                ->sort()
                ->toArray();
        } catch (\Throwable $e) {
            $pages = [];
        }

        $counts = [];
        try {
            $counts['students'] = Student::count();
            $counts['teachers'] = class_exists(Teacher::class) ? Teacher::count() : 0;
            $counts['modules'] = class_exists(Module::class) ? Module::count() : 0;
            $counts['packages'] = class_exists(Package::class) ? Package::count() : 0;
            $counts['courses'] = class_exists(Course::class) ? Course::count() : 0;
            $counts['course_fees'] = class_exists(CourseFee::class) ? CourseFee::count() : 0;
            $counts['classes'] = class_exists(SchoolClass::class) ? SchoolClass::count() : 0;
            $counts['schedules'] = class_exists(Schedule::class) ? Schedule::count() : 0;
            $counts['certificates'] = class_exists(Certificate::class) ? Certificate::count() : 0;
            $counts['payments'] = class_exists(Payment::class) ? Payment::count() : 0;
            $counts['salaries'] = class_exists(Salary::class) ? Salary::count() : 0;
            $counts['announcements'] = class_exists(Announcement::class) ? Announcement::count() : 0;
            $counts['messages'] = class_exists(ChatMessage::class) ? ChatMessage::count() : 0;
            $counts['tryouts'] = class_exists(Tryout::class) ? Tryout::count() : 0;
        } catch (\Throwable $e) {
            $counts = [];
        }

        $menuStructure = [
            ['section' => 'AKADEMIK', 'items' => [
                ['key' => 'student', 'label' => 'Siswa', 'url' => url('/admin/students'), 'count' => $counts['students'] ?? 0],
                ['key' => 'teacher', 'label' => 'Guru', 'url' => url('/admin/teachers'), 'count' => $counts['teachers'] ?? 0],
                ['key' => 'module', 'label' => 'Modul Belajar', 'url' => url('/admin/modules'), 'count' => $counts['modules'] ?? 0],
                ['key' => 'package', 'label' => 'Paket Belajar', 'url' => url('/admin/packages'), 'count' => $counts['packages'] ?? 0],
                ['key' => 'course', 'label' => 'Mata Pelajaran', 'url' => url('/admin/courses'), 'count' => $counts['courses'] ?? 0],
                ['key' => 'course_fee', 'label' => 'Biaya Mapel', 'url' => url('/admin/courses/fees'), 'count' => $counts['course_fees'] ?? 0],
                ['key' => 'class', 'label' => 'Kelas', 'url' => url('/admin/classes'), 'count' => $counts['classes'] ?? 0],
                ['key' => 'schedule', 'label' => 'Jadwal', 'url' => url('/admin/schedules'), 'count' => $counts['schedules'] ?? 0],
                ['key' => 'certificate', 'label' => 'Sertifikat', 'url' => url('/admin/certificates'), 'count' => $counts['certificates'] ?? 0],
            ]],
            ['section' => 'KEUANGAN', 'items' => [
                ['key' => 'salary', 'label' => 'Gaji Guru', 'url' => url('/admin/salaries'), 'count' => $counts['salaries'] ?? 0],
                ['key' => 'report', 'label' => 'Laporan Keuangan', 'url' => url('/admin/reports'), 'count' => 0],
            ]],
            ['section' => 'LANDING PAGE', 'items' => [
                ['key' => 'landing', 'label' => 'Kelola Landing Page', 'url' => url('/admin/landing'), 'count' => 0],
            ]],
            ['section' => 'KOMUNIKASI', 'items' => [
                ['key' => 'announcement', 'label' => 'Pengumuman', 'url' => url('/admin/announcements'), 'count' => $counts['announcements'] ?? 0],
                ['key' => 'message', 'label' => 'Pesan Aplikasi', 'url' => url('/admin/messages'), 'count' => $counts['messages'] ?? 0],
                ['key' => 'videocall', 'label' => 'Video Call', 'url' => url('/admin/videocall'), 'count' => 0],
            ]],
            ['section' => 'TRYOUT CBT', 'items' => [
                ['key' => 'tryout', 'label' => 'Tryout UTBK/PTN', 'url' => url('/admin/tryouts'), 'count' => $counts['tryouts'] ?? 0],
            ]],
        ];

        return view('owner.branches.form', [
            'branch' => $branch,
            'title' => 'Edit Cabang',
            'pages' => $pages,
            'menuStructure' => $menuStructure,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'email' => 'nullable|email',
            'password' => 'nullable|min:6',
            'admin_name' => 'nullable|string',
            'admin_username' => 'nullable|string',
        ]);

        // Determine feature flags from either pages[] (new UI) or legacy can_* inputs
        $pagesSelected = $request->input('pages');
        if (is_array($pagesSelected) && count($pagesSelected)) {
            $can_students = in_array('student', $pagesSelected);
            $can_teachers = in_array('teacher', $pagesSelected) || in_array('employee', $pagesSelected);
            $can_schedules = in_array('schedule', $pagesSelected);
            $can_payments = in_array('payment', $pagesSelected);
            $can_tryouts = in_array('tryout', $pagesSelected);
        } else {
            $can_students = $request->has('can_students');
            $can_teachers = $request->has('can_teachers');
            $can_schedules = $request->has('can_schedules');
            $can_payments = $request->has('can_payments');
            $can_tryouts = $request->has('can_tryouts');
        }

        // create branch first (without admin_id)
        $branch = Branch::create([
            'name' => $request->name,
            'city' => $request->city,
            'regency' => $request->regency,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'active',
            'can_students' => $can_students,
            'can_teachers' => $can_teachers,
            'can_schedules' => $can_schedules,
            'can_payments' => $can_payments,
            'can_tryouts' => $can_tryouts,
            'allowed_pages' => is_array($pagesSelected) ? array_values($pagesSelected) : [],
            'created_by' => auth()->id(),
        ]);

        // create admin user if email provided
        if ($request->email && $request->password) {
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->admin_name ?? $request->email,
                    'username' => $request->admin_username ?? null,
                    'password' => Hash::make($request->password),
                    'is_active' => true,
                ]
            );

            // assign role admin
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('admin');
            }

            // link user to branch and save relation
            $user->branch_id = $branch->id;
            $user->save();

            $branch->admin_id = $user->id;
            $branch->save();
        }

        return back()->with('success', 'Cabang berhasil dibuat');
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'status' => 'nullable',
            'email' => 'nullable|email',
            'password' => 'nullable|min:6',
            'admin_name' => 'nullable|string',
            'admin_username' => 'nullable|string',
        ]);

        // Determine feature flags from either pages[] (new UI) or legacy can_* inputs
        $pagesSelected = $request->input('pages');
        if (is_array($pagesSelected) && count($pagesSelected)) {
            $can_students = in_array('student', $pagesSelected);
            $can_teachers = in_array('teacher', $pagesSelected) || in_array('employee', $pagesSelected);
            $can_schedules = in_array('schedule', $pagesSelected);
            $can_payments = in_array('payment', $pagesSelected);
            $can_tryouts = in_array('tryout', $pagesSelected);
            $allowed_pages = array_values($pagesSelected);
        } else {
            $can_students = $request->has('can_students');
            $can_teachers = $request->has('can_teachers');
            $can_schedules = $request->has('can_schedules');
            $can_payments = $request->has('can_payments');
            $can_tryouts = $request->has('can_tryouts');
            $allowed_pages = $branch->allowed_pages ?? [];
        }

        $branch->update([
            'name' => $request->name,
            'city' => $request->city,
            'regency' => $request->regency ?? $branch->regency,
            'address' => $request->address ?? $branch->address,
            'phone' => $request->phone ?? $branch->phone,
            'email' => $request->email ?? $branch->email,
            'status' => $request->status ?? $branch->status,
            'can_students' => $can_students,
            'can_teachers' => $can_teachers,
            'can_schedules' => $can_schedules,
            'can_payments' => $can_payments,
            'can_tryouts' => $can_tryouts,
            'allowed_pages' => is_array($allowed_pages) ? $allowed_pages : [],
            'updated_by' => auth()->id(),
        ]);

        // Handle admin account creation/update
        $email = $request->input('email');
        $password = $request->input('password');
        $adminName = $request->input('admin_name');
        $adminUsername = $request->input('admin_username');

        $admin = $branch->admin;

        if ($admin) {
            $data = [];
            if (! empty($adminName)) $data['name'] = $adminName;
            if (! empty($adminUsername)) $data['username'] = $adminUsername;
            if (! empty($email)) $data['email'] = $email;
            if (! empty($password)) $data['password'] = Hash::make($password);
            if (! empty($data)) {
                $admin->update($data);
            }
            if (method_exists($admin, 'assignRole')) {
                $admin->assignRole('admin');
            }
            $admin->branch_id = $branch->id;
            $admin->save();
            $branch->admin_id = $admin->id;
            $branch->save();
        } else {
            // create admin if email+password provided
            if (! empty($email) && ! empty($password)) {
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $adminName ?? $email,
                        'username' => $adminUsername ?? null,
                        'password' => Hash::make($password),
                        'is_active' => true,
                    ]
                );
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('admin');
                }
                $user->branch_id = $branch->id;
                $user->save();
                $branch->admin_id = $user->id;
                $branch->save();
            }
        }

        return back()->with('success', 'Cabang berhasil diupdate');
    }

    public function destroy(Branch $branch)
    {
        // do not delete admin user automatically; just remove relation
        $branch->admin_id = null;
        $branch->save();
        $branch->delete();

        return back()->with('success', 'Cabang berhasil dihapus');
    }
    public function resetPassword(Request $request, Branch $branch)
{
    $request->validate([
        'password' => 'required|min:6'
    ]);

    if ($branch->admin && $branch->admin->exists()) {
        $branch->admin->update(['password' => Hash::make($request->password)]);
    } else {
        $branch->update(['password' => Hash::make($request->password)]);
    }

    return back()->with('success', 'Password cabang berhasil direset');
}
    public function exportExcel()
    {
        $filename = 'branches_' . date('Ymd_His') . '.csv';

        $branches = Branch::select('id','name','address','city','regency','phone','email','status','created_at')->get();

        $callback = function() use ($branches) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID','Name','Address','City','Regency','Phone','Email','Status','Created At']);

            foreach($branches as $b){
                fputcsv($out, [
                    $b->id,
                    $b->name,
                    $b->address,
                    $b->city,
                    $b->regency,
                    $b->phone,
                    $b->email,
                    $b->status,
                    $b->created_at,
                ]);
            }

            fclose($out);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf()
    {
        $branches = Branch::all();

        // If PDF facade exists (barryvdh/laravel-dompdf), use it; otherwise return HTML view
        if (class_exists('\Barryvdh\DomPDF\Facade')) {
            $pdf = PDF::loadView('owner.branches.pdf', compact('branches'));
            return $pdf->download('branches.pdf');
        }

        return response()->view('owner.branches.pdf', compact('branches'));
    }

    /**
     * Owner view of a branch dashboard
     */
    public function dashboard(Branch $branch)
    {
        // load some quick stats for the branch
        $studentsCount = $branch->students()->count();

        return view('owner.branches.dashboard', [
            'branch' => $branch,
            'studentsCount' => $studentsCount,
        ]);
    }

    /**
     * Impersonate branch admin (owner only)
     */
    public function impersonate(Request $request, Branch $branch)
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }
        // defensive: prefer helper methods if available
        $isOwner = false;
        try {
            if (method_exists($user, 'isOwner')) $isOwner = $user->isOwner();
            elseif (method_exists($user, 'hasRole')) $isOwner = $user->hasRole('owner');
        } catch (\Throwable $e) {
            $isOwner = false;
        }
        if (! $isOwner) {
            // include current roles to help debugging instead of immediate 403
            try {
                $roles = $user->getRoleNames()->toArray();
            } catch (\Throwable $e) {
                $roles = [];
            }
            \Log::warning('Impersonate denied: user lacks owner role', ['user_id' => $user->id, 'roles' => $roles]);
            return back()->with('error', 'Akses ditolak: Anda tidak memiliki peran owner. Peran saat ini: ' . implode(',', $roles));
        }

        if (! $branch->admin_id) {
            return back()->with('error', 'Cabang belum memiliki akun admin');
        }

        $admin = User::find($branch->admin_id);
        if (! $admin) {
            return back()->with('error', 'Akun admin cabang tidak ditemukan');
        }

        // store original user id to allow leaving impersonation
        $request->session()->put('impersonate.original_user', $user->id);
        $request->session()->put('impersonate.branch_id', $branch->id);

        // log in as branch admin and regenerate session to avoid stale intended URL/session
        Auth::loginUsingId($admin->id);
        try {
            $request->session()->regenerate();
            // clear any intended owner URL so user doesn't get redirected back to owner pages
            $intended = $request->session()->get('url.intended');
            if ($intended && str_starts_with($intended, url('/').'/owner')) {
                $request->session()->forget('url.intended');
            }
        } catch (\Throwable $e) {
            // ignore session regen failures
        }

        return redirect()->route('dashboard');
    }

    /**
     * Leave impersonation and restore original owner session
     */
    public function leaveImpersonation(Request $request)
    {
        $orig = $request->session()->pull('impersonate.original_user');
        $request->session()->forget('impersonate.branch_id');
        if ($orig) {
            Auth::loginUsingId($orig);
        }

        return redirect()->route('owner.branches.index');
    }

}