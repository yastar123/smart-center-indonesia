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
use App\Http\Controllers\Owner\BranchController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.avatar');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // STUDENTS
        Route::get('/students',           [StudentController::class, 'index'])  ->name('students.index');
        Route::post('/students',          [StudentController::class, 'store'])  ->name('students.store');
        Route::get('/students/{student}', [StudentController::class, 'show'])   ->name('students.show');
        Route::put('/students/{student}', [StudentController::class, 'update']) ->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

        // TEACHERS
        Route::get('/teachers',           [TeacherController::class, 'index'])  ->name('teachers.index');
        Route::post('/teachers',          [TeacherController::class, 'store'])  ->name('teachers.store');
        Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])   ->name('teachers.show');
        Route::put('/teachers/{teacher}', [TeacherController::class, 'update']) ->name('teachers.update');
        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

        // PAYMENTS (Invoices)
        Route::get('/payments',              [PaymentController::class, 'index'])   ->name('payments.index');
        Route::post('/payments',             [PaymentController::class, 'store'])   ->name('payments.store');
        Route::get('/payments/{payment}',    [PaymentController::class, 'show'])    ->name('payments.show');
        Route::put('/payments/{payment}',    [PaymentController::class, 'update'])  ->name('payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']) ->name('payments.destroy');
        Route::post('/payments/{invoice}/pay', [PaymentController::class, 'markPaid'])->name('payments.pay');

        // SCHEDULES
        Route::get('/schedules',              [ScheduleController::class, 'index'])   ->name('schedules.index');
        Route::post('/schedules',             [ScheduleController::class, 'store'])   ->name('schedules.store');
        Route::get('/schedules/{schedule}',   [ScheduleController::class, 'show'])    ->name('schedules.show');
        Route::put('/schedules/{schedule}',   [ScheduleController::class, 'update'])  ->name('schedules.update');
        Route::delete('/schedules/{schedule}',[ScheduleController::class, 'destroy']) ->name('schedules.destroy');

        // COURSES (Mata Pelajaran)
        Route::get('/courses',            [CourseController::class, 'index'])   ->name('courses.index');
        Route::post('/courses',           [CourseController::class, 'store'])   ->name('courses.store');
        Route::get('/courses/{course}',   [CourseController::class, 'show'])    ->name('courses.show');
        Route::put('/courses/{course}',   [CourseController::class, 'update'])  ->name('courses.update');
        Route::delete('/courses/{course}',[CourseController::class, 'destroy']) ->name('courses.destroy');

        // CATEGORIES for courses
        Route::get('/categories',           [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories',          [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}',[\App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('categories.show');
        Route::put('/categories/{category}',[\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}',[\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        // CLASSES (Kelas)
        Route::get('/classes',           [SchoolClassController::class, 'index'])   ->name('classes.index');
        Route::post('/classes',          [SchoolClassController::class, 'store'])   ->name('classes.store');
        Route::get('/classes/{class}',   [SchoolClassController::class, 'show'])    ->name('classes.show');
        Route::put('/classes/{class}',   [SchoolClassController::class, 'update'])  ->name('classes.update');
        Route::delete('/classes/{class}',[SchoolClassController::class, 'destroy']) ->name('classes.destroy');

        // CERTIFICATES (Sertifikat)
        Route::get('/certificates',                  [CertificateController::class, 'index'])   ->name('certificates.index');
        Route::post('/certificates',                 [CertificateController::class, 'store'])   ->name('certificates.store');
        Route::get('/certificates/{certificate}',    [CertificateController::class, 'show'])    ->name('certificates.show');
        Route::put('/certificates/{certificate}',    [CertificateController::class, 'update'])  ->name('certificates.update');
        Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy']) ->name('certificates.destroy');

        // TRYOUTS (placeholder route kept)
        Route::get('/tryouts', fn() => view('admin.tryouts.index'))->name('tryouts.index');

        // REPORTS
        Route::get('/reports', fn() => view('admin.reports.index'))->name('reports.index');
    });

/*
|--------------------------------------------------------------------------
| OWNER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {

        Route::get('/branches', [BranchController::class, 'index'])
            ->name('branches.index');

        // Branch-specific dashboard for owner to view branch details
        Route::get('/branches/{branch}/dashboard', [BranchController::class, 'dashboard'])
            ->name('branches.dashboard');
        
        // impersonation: owner can act as branch admin
        Route::post('/branches/{branch}/impersonate', [BranchController::class, 'impersonate'])
            ->name('branches.impersonate');

        // (leave impersonation route is defined globally below so it remains accessible
        // even while impersonating)

        Route::post('/branches', [BranchController::class, 'store'])
            ->name('branches.store');

        // Static routes MUST come before {branch} wildcard
        Route::get('/branches/export/excel', [BranchController::class, 'exportExcel'])
            ->name('branches.export.excel');

        Route::get('/branches/export/pdf', [BranchController::class, 'exportPdf'])
            ->name('branches.export.pdf');

        Route::put('/branches/{branch}', [BranchController::class, 'update'])
            ->name('branches.update');

        Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])
            ->name('branches.destroy');

        Route::post('/branches/{branch}/reset-password',
            [BranchController::class, 'resetPassword'])
            ->name('branches.resetPassword');

        Route::get('/activity-log', function () {
            $activities = \Spatie\Activitylog\Models\Activity::with('causer')
                ->latest()
                ->paginate(20);
            return view('owner.activity-log', compact('activities'));
        })->name('activity-log');

        Route::get('/settings', function () {
            return view('owner.settings');
        })->name('settings.index');

        Route::get('/analytics', fn() => view('owner.analytics'))->name('analytics');
    });

/*
|--------------------------------------------------------------------------
| GURU PORTAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard',  fn() => view('guru.dashboard'))->name('dashboard');
        Route::get('/attendance', fn() => view('guru.attendance'))->name('attendance');
        Route::get('/grades',     fn() => view('guru.grades'))->name('grades');
    });

/*
|--------------------------------------------------------------------------
| SISWA PORTAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('siswa.dashboard'))->name('dashboard');
        Route::get('/schedule',  fn() => view('siswa.schedule'))->name('schedule');
        Route::get('/tryout',    fn() => view('siswa.coming-soon', [
            'title' => 'Tryout Online',
            'icon'  => 'bi-pencil-square',
            'desc'  => 'Fitur CBT (Computer Based Test) online sedang dalam pengembangan aktif.',
        ]))->name('tryout');
    });

require __DIR__.'/auth.php';

// Global route to leave impersonation — kept outside owner group so it is
// accessible while impersonating (current user may be branch admin).
Route::post('/impersonate/leave', function () {
    $orig = session()->pull('impersonate.original_user');
    session()->forget('impersonate.branch_id');
    if ($orig) {
        auth()->loginUsingId($orig);
    }
    return redirect()->route('owner.branches.index');
})->name('impersonate.leave')->middleware('auth');
