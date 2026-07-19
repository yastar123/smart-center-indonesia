<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ScheduleDashboardController;
use App\Http\Controllers\Admin\ScheduleListController;
use App\Http\Controllers\Admin\RescheduleController;
use App\Http\Controllers\Admin\RegistrationListController;
use App\Http\Controllers\Admin\StudentRegistrationController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\TryoutController;
use App\Http\Controllers\Public\StudentRegistrationPublicController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Owner\BranchController;
use App\Http\Controllers\Owner\SubjectController as OwnerSubjectController;
use App\Http\Controllers\Owner\CoursePackageController as OwnerCoursePackageController;
use App\Http\Controllers\Owner\AcademicModuleController as OwnerAcademicModuleController;
use App\Http\Controllers\Guru\AttendanceController;
use App\Http\Controllers\Siswa\SiswaController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
})->name('landing');

Route::get('/cabang/{branch}', [\App\Http\Controllers\Public\BranchLandingController::class, 'show'])
    ->name('cabang.show');

// ARTICLES — public pages
Route::get('/artikel', function () {
    $articles = \App\Models\Article::with('penulis')
        ->published()
        ->when(request('kategori'), fn($q) => $q->where('kategori', request('kategori')))
        ->latest('published_at')
        ->paginate(9);
    return view('articles.index', compact('articles'));
})->name('articles.index');

Route::get('/artikel/{slug}', function (string $slug) {
    $article = \App\Models\Article::with('penulis')
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    // Increment views
    $article->increment('views');

    // Related articles (same category, exclude current)
    $related = \App\Models\Article::published()
        ->where('kategori', $article->kategori)
        ->where('id', '!=', $article->id)
        ->latest('published_at')
        ->take(3)
        ->get();

    return view('articles.show', compact('article', 'related'));
})->name('articles.show');

Route::post('/public/student-registrations', [StudentRegistrationPublicController::class, 'store'])
    ->name('public.student-registrations.store');

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
        Route::get('/students/create',        [StudentController::class, 'create'])  ->name('students.create');
        Route::post('/students',             [StudentController::class, 'store'])   ->name('students.store');
        Route::get('/students/{student}/edit',[StudentController::class, 'edit'])    ->name('students.edit');
        Route::get('/students/{student}',    [StudentController::class, 'show'])    ->name('students.show');
        Route::put('/students/{student}',    [StudentController::class, 'update'])  ->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy']) ->name('students.destroy');

        // TEACHERS
        Route::get('/teachers',              [TeacherController::class, 'index'])   ->name('teachers.index');
        Route::get('/teachers/create',       [TeacherController::class, 'create'])  ->name('teachers.create');
        Route::post('/teachers',             [TeacherController::class, 'store'])   ->name('teachers.store');
        Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])   ->name('teachers.edit');
        Route::get('/teachers/{teacher}',    [TeacherController::class, 'show'])    ->name('teachers.show');
        Route::put('/teachers/{teacher}',    [TeacherController::class, 'update'])  ->name('teachers.update');
        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy']) ->name('teachers.destroy');

        // ATTENDANCE HISTORY (Admin) — removed

        // SCHEDULES (index redirects to create — list page removed)
        Route::get('/schedules',                    fn() => redirect()->route('admin.schedules.create')) ->name('schedules.index');
        Route::get('/schedules/create',             [ScheduleController::class, 'create'])  ->name('schedules.create');
        Route::post('/schedules',                   [ScheduleController::class, 'store'])   ->name('schedules.store');
        Route::post('/schedules/conflict-check',    [ScheduleController::class, 'conflictCheck'])->name('schedules.conflict-check');
        Route::get('/schedules/{schedule}/edit',    [ScheduleController::class, 'edit'])    ->name('schedules.edit');
        Route::get('/schedules/{schedule}',         [ScheduleController::class, 'show'])    ->name('schedules.show');
        Route::put('/schedules/{schedule}',         [ScheduleController::class, 'update'])  ->name('schedules.update');
        Route::delete('/schedules/{schedule}',      [ScheduleController::class, 'destroy']) ->name('schedules.destroy');

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

        // ATTENDANCE ADMIN (Manajemen Absensi)
        Route::get('/attendance',                         [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'packageIndex'])->name('attendance.index');
        Route::get('/attendance/sessions',                [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'index'])      ->name('attendance.sessions');
        Route::get('/attendance/{schedule}',              [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'show'])       ->name('attendance.show');
        Route::put('/attendance/{absensi}',               [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'update'])     ->name('attendance.update');
        Route::post('/attendance/{schedule}/bulk',        [\App\Http\Controllers\Admin\AdminAttendanceController::class, 'bulkUpdate']) ->name('attendance.bulk');

        // API untuk ambil mata pelajaran yang diambil siswa (dipakai di UI admin)
        Route::get('/students/{student}/courses',    [CertificateController::class, 'studentCourses']) ->name('students.courses');


        // CUTI & FREEZE PAKET (Admin)
        Route::get('/leave',                    [\App\Http\Controllers\Admin\LeaveController::class, 'index'])  ->name('leave.index');
        Route::patch('/leave/{leave}/approve',  [\App\Http\Controllers\Admin\LeaveController::class, 'approve'])->name('leave.approve');
        Route::patch('/leave/{leave}/reject',   [\App\Http\Controllers\Admin\LeaveController::class, 'reject']) ->name('leave.reject');

        // ROOMS (Fasilitas Ruangan)
        Route::get('/rooms',              [\App\Http\Controllers\Admin\RoomController::class, 'index'])   ->name('rooms.index');
        Route::get('/rooms/create',       [\App\Http\Controllers\Admin\RoomController::class, 'create'])  ->name('rooms.create');
        Route::post('/rooms',             [\App\Http\Controllers\Admin\RoomController::class, 'store'])   ->name('rooms.store');
        Route::get('/rooms/{room}',       [\App\Http\Controllers\Admin\RoomController::class, 'show'])    ->name('rooms.show');
        Route::get('/rooms/{room}/edit',  [\App\Http\Controllers\Admin\RoomController::class, 'edit'])    ->name('rooms.edit');
        Route::put('/rooms/{room}',       [\App\Http\Controllers\Admin\RoomController::class, 'update'])  ->name('rooms.update');
        Route::delete('/rooms/{room}',    [\App\Http\Controllers\Admin\RoomController::class, 'destroy']) ->name('rooms.destroy');

        // PACKAGES (Paket Belajar)
        Route::get('/packages',              [PackageController::class, 'index'])   ->name('packages.index');
        Route::post('/packages',             [PackageController::class, 'store'])   ->name('packages.store');
        Route::get('/packages/{package}',    [PackageController::class, 'show'])    ->name('packages.show');
        Route::put('/packages/{package}',    [PackageController::class, 'update'])  ->name('packages.update');
        Route::delete('/packages/{package}', [PackageController::class, 'destroy']) ->name('packages.destroy');

        // SALARIES (Gaji Guru)
        Route::middleware('role:admin|owner')->group(function () {
            Route::get('/salaries',             [SalaryController::class, 'index'])    ->name('salaries.index');
            Route::get('/salaries/create',      [SalaryController::class, 'create'])   ->name('salaries.create');
            Route::get('/salaries/teachers/{teacher}/packages', [SalaryController::class, 'teacherPackages']) ->name('salaries.teacher-packages');
            Route::post('/salaries',            [SalaryController::class, 'store'])    ->name('salaries.store');
            Route::get('/salaries/{salary}/edit', [SalaryController::class, 'edit'])    ->name('salaries.edit');
            Route::get('/salaries/{salary}',    [SalaryController::class, 'show'])     ->name('salaries.show');
            Route::put('/salaries/{salary}',    [SalaryController::class, 'update'])   ->name('salaries.update');
            Route::delete('/salaries/{salary}', [SalaryController::class, 'destroy'])  ->name('salaries.destroy');
            Route::get('/salaries/{salary}/slip', [SalaryController::class, 'printSlip'])->name('salaries.slip');
        });

        // ARTICLES (Artikel)
        Route::get('/articles',              [ArticleController::class, 'index'])   ->name('articles.index');
        Route::post('/articles',             [ArticleController::class, 'store'])   ->name('articles.store');
        Route::get('/articles/{article}',    [ArticleController::class, 'show'])    ->name('articles.show');
        Route::put('/articles/{article}',    [ArticleController::class, 'update'])  ->name('articles.update');
        Route::delete('/articles/{article}', [ArticleController::class, 'destroy']) ->name('articles.destroy');

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


        // SCHEDULE DASHBOARD (Visual calendar view)
        Route::get('/schedule',              [ScheduleDashboardController::class, 'index']) ->name('schedule-dashboard.index');

        // RESCHEDULE & AVAILABILITY
        Route::get('/reschedule',                       [RescheduleController::class, 'index'])   ->name('reschedule.index');
        Route::post('/reschedule/{proposal}/approve',   [RescheduleController::class, 'approve']) ->name('reschedule.approve');
        Route::post('/reschedule/{proposal}/reject',    [RescheduleController::class, 'reject'])  ->name('reschedule.reject');

        // REGISTRATION WIZARD (Registrasi Siswa Baru — 5 langkah + kirim akun ke WA)

        // REGISTRATION LIST (Daftar Registrasi dari Form Publik)
        Route::get('/registration-list',                                [RegistrationListController::class, 'index'])       ->name('registration-list.index');
        Route::get('/registration-list/create',                         [RegistrationListController::class, 'createNew'])   ->name('registration-list.create');
        Route::get('/registration-list/{registration}/process',         [RegistrationListController::class, 'process'])     ->name('registration-list.process');
        Route::post('/registration-list/{registration}/process',        [RegistrationListController::class, 'processStore'])->name('registration-list.process.store');
        Route::post('/registration-list/guru-conflict-check',            [RegistrationListController::class, 'guruConflictCheck'])->name('registration-list.guru-conflict-check');
        Route::get('/registration-list/student-search',                  [RegistrationListController::class, 'studentSearch'])->name('registration-list.student-search');
        Route::get('/registration-list/student-detail/{student}',        [RegistrationListController::class, 'studentDetail'])->name('registration-list.student-detail');
        Route::patch('/registration-list/student-update/{student}',      [RegistrationListController::class, 'studentUpdate'])->name('registration-list.student-update');

        // STUDENT REGISTRATIONS (pendaftar dari form publik)
        Route::get('/student-registrations/{studentRegistration}',        [StudentRegistrationController::class, 'show'])    ->name('student-registrations.show');
        Route::delete('/student-registrations/{studentRegistration}',      [StudentRegistrationController::class, 'destroy']) ->name('student-registrations.destroy');


        // BILLING (Manajemen E-Billing)
        Route::get('/billing',                   [BillingController::class, 'index'])   ->name('billing.index');
        Route::post('/billing',                  [BillingController::class, 'store'])   ->name('billing.store');
        Route::get('/billing/export',            [BillingController::class, 'export'])  ->name('billing.export');
        Route::get('/billing/{billing}',         [BillingController::class, 'show'])    ->name('billing.show');
        Route::put('/billing/{billing}',         [BillingController::class, 'update'])  ->name('billing.update');
        Route::delete('/billing/{billing}',      [BillingController::class, 'destroy']) ->name('billing.destroy');
        Route::get('/billing/payments/pending',  [\App\Http\Controllers\Admin\VerifikasiPembayaranController::class, 'index'])->name('billing.payments-pending');

        // SCHEDULE LIST & CREATE (aliases to existing schedules)
        Route::get('/schedule-list',   [ScheduleListController::class, 'index'])              ->name('schedule-list.index');
        Route::get('/schedule-create', fn() => redirect()->route('admin.schedules.create'))->name('schedule-create.index');

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
        Route::post('/landing/tickers',                                 [\App\Http\Controllers\Admin\LandingContentController::class, 'storeTicker'])          ->name('landing.tickers.store');
        Route::put('/landing/tickers/{ticker}',                         [\App\Http\Controllers\Admin\LandingContentController::class, 'updateTicker'])         ->name('landing.tickers.update');
        Route::delete('/landing/tickers/{ticker}',                      [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyTicker'])        ->name('landing.tickers.destroy');
        Route::post('/landing/features',                                [\App\Http\Controllers\Admin\LandingContentController::class, 'storeFeature'])         ->name('landing.features.store');
        Route::put('/landing/features/{feature}',                       [\App\Http\Controllers\Admin\LandingContentController::class, 'updateFeature'])        ->name('landing.features.update');
        Route::delete('/landing/features/{feature}',                    [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyFeature'])       ->name('landing.features.destroy');
        Route::post('/landing/jenjangs',                                [\App\Http\Controllers\Admin\LandingContentController::class, 'storeJenjang'])         ->name('landing.jenjangs.store');
        Route::put('/landing/jenjangs/{jenjang}',                       [\App\Http\Controllers\Admin\LandingContentController::class, 'updateJenjang'])        ->name('landing.jenjangs.update');
        Route::delete('/landing/jenjangs/{jenjang}',                    [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyJenjang'])       ->name('landing.jenjangs.destroy');
        Route::post('/landing/trusts',                                  [\App\Http\Controllers\Admin\LandingContentController::class, 'storeTrust'])           ->name('landing.trusts.store');
        Route::put('/landing/trusts/{trust}',                           [\App\Http\Controllers\Admin\LandingContentController::class, 'updateTrust'])          ->name('landing.trusts.update');
        Route::delete('/landing/trusts/{trust}',                        [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyTrust'])         ->name('landing.trusts.destroy');
        Route::post('/landing/highlights',                              [\App\Http\Controllers\Admin\LandingContentController::class, 'storeHighlight'])       ->name('landing.highlights.store');
        Route::put('/landing/highlights/{highlight}',                   [\App\Http\Controllers\Admin\LandingContentController::class, 'updateHighlight'])      ->name('landing.highlights.update');
        Route::delete('/landing/highlights/{highlight}',                [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyHighlight'])     ->name('landing.highlights.destroy');
        Route::post('/landing/galleries',                               [\App\Http\Controllers\Admin\LandingContentController::class, 'storeGallery'])        ->name('landing.galleries.store');
        Route::put('/landing/galleries/{gallery}',                      [\App\Http\Controllers\Admin\LandingContentController::class, 'updateGallery'])       ->name('landing.galleries.update');
        Route::delete('/landing/galleries/{gallery}',                   [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyGallery'])      ->name('landing.galleries.destroy');
        Route::post('/landing/faqs',                                    [\App\Http\Controllers\Admin\LandingContentController::class, 'storeFaq'])            ->name('landing.faqs.store');
        Route::put('/landing/faqs/{faq}',                               [\App\Http\Controllers\Admin\LandingContentController::class, 'updateFaq'])           ->name('landing.faqs.update');
        Route::delete('/landing/faqs/{faq}',                            [\App\Http\Controllers\Admin\LandingContentController::class, 'destroyFaq'])          ->name('landing.faqs.destroy');

        // BRANCH LANDING CONTENT
        Route::get('/landing/cabang',                                   [\App\Http\Controllers\Admin\BranchLandingContentController::class, 'index'])           ->name('landing.cabang.index');
        Route::post('/landing/cabang',                                  [\App\Http\Controllers\Admin\BranchLandingContentController::class, 'storeBranch'])      ->name('landing.cabang.store');
        Route::get('/landing/cabang/{branch}',                          [\App\Http\Controllers\Admin\BranchLandingContentController::class, 'show'])             ->name('landing.cabang.show');
        Route::put('/landing/cabang/{branch}',                          [\App\Http\Controllers\Admin\BranchLandingContentController::class, 'update'])           ->name('landing.cabang.update');
        Route::post('/landing/cabang/{branch}/info',                    [\App\Http\Controllers\Admin\BranchLandingContentController::class, 'updateBranch'])     ->name('landing.cabang.update-info');
        Route::delete('/landing/cabang/{branch}',                       [\App\Http\Controllers\Admin\BranchLandingContentController::class, 'destroyBranch'])    ->name('landing.cabang.destroy');

        // TAGIHAN SISWA (cicilan & pascabayar)
        Route::get('/tagihan-siswa', [\App\Http\Controllers\Admin\TagihanSiswaController::class, 'index'])->name('tagihan-siswa.index');
        Route::get('/tagihan-siswa/{kelas}', [\App\Http\Controllers\Admin\TagihanSiswaController::class, 'show'])->name('tagihan-siswa.show');
        Route::post('/tagihan-siswa/{kelas}/generate-invoice', [\App\Http\Controllers\Admin\TagihanSiswaController::class, 'generateInvoice'])->name('tagihan-siswa.generate-invoice');
        Route::get('/riwayat-guru-mengajar', [\App\Http\Controllers\Admin\TeacherHistoryController::class, 'index'])->name('riwayat-guru.index');
        Route::get('/riwayat-guru-mengajar/{teacher}', [\App\Http\Controllers\Admin\TeacherHistoryController::class, 'show'])->name('riwayat-guru.show');

        // RIWAYAT SESI — removed


        // AJAX: used sessions per package
        Route::get('/schedules/package/{package}/used-sessions', function(\App\Models\Package $package) {
            $used = \App\Models\Schedule::where('paket_id', $package->id)
                ->whereNull('deleted_at')
                ->orderBy('pertemuan_ke')
                ->get(['pertemuan_ke', 'tanggal', 'status', 'topik'])
                ->keyBy('pertemuan_ke')
                ->map(fn($s) => [
                    'tanggal' => $s->tanggal ? \Carbon\Carbon::parse($s->tanggal)->format('d/m/Y') : null,
                    'status'  => $s->status,
                    'topik'   => $s->topik,
                ]);
            return response()->json(['used' => $used]);
        })->name('schedules.package-used-sessions');

        // AJAX: students by package (primary source of truth)
        Route::get('/schedules/package/{package}/students', function(\App\Models\Package $package) {
            $students = \App\Models\Student::where('package_id', $package->id)
                ->where('status', 'aktif')
                ->orderBy('name')
                ->get(['id', 'name', 'nis', 'branch_id']);

            return response()->json([
                'students' => $students->map(fn($s) => [
                    'id'   => $s->id,
                    'name' => $s->name,
                    'nis'  => $s->nis,
                ]),
                'count' => $students->count(),
            ]);
        })->name('schedules.package-students');

        // AJAX: students by subject/course with remaining session count per subject
        Route::get('/schedules/subject/{course}/students', function(\App\Models\Course $course, \Illuminate\Http\Request $request) {
            // Determine effective branch: use course's cabang_id if set, else fall back to the
            // authenticated user's branch_id so global (cabang_id = null) courses still filter correctly.
            $userBranchId    = auth()->user()->branch_id;
            $effectiveBranch = $course->cabang_id ?? $userBranchId;

            // Find students via class enrollment: school_classes (mata_pelajaran_id) → class_students → students
            $classIds = \App\Models\SchoolClass::where('mata_pelajaran_id', $course->id)
                ->when($effectiveBranch, fn($q) => $q->where('cabang_id', $effectiveBranch))
                ->pluck('id');

            $studentIdsViaClass = \DB::table('class_students')
                ->whereIn('class_id', $classIds)
                ->pluck('student_id');

            // Also check student_course_payments (direct course enrollment)
            $studentIdsViaCourse = \DB::table('student_course_payments')
                ->where('course_id', $course->id)
                ->pluck('student_id');

            $allStudentIds = $studentIdsViaClass->merge($studentIdsViaCourse)->unique()->values();

            // Students table uses branch_id (not cabang_id). Apply branch scope in both paths
            // to prevent cross-branch data leaks in multi-tenant setups.
            if ($allStudentIds->isEmpty()) {
                // Fallback: all active students in the same branch
                $students = \App\Models\Student::when($effectiveBranch, fn($q) => $q->where('branch_id', $effectiveBranch))
                    ->where('status', 'aktif')
                    ->orderBy('name')
                    ->get(['id', 'name', 'nis', 'total_sesi', 'package_id', 'branch_id']);
            } else {
                $students = \App\Models\Student::whereIn('id', $allStudentIds)
                    ->when($effectiveBranch, fn($q) => $q->where('branch_id', $effectiveBranch))
                    ->where('status', 'aktif')
                    ->orderBy('name')
                    ->get(['id', 'name', 'nis', 'total_sesi', 'package_id', 'branch_id']);
            }

            $result = $students->map(function ($s) use ($course) {
                // Sessions used for this specific subject
                $used = \App\Models\AbsensiSiswa::where('siswa_id', $s->id)
                    ->whereHas('jadwal', fn($q) => $q->where('mata_pelajaran_id', $course->id))
                    ->whereIn('status', ['hadir'])
                    ->count();

                // Get session quota from their package for this course
                $packageSesi = \App\Models\Package::where('id', $s->package_id)
                    ->value('jumlah_pertemuan') ?? $s->total_sesi ?? 0;

                $sisa = max(0, $packageSesi - $used);

                return [
                    'id'          => $s->id,
                    'name'        => $s->name,
                    'nis'         => $s->nis,
                    'sesi_paket'  => $packageSesi,
                    'sesi_terpakai' => $used,
                    'sisa_sesi'   => $sisa,
                    'package_id'  => $s->package_id,
                ];
            });

            return response()->json(['success' => true, 'students' => $result, 'count' => $result->count()]);
        })->name('schedules.subject-students');

        // AJAX: teacher stats (sessions taught, subjects, earnings for freelance)
        Route::get('/schedules/teacher/{teacher}/stats', function(\App\Models\Teacher $teacher) {
            $schedulesDone = \App\Models\Schedule::where('guru_id', $teacher->id)
                ->whereIn('status', ['selesai', 'berlangsung'])
                ->count();

            $schedulesAll = \App\Models\Schedule::where('guru_id', $teacher->id)
                ->whereNull('deleted_at')
                ->count();

            $subjectIds = \App\Models\Schedule::where('guru_id', $teacher->id)
                ->whereNotNull('mata_pelajaran_id')
                ->distinct()
                ->pluck('mata_pelajaran_id');

            $subjects = \App\Models\Course::whereIn('id', $subjectIds)->pluck('nama')->toArray();

            $earnings = null;
            if ($teacher->jenis_guru === 'freelance') {
                $earnings = \App\Models\Schedule::where('guru_id', $teacher->id)
                    ->where('status', 'selesai')
                    ->whereNotNull('honor_per_sesi')
                    ->sum('honor_per_sesi');
            }

            return response()->json([
                'success'          => true,
                'jenis_guru'       => $teacher->jenis_guru,
                'salary_base'      => $teacher->salary_base,
                'sesi_selesai'     => $schedulesDone,
                'sesi_total'       => $schedulesAll,
                'subjects'         => $subjects,
                'subjects_count'   => count($subjects),
                'earnings'         => $earnings,
            ]);
        })->name('schedules.teacher-stats');

        // AJAX: students by teacher (fallback / legacy)
        Route::get('/schedules/teacher/{teacher}/students', function(\App\Models\Teacher $teacher, \Illuminate\Http\Request $request) {
            $packageId = $request->query('package_id');

            // Primary: if package_id given, return students enrolled in that package
            if ($packageId) {
                $students = \App\Models\Student::where('package_id', $packageId)
                    ->where('status', 'aktif')
                    ->orderBy('name')
                    ->get(['id', 'name', 'nis']);

                return response()->json($students->map(fn($s) => [
                    'id'      => $s->id,
                    'name'    => $s->name,
                    'classes' => [],
                ])->values());
            }

            // Fallback: find via school_classes assigned to teacher
            $classes = \App\Models\SchoolClass::where('guru_id', $teacher->id)
                ->with(['mataPelajaran', 'siswa'])
                ->get();

            $students = [];
            foreach ($classes as $class) {
                foreach ($class->siswa as $student) {
                    if (! isset($students[$student->id])) {
                        $students[$student->id] = [
                            'id'      => $student->id,
                            'name'    => $student->name,
                            'classes' => [],
                        ];
                    }
                    $label = $class->mataPelajaran?->nama ?? $class->nama_kelas;
                    if (! in_array($label, $students[$student->id]['classes'], true)) {
                        $students[$student->id]['classes'][] = $label;
                    }
                }
            }

            return response()->json(array_values($students));
        })->name('schedules.teacher-students');
    });

// CERTIFICATES — accessible by admin, owner, and guru
Route::middleware(['auth', 'role:admin|owner|guru'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/certificates',                            [\App\Http\Controllers\Admin\CertificateController::class, 'index'])         ->name('certificates.index');
        Route::post('/certificates',                          [\App\Http\Controllers\Admin\CertificateController::class, 'store'])         ->name('certificates.store');
        Route::get('/certificates/students/{student}',        [\App\Http\Controllers\Admin\CertificateController::class, 'studentDetail']) ->name('certificates.student');
        Route::post('/certificates/students/{student}/upload',[\App\Http\Controllers\Admin\CertificateController::class, 'uploadForStudent'])->name('certificates.student.upload');
        Route::get('/certificates/{certificate}',             [\App\Http\Controllers\Admin\CertificateController::class, 'show'])          ->name('certificates.show');
        Route::put('/certificates/{certificate}',             [\App\Http\Controllers\Admin\CertificateController::class, 'update'])        ->name('certificates.update');
        Route::delete('/certificates/{certificate}',          [\App\Http\Controllers\Admin\CertificateController::class, 'destroy'])       ->name('certificates.destroy');
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
        Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
        Route::get('/branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
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

        // PROMO & KONTEN
        Route::get('/promo',              [\App\Http\Controllers\Owner\PromoController::class, 'index'])   ->name('promo.index');
        Route::get('/promo/create',       [\App\Http\Controllers\Owner\PromoController::class, 'create'])  ->name('promo.create');
        Route::post('/promo',             [\App\Http\Controllers\Owner\PromoController::class, 'store'])   ->name('promo.store');
        Route::get('/promo/{promo}',      [\App\Http\Controllers\Owner\PromoController::class, 'show'])    ->name('promo.show');
        Route::get('/promo/{promo}/edit', [\App\Http\Controllers\Owner\PromoController::class, 'edit'])    ->name('promo.edit');
        Route::post('/promo/{promo}',     [\App\Http\Controllers\Owner\PromoController::class, 'update'])  ->name('promo.update');
        Route::delete('/promo/{promo}',   [\App\Http\Controllers\Owner\PromoController::class, 'destroy']) ->name('promo.destroy');

        // KURIKULUM & SILABUS
        Route::get('/curriculum',                         [\App\Http\Controllers\Owner\CurriculumController::class, 'index'])           ->name('curriculum.index');
        Route::get('/curriculum/create',                  [\App\Http\Controllers\Owner\CurriculumController::class, 'create'])          ->name('curriculum.create');
        Route::post('/curriculum',                        [\App\Http\Controllers\Owner\CurriculumController::class, 'store'])           ->name('curriculum.store');
        Route::get('/curriculum/{curriculum}',            [\App\Http\Controllers\Owner\CurriculumController::class, 'show'])            ->name('curriculum.show');
        Route::get('/curriculum/{curriculum}/edit',       [\App\Http\Controllers\Owner\CurriculumController::class, 'edit'])            ->name('curriculum.edit');
        Route::post('/curriculum/{curriculum}',           [\App\Http\Controllers\Owner\CurriculumController::class, 'update'])          ->name('curriculum.update');
        Route::delete('/curriculum/{curriculum}',         [\App\Http\Controllers\Owner\CurriculumController::class, 'destroy'])         ->name('curriculum.destroy');
        Route::post('/curriculum-chapter/{chapter}/pdf',  [\App\Http\Controllers\Owner\CurriculumController::class, 'uploadChapterPdf'])->name('curriculum.chapter.pdf');

        // MATA PELAJARAN (pindah dari admin)
        Route::get('/subject',               [OwnerSubjectController::class, 'index'])   ->name('subject.index');
        Route::get('/subject/create',        [OwnerSubjectController::class, 'create'])  ->name('subject.create');
        Route::post('/subject',              [OwnerSubjectController::class, 'store'])   ->name('subject.store');
        Route::get('/subject/{subject}',     [OwnerSubjectController::class, 'show'])    ->name('subject.show');
        Route::get('/subject/{subject}/edit',[OwnerSubjectController::class, 'edit'])    ->name('subject.edit');
        Route::put('/subject/{subject}',     [OwnerSubjectController::class, 'update'])  ->name('subject.update');
        Route::delete('/subject/{subject}',  [OwnerSubjectController::class, 'destroy']) ->name('subject.destroy');

        // PAKET BELAJAR (pindah dari admin)
        Route::get('/course-package',                        [OwnerCoursePackageController::class, 'index'])            ->name('course-package.index');
        Route::get('/course-package/create',                 [OwnerCoursePackageController::class, 'create'])           ->name('course-package.create');
        Route::post('/course-package',                       [OwnerCoursePackageController::class, 'store'])            ->name('course-package.store');
        Route::get('/course-package/{coursePackage}',        [OwnerCoursePackageController::class, 'show'])             ->name('course-package.show');
        Route::get('/course-package/{coursePackage}/edit',   [OwnerCoursePackageController::class, 'edit'])             ->name('course-package.edit');
        Route::put('/course-package/{coursePackage}',        [OwnerCoursePackageController::class, 'update'])           ->name('course-package.update');
        Route::delete('/course-package/{coursePackage}',     [OwnerCoursePackageController::class, 'destroy'])          ->name('course-package.destroy');
        Route::get('/course-package/{coursePackage}/course-teachers', [OwnerCoursePackageController::class, 'courseTeachersApi']) ->name('course-package.course-teachers');

        // MODUL AKADEMIK (pindah dari admin)
        Route::get('/module',               [OwnerAcademicModuleController::class, 'index'])   ->name('module.index');
        Route::get('/module/create',        [OwnerAcademicModuleController::class, 'create'])  ->name('module.create');
        Route::post('/module',              [OwnerAcademicModuleController::class, 'store'])   ->name('module.store');
        Route::get('/module/{module}',      [OwnerAcademicModuleController::class, 'show'])    ->name('module.show');
        Route::get('/module/{module}/edit', [OwnerAcademicModuleController::class, 'edit'])    ->name('module.edit');
        Route::put('/module/{module}',      [OwnerAcademicModuleController::class, 'update'])  ->name('module.update');
        Route::delete('/module/{module}',   [OwnerAcademicModuleController::class, 'destroy']) ->name('module.destroy');
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

        // NILAI SISWA (Guru)
        Route::get('/grades',         [\App\Http\Controllers\Guru\GradeController::class, 'index'])     ->name('grades.index');
        Route::post('/grades/batch',  [\App\Http\Controllers\Guru\GradeController::class, 'storeBatch'])->name('grades.storeBatch');

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

        // Kelas Saya
        Route::get('/kelas', [\App\Http\Controllers\Siswa\KelasController::class, 'index'])->name('kelas.index');

        // Harga Paket (courses fees kept for reference, courses.index kept for legacy links)
        Route::get('/courses', [\App\Http\Controllers\Siswa\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/fees', [\App\Http\Controllers\Siswa\CourseController::class, 'fees'])->name('courses.fees');

        // Tagihan (Siswa)
        Route::get('/billing', [\App\Http\Controllers\Siswa\BillingController::class, 'index'])->name('billing.index');
        Route::get('/billing/invoice/{invoice}', [\App\Http\Controllers\Siswa\BillingController::class, 'invoiceDetail'])->name('billing.invoice-detail');
        Route::post('/billing/invoice/{invoice}/upload', [\App\Http\Controllers\Siswa\BillingController::class, 'invoiceUpload'])->name('billing.invoice-upload');
        Route::post('/billing/{course}/pay', [\App\Http\Controllers\Siswa\BillingController::class, 'pay'])->name('billing.pay');
        Route::post('/billing/bulk', [\App\Http\Controllers\Siswa\BillingController::class, 'bulkPay'])->name('billing.bulk');

        // Tryout (CBT)
        Route::get('/tryout',                          [\App\Http\Controllers\Siswa\TryoutController::class, 'index']) ->name('tryout');
        Route::get('/tryout/{tryout}',                 [\App\Http\Controllers\Siswa\TryoutController::class, 'show'])  ->name('tryout.show');
        Route::post('/tryout/{tryout}/submit',         [\App\Http\Controllers\Siswa\TryoutController::class, 'submit'])->name('tryout.submit');
        Route::get('/tryout/{tryout}/result/{attempt}',[\App\Http\Controllers\Siswa\TryoutController::class, 'result'])->name('tryout.result');

        // CUTI & FREEZE (Siswa)
        Route::get('/leave',        [\App\Http\Controllers\Siswa\LeaveController::class, 'index']) ->name('leave.index');
        Route::get('/leave/create', [\App\Http\Controllers\Siswa\LeaveController::class, 'create'])->name('leave.create');
        Route::post('/leave',       [\App\Http\Controllers\Siswa\LeaveController::class, 'store']) ->name('leave.store');

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
