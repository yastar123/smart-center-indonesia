<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\CoursePackageController;
use App\Http\Controllers\Admin\ScheduleDashboardController;
use App\Http\Controllers\Admin\RescheduleController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\AcademicModuleController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\TryoutController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\AttendanceHistoryController;
use App\Http\Controllers\Owner\BranchController;
use App\Http\Controllers\Guru\AttendanceController;
use App\Http\Controllers\Siswa\SiswaController;

Route::redirect('/', '/login');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',         [ProfileController::class, 'edit'])         ->name('profile.edit');
    Route::patch('/profile',       [ProfileController::class, 'update'])       ->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']) ->name('profile.avatar');
    Route::delete('/profile',      [ProfileController::class, 'destroy'])      ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|owner', 'check.branch.access'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // STUDENTS
        Route::get('/students',              [StudentController::class, 'index'])   ->name('students.index');
        Route::post('/students',             [StudentController::class, 'store'])   ->name('students.store');
        Route::get('/students/{student}',    [StudentController::class, 'show'])    ->name('students.show');
        Route::put('/students/{student}',    [StudentController::class, 'update'])  ->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy']) ->name('students.destroy');

        // TEACHERS
        Route::get('/teachers',              [TeacherController::class, 'index'])   ->name('teachers.index');
        Route::post('/teachers',             [TeacherController::class, 'store'])   ->name('teachers.store');
        Route::get('/teachers/{teacher}',    [TeacherController::class, 'show'])    ->name('teachers.show');
        Route::put('/teachers/{teacher}',    [TeacherController::class, 'update'])  ->name('teachers.update');
        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy']) ->name('teachers.destroy');

        // PAYMENTS (Invoices)
        Route::get('/payments',                [PaymentController::class, 'index'])   ->name('payments.index');
        Route::post('/payments',               [PaymentController::class, 'store'])   ->name('payments.store');
        Route::get('/payments/{payment}',      [PaymentController::class, 'show'])    ->name('payments.show');
        Route::put('/payments/{payment}',      [PaymentController::class, 'update'])  ->name('payments.update');
        Route::delete('/payments/{payment}',   [PaymentController::class, 'destroy']) ->name('payments.destroy');
        Route::post('/payments/{invoice}/pay', [PaymentController::class, 'markPaid'])->name('payments.pay');

        // Course Payment Verification
        Route::post('/payments/course/{payment}/verify', [PaymentController::class, 'verifyCoursePayment'])->name('payments.course.verify');
        Route::post('/payments/course/{payment}/reject', [PaymentController::class, 'rejectCoursePayment'])->name('payments.course.reject');

        // ATTENDANCE HISTORY (Admin)
        Route::get('/attendance-history', [AttendanceHistoryController::class, 'index'])->name('attendance-history.index');

        // SCHEDULES
        Route::get('/schedules',               [ScheduleController::class, 'index'])   ->name('schedules.index');
        Route::post('/schedules',              [ScheduleController::class, 'store'])   ->name('schedules.store');
        Route::get('/schedules/{schedule}',    [ScheduleController::class, 'show'])    ->name('schedules.show');
        Route::put('/schedules/{schedule}',    [ScheduleController::class, 'update'])  ->name('schedules.update');
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']) ->name('schedules.destroy');

        // COURSES (Mata Pelajaran)
        Route::get('/courses',            [CourseController::class, 'index'])   ->name('courses.index');
        Route::post('/courses',           [CourseController::class, 'store'])   ->name('courses.store');
        Route::get('/courses/{course}',   [CourseController::class, 'show'])    ->name('courses.show');
        Route::put('/courses/{course}',   [CourseController::class, 'update'])  ->name('courses.update');
        Route::delete('/courses/{course}',[CourseController::class, 'destroy']) ->name('courses.destroy');

        // VERIFIKASI PEMBAYARAN MAPEL SISWA
        Route::get('/course-payments', [\App\Http\Controllers\Admin\CoursePaymentController::class, 'index'])->name('course-payments.index');
        Route::post('/course-payments/{payment}/verify', [\App\Http\Controllers\Admin\CoursePaymentController::class, 'verify'])->name('course-payments.verify');
        Route::post('/course-payments/{payment}/reject', [\App\Http\Controllers\Admin\CoursePaymentController::class, 'reject'])->name('course-payments.reject');

        // CATEGORIES
        Route::get('/categories',              [\App\Http\Controllers\Admin\CategoryController::class, 'index']) ->name('categories.index');
        Route::post('/categories',             [\App\Http\Controllers\Admin\CategoryController::class, 'store']) ->name('categories.store');
        Route::get('/categories/{category}',   [\App\Http\Controllers\Admin\CategoryController::class, 'show'])  ->name('categories.show');
        Route::put('/categories/{category}',   [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}',[\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        // CLASSES (Kelas)
        Route::get('/classes',                        [SchoolClassController::class, 'index'])           ->name('classes.index');
        Route::post('/classes',                       [SchoolClassController::class, 'store'])           ->name('classes.store');
        Route::get('/classes/{class}',                [SchoolClassController::class, 'show'])            ->name('classes.show');
        Route::put('/classes/{class}',                [SchoolClassController::class, 'update'])          ->name('classes.update');
        Route::delete('/classes/{class}',             [SchoolClassController::class, 'destroy'])         ->name('classes.destroy');
        Route::get('/teachers/{teacher}/courses',     [SchoolClassController::class, 'getTeacherCourses'])->name('teachers.courses');

        // CERTIFICATES (Sertifikat)
        Route::get('/certificates',                  [CertificateController::class, 'index'])   ->name('certificates.index');
        Route::post('/certificates',                 [CertificateController::class, 'store'])   ->name('certificates.store');
        Route::get('/certificates/{certificate}',    [CertificateController::class, 'show'])    ->name('certificates.show');
        Route::put('/certificates/{certificate}',    [CertificateController::class, 'update'])  ->name('certificates.update');
        Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy']) ->name('certificates.destroy');
        // API untuk ambil mata pelajaran yang diambil siswa (dipakai di UI admin)
        Route::get('/students/{student}/courses',    [CertificateController::class, 'studentCourses']) ->name('students.courses');


        // PACKAGES (Paket Belajar)
        Route::get('/packages',              [PackageController::class, 'index'])   ->name('packages.index');
        Route::post('/packages',             [PackageController::class, 'store'])   ->name('packages.store');
        Route::get('/packages/{package}',    [PackageController::class, 'show'])    ->name('packages.show');
        Route::put('/packages/{package}',    [PackageController::class, 'update'])  ->name('packages.update');
        Route::delete('/packages/{package}', [PackageController::class, 'destroy']) ->name('packages.destroy');

        // SALARIES (Gaji Guru)
        Route::middleware('role:admin|owner')->group(function () {
            Route::get('/salaries',             [SalaryController::class, 'index'])    ->name('salaries.index');
            Route::post('/salaries',            [SalaryController::class, 'store'])    ->name('salaries.store');
            Route::get('/salaries/{salary}',    [SalaryController::class, 'show'])     ->name('salaries.show');
            Route::put('/salaries/{salary}',    [SalaryController::class, 'update'])   ->name('salaries.update');
            Route::delete('/salaries/{salary}', [SalaryController::class, 'destroy'])  ->name('salaries.destroy');
            Route::get('/salaries/{salary}/slip', [SalaryController::class, 'printSlip'])->name('salaries.slip');
        });

        // ANNOUNCEMENTS (Pengumuman)
        Route::get('/announcements',                    [AnnouncementController::class, 'index'])   ->name('announcements.index');
        Route::post('/announcements',                   [AnnouncementController::class, 'store'])   ->name('announcements.store');
        Route::get('/announcements/{announcement}',     [AnnouncementController::class, 'show'])    ->name('announcements.show');
        Route::put('/announcements/{announcement}',     [AnnouncementController::class, 'update'])  ->name('announcements.update');
        Route::delete('/announcements/{announcement}',  [AnnouncementController::class, 'destroy']) ->name('announcements.destroy');

        // TRYOUTS (Tryout UTBK/PTN) — full CRUD + soal management + results
        Route::get('/tryouts',                               [TryoutController::class, 'index'])      ->name('tryouts.index');
        Route::post('/tryouts',                              [TryoutController::class, 'store'])      ->name('tryouts.store');
        Route::get('/tryouts/{tryout}',                      [TryoutController::class, 'show'])       ->name('tryouts.show');
        Route::put('/tryouts/{tryout}',                      [TryoutController::class, 'update'])     ->name('tryouts.update');
        Route::delete('/tryouts/{tryout}',                   [TryoutController::class, 'destroy'])    ->name('tryouts.destroy');
        Route::get('/tryouts/{tryout}/soal',                 [TryoutController::class, 'soalIndex'])  ->name('tryouts.soal.index');
        Route::post('/tryouts/{tryout}/soal',                [TryoutController::class, 'soalStore'])  ->name('tryouts.soal.store');
        Route::delete('/tryouts/{tryout}/soal/{soal}',       [TryoutController::class, 'soalDestroy'])->name('tryouts.soal.destroy');
        Route::get('/tryouts/{tryout}/results',              [TryoutController::class, 'results'])    ->name('tryouts.results');

        // MESSAGES (Pesan Aplikasi)
        Route::get('/messages',                    [MessageController::class, 'index'])       ->name('messages.index');
        Route::get('/messages/rooms',              [MessageController::class, 'getRooms'])    ->name('messages.rooms');
        Route::get('/messages/{room}/messages',    [MessageController::class, 'getMessages']) ->name('messages.get');
        Route::post('/messages/{room}/send',       [MessageController::class, 'sendMessage']) ->name('messages.send');
        Route::post('/messages/room',              [MessageController::class, 'createRoom'])  ->name('messages.createRoom');

        // VIDEO CALL
        Route::get('/videocall', fn() => view('admin.videocall.index'))->name('videocall.index');

        // REPORTS
        Route::get('/reports', fn() => view('admin.reports.index'))->middleware('role:admin|owner')->name('reports.index');

        // SUBJECT (Mata Pelajaran — new UI)
        Route::get('/subject',               [SubjectController::class, 'index'])   ->name('subject.index');
        Route::get('/subject/create',        [SubjectController::class, 'create'])  ->name('subject.create');
        Route::post('/subject',              [SubjectController::class, 'store'])   ->name('subject.store');
        Route::get('/subject/{subject}',     [SubjectController::class, 'show'])    ->name('subject.show');
        Route::get('/subject/{subject}/edit',[SubjectController::class, 'edit'])    ->name('subject.edit');
        Route::put('/subject/{subject}',     [SubjectController::class, 'update'])  ->name('subject.update');
        Route::delete('/subject/{subject}',  [SubjectController::class, 'destroy']) ->name('subject.destroy');

        // COURSE PACKAGE (Paket Belajar — new UI)
        Route::get('/course-package',                        [CoursePackageController::class, 'index'])   ->name('course-package.index');
        Route::get('/course-package/create',                 [CoursePackageController::class, 'create'])  ->name('course-package.create');
        Route::post('/course-package',                       [CoursePackageController::class, 'store'])   ->name('course-package.store');
        Route::get('/course-package/{coursePackage}',        [CoursePackageController::class, 'show'])    ->name('course-package.show');
        Route::get('/course-package/{coursePackage}/edit',   [CoursePackageController::class, 'edit'])    ->name('course-package.edit');
        Route::put('/course-package/{coursePackage}',        [CoursePackageController::class, 'update'])  ->name('course-package.update');
        Route::delete('/course-package/{coursePackage}',     [CoursePackageController::class, 'destroy']) ->name('course-package.destroy');

        // SCHEDULE DASHBOARD (Visual calendar view)
        Route::get('/schedule',              [ScheduleDashboardController::class, 'index']) ->name('schedule-dashboard.index');

        // RESCHEDULE & AVAILABILITY
        Route::get('/reschedule',                       [RescheduleController::class, 'index'])   ->name('reschedule.index');
        Route::post('/reschedule/{proposal}/approve',   [RescheduleController::class, 'approve']) ->name('reschedule.approve');
        Route::post('/reschedule/{proposal}/reject',    [RescheduleController::class, 'reject'])  ->name('reschedule.reject');

        // REGISTRATION (Registrasi Siswa)
        Route::get('/registration-list',         [RegistrationController::class, 'index'])  ->name('registration.index');
        Route::get('/registration-create',       [RegistrationController::class, 'create']) ->name('registration.create');
        Route::post('/registration-create',      [RegistrationController::class, 'store'])  ->name('registration.store');

        // ACADEMIC MODULE (Modul Akademik)
        Route::get('/module',                    [AcademicModuleController::class, 'index'])   ->name('module.index');
        Route::get('/module/create',             [AcademicModuleController::class, 'create'])  ->name('module.create');
        Route::post('/module',                   [AcademicModuleController::class, 'store'])   ->name('module.store');
        Route::get('/module/{module}',           [AcademicModuleController::class, 'show'])    ->name('module.show');
        Route::get('/module/{module}/edit',      [AcademicModuleController::class, 'edit'])    ->name('module.edit');
        Route::put('/module/{module}',           [AcademicModuleController::class, 'update'])  ->name('module.update');
        Route::delete('/module/{module}',        [AcademicModuleController::class, 'destroy']) ->name('module.destroy');

        // BILLING (Manajemen E-Billing)
        Route::get('/billing',                   [BillingController::class, 'index'])   ->name('billing.index');
        Route::post('/billing',                  [BillingController::class, 'store'])   ->name('billing.store');
        Route::get('/billing/export',            [BillingController::class, 'export'])  ->name('billing.export');
        Route::get('/billing/{billing}',         [BillingController::class, 'show'])    ->name('billing.show');
        Route::put('/billing/{billing}',         [BillingController::class, 'update'])  ->name('billing.update');
        Route::delete('/billing/{billing}',      [BillingController::class, 'destroy']) ->name('billing.destroy');

        // SCHEDULE LIST & CREATE (aliases to existing schedules)
        Route::get('/schedule-list',  fn() => redirect()->route('admin.schedules.index'))  ->name('schedule-list.index');
        Route::get('/schedule-create',fn() => redirect()->route('admin.schedules.index'))  ->name('schedule-create.index');

        // LANDING PAGE CONTENT
        Route::get('/landing',                                          [\App\Http\Controllers\Admin\LandingContentController::class, 'index'])             ->name('landing.index');
        Route::put('/landing/settings',                                 [\App\Http\Controllers\Admin\LandingContentController::class, 'updateSettings'])    ->name('landing.settings.update');
        Route::post('/landing/testimonials',                            [\App\Http\Controllers\Admin\LandingContentController::class, 'storeTestimonial'])   ->name('landing.testimonials.store');
        Route::put('/landing/testimonials/{testimonial}',               [\App\Http\Controllers\Admin\LandingContentController::class, 'updateTestimonial'])  ->name('landing.testimonials.update');
        Route::delete('/landing/testimonials/{testimonial}',            [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyTestimonial']) ->name('landing.testimonials.destroy');
        Route::post('/landing/programs',                                [\App\Http\Controllers\Admin\LandingContentController::class, 'storeProgram'])       ->name('landing.programs.store');
        Route::put('/landing/programs/{program}',                       [\App\Http\Controllers\Admin\LandingContentController::class, 'updateProgram'])      ->name('landing.programs.update');
        Route::delete('/landing/programs/{program}',                    [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyProgram'])     ->name('landing.programs.destroy');
        Route::post('/landing/wa',                                      [\App\Http\Controllers\Admin\LandingContentController::class, 'storeWa'])              ->name('landing.wa.store');
        Route::put('/landing/wa/{wa}',                                  [\App\Http\Controllers\Admin\LandingContentController::class, 'updateWa'])             ->name('landing.wa.update');
        Route::delete('/landing/wa/{wa}',                               [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyWa'])            ->name('landing.wa.destroy');
    });

/*
|--------------------------------------------------------------------------
| OWNER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
        Route::get('/branches/{branch}/dashboard', [BranchController::class, 'dashboard'])->name('branches.dashboard');
        Route::post('/branches/{branch}/impersonate', [BranchController::class, 'impersonate'])->name('branches.impersonate');

        // Static routes MUST come before {branch} wildcard
        Route::get('/branches/export/excel', [BranchController::class, 'exportExcel'])->name('branches.export.excel');
        Route::get('/branches/export/pdf',   [BranchController::class, 'exportPdf'])  ->name('branches.export.pdf');

        Route::post('/branches',            [BranchController::class, 'store'])         ->name('branches.store');
        Route::put('/branches/{branch}',    [BranchController::class, 'update'])        ->name('branches.update');
        Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])       ->name('branches.destroy');
        Route::post('/branches/{branch}/reset-password', [BranchController::class, 'resetPassword'])->name('branches.resetPassword');

        Route::get('/activity-log', function () {
            $activities = \Spatie\Activitylog\Models\Activity::with('causer')->latest()->paginate(20);
            return view('owner.activity-log', compact('activities'));
        })->name('activity-log');

        Route::get('/settings', function () {
            $ss = \App\Models\SystemSetting::bulk(
                ['inst.name','inst.acronym','inst.email','inst.phone','inst.address'],
                ['inst.name'=>'Smart Center Indonesia','inst.acronym'=>'SCI','inst.email'=>'','inst.phone'=>'','inst.address'=>'']
            );
            return view('owner.settings', ['ss' => $ss]);
        })->name('settings.index');
        Route::put('/settings', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'inst.name'    => 'required|string|max:150',
                'inst.acronym' => 'required|string|max:20',
                'inst.email'   => 'nullable|email|max:150',
                'inst.phone'   => 'nullable|string|max:30',
                'inst.address' => 'nullable|string|max:500',
            ]);
            $inst = $request->input('inst', []);
            \App\Models\SystemSetting::set('inst.name',    $inst['name']    ?? '');
            \App\Models\SystemSetting::set('inst.acronym', $inst['acronym'] ?? '');
            \App\Models\SystemSetting::set('inst.email',   $inst['email']   ?? '');
            \App\Models\SystemSetting::set('inst.phone',   $inst['phone']   ?? '');
            \App\Models\SystemSetting::set('inst.address', $inst['address'] ?? '');
            return back()->with('success', 'Pengaturan sistem berhasil disimpan.');
        })->name('settings.update');
        Route::get('/analytics', fn() => view('owner.analytics'))->name('analytics');
    });

/*
|--------------------------------------------------------------------------
| GURU PORTAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('guru.dashboard'))->name('dashboard');

        // GURU — Payments (view payments made by admin)
        Route::get('/payments', [\App\Http\Controllers\Guru\SalaryController::class, 'index'])->name('payments.index');

        // MESSAGES (Guru)
        Route::get('/messages', [\App\Http\Controllers\Guru\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/rooms',              [\App\Http\Controllers\Admin\MessageController::class, 'getRooms'])    ->name('messages.rooms');
        Route::get('/messages/{room}/messages',    [\App\Http\Controllers\Admin\MessageController::class, 'getMessages']) ->name('messages.get');
        Route::post('/messages/{room}/send',       [\App\Http\Controllers\Admin\MessageController::class, 'sendMessage']) ->name('messages.send');
        Route::post('/messages/room',              [\App\Http\Controllers\Admin\MessageController::class, 'createRoom'])  ->name('messages.createRoom');

        // CLASSES (Guru)
        Route::get('/classes', [\App\Http\Controllers\Guru\ClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/{class}', [\App\Http\Controllers\Guru\ClassController::class, 'show'])->name('classes.show');
        Route::get('/classes/{class}/attendance', [\App\Http\Controllers\Guru\ClassController::class, 'attendance'])->name('classes.attendance');

        // Pengumuman (Guru) - reuse siswa view but filter by teacher's branch
        Route::get('/announcements', function () {
            $teacher = \App\Models\Teacher::where('user_id', auth()->id())->first();
            $announcements = \App\Models\Announcement::where('status', 'aktif')
                ->where(function ($q) use ($teacher) {
                    $q->whereNull('cabang_id');
                    if ($teacher) {
                        $q->orWhere('cabang_id', $teacher->branch_id);
                    }
                })
                ->where(function ($q) { $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', now()); })
                ->where(function ($q) { $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', now()); })
                ->orderByDesc('is_pinned')
                ->orderByDesc('created_at')
                ->paginate(12);

            return view('siswa.announcements', compact('announcements'))->with('student', null);
        })->name('announcements');

        // JADWAL GURU — guru dapat melihat & mengedit jadwal miliknya
        Route::get('/schedules',               [\App\Http\Controllers\Guru\ScheduleController::class, 'index']) ->name('schedules.index');
        Route::get('/schedules/{schedule}',    [\App\Http\Controllers\Guru\ScheduleController::class, 'show'])  ->name('schedules.show');
        Route::put('/schedules/{schedule}',    [\App\Http\Controllers\Guru\ScheduleController::class, 'update'])->name('schedules.update');

        // Absensi: API endpoints used by class attendance page
        Route::get('/attendance/history', [\App\Http\Controllers\Guru\AttendanceHistoryController::class, 'index'])->name('attendance.history');
        Route::get('/attendance/history/{course}', [\App\Http\Controllers\Guru\AttendanceHistoryController::class, 'show'])->name('attendance.history.show');
        Route::get('/attendance/{schedule}/students',    [AttendanceController::class, 'getStudents'])->name('attendance.students');
        Route::post('/attendance',                       [AttendanceController::class, 'store'])      ->name('attendance.store');
        Route::get('/attendance/report',                 [AttendanceController::class, 'report'])     ->name('attendance.report');


        // Schedule Agreements (Proposals)
        Route::get('/schedule-agreements', [\App\Http\Controllers\Guru\ScheduleProposalController::class, 'index'])->name('schedule-agreements.index');
        Route::post('/schedule-agreements', [\App\Http\Controllers\Guru\ScheduleProposalController::class, 'store'])->name('schedule-agreements.store');
        Route::get('/schedule-agreements/class/{class}/meetings', [\App\Http\Controllers\Guru\ScheduleProposalController::class, 'classMeetings'])->name('schedule-agreements.class-meetings');
        Route::post('/schedule-agreements/{proposal}/approve', [\App\Http\Controllers\Guru\ScheduleProposalController::class, 'approve'])->name('schedule-agreements.approve');
        Route::post('/schedule-agreements/{proposal}/reject', [\App\Http\Controllers\Guru\ScheduleProposalController::class, 'reject'])->name('schedule-agreements.reject');
    });

/*
|--------------------------------------------------------------------------
| SISWA PORTAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('siswa.dashboard'))->name('dashboard');

        // MESSAGES (Siswa)
        Route::get('/messages', [\App\Http\Controllers\Siswa\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/rooms',              [\App\Http\Controllers\Admin\MessageController::class, 'getRooms'])    ->name('messages.rooms');
        Route::get('/messages/{room}/messages',    [\App\Http\Controllers\Admin\MessageController::class, 'getMessages']) ->name('messages.get');
        Route::post('/messages/{room}/send',       [\App\Http\Controllers\Admin\MessageController::class, 'sendMessage']) ->name('messages.send');
        Route::post('/messages/room',              [\App\Http\Controllers\Admin\MessageController::class, 'createRoom'])  ->name('messages.createRoom');

        // JADWAL SISWA — read-only view
        Route::get('/schedules', [\App\Http\Controllers\Siswa\ScheduleController::class, 'index'])->name('schedules.index');

        // Sertifikat
        Route::get('/certificates',                           [SiswaController::class, 'certificates'])      ->name('certificates.index');
        Route::get('/certificates/{certificate}/download',    [SiswaController::class, 'downloadCertificate'])->name('certificates.download');
        Route::post('/certificates/upload',                   [SiswaController::class, 'uploadCertificate']) ->name('certificates.upload');

        // Pengumuman
        Route::get('/announcements', function () {
            $student = \App\Models\Student::where('user_id', auth()->id())->first();
            $announcements = \App\Models\Announcement::where('status', 'aktif')
                ->where(function ($q) use ($student) {
                    $q->whereNull('cabang_id');
                    if ($student) {
                        $q->orWhere('cabang_id', $student->branch_id);
                    }
                })
                ->where(function ($q) { $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', now()); })
                ->where(function ($q) { $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', now()); })
                ->orderByDesc('is_pinned')
                ->orderByDesc('created_at')
                ->paginate(12);
            return view('siswa.announcements', compact('announcements', 'student'));
        })->name('announcements');

        // Absensi (Siswa) — Riwayat & Konfirmasi Kehadiran
        Route::get('/attendance', [\App\Http\Controllers\Siswa\AttendanceController::class, 'index'])->name('attendance');
        Route::get('/attendance/{course}', [\App\Http\Controllers\Siswa\AttendanceController::class, 'show'])->name('attendance.show');
        Route::post('/attendance/{schedule}/confirm', [\App\Http\Controllers\Siswa\AttendanceController::class, 'confirmAttendance'])->name('attendance.confirm');

        // List Mata Pelajaran
        Route::get('/courses', [\App\Http\Controllers\Siswa\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/fees', [\App\Http\Controllers\Siswa\CourseController::class, 'fees'])->name('courses.fees');

        // Tagihan (Siswa)
        Route::get('/billing', [\App\Http\Controllers\Siswa\BillingController::class, 'index'])->name('billing.index');
        Route::post('/billing/{course}/pay', [\App\Http\Controllers\Siswa\BillingController::class, 'pay'])->name('billing.pay');
        Route::post('/billing/bulk', [\App\Http\Controllers\Siswa\BillingController::class, 'bulkPay'])->name('billing.bulk');

        // Tryout (CBT)
        Route::get('/tryout',                          [\App\Http\Controllers\Siswa\TryoutController::class, 'index']) ->name('tryout');
        Route::get('/tryout/{tryout}',                 [\App\Http\Controllers\Siswa\TryoutController::class, 'show'])  ->name('tryout.show');
        Route::post('/tryout/{tryout}/submit',         [\App\Http\Controllers\Siswa\TryoutController::class, 'submit'])->name('tryout.submit');
        Route::get('/tryout/{tryout}/result/{attempt}',[\App\Http\Controllers\Siswa\TryoutController::class, 'result'])->name('tryout.result');

        // Schedule Agreements (Proposals)
        Route::get('/schedule-agreements', [\App\Http\Controllers\Siswa\ScheduleProposalController::class, 'index'])->name('schedule-agreements.index');
        Route::post('/schedule-agreements', [\App\Http\Controllers\Siswa\ScheduleProposalController::class, 'store'])->name('schedule-agreements.store');
        Route::get('/schedule-agreements/class/{class}/meetings', [\App\Http\Controllers\Siswa\ScheduleProposalController::class, 'classMeetings'])->name('schedule-agreements.class-meetings');
        Route::post('/schedule-agreements/{proposal}/approve', [\App\Http\Controllers\Siswa\ScheduleProposalController::class, 'approve'])->name('schedule-agreements.approve');
        Route::post('/schedule-agreements/{proposal}/reject', [\App\Http\Controllers\Siswa\ScheduleProposalController::class, 'reject'])->name('schedule-agreements.reject');
    });

require __DIR__.'/auth.php';

// Global: leave impersonation
Route::post('/impersonate/leave', function () {
    $orig = session()->pull('impersonate.original_user');
    session()->forget('impersonate.branch_id');
    if ($orig) auth()->loginUsingId($orig);
    return redirect()->route('owner.branches.index');
})->name('impersonate.leave')->middleware('auth');
