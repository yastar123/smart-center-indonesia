<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Owner\BranchController;

Route::get('/', function () {
    return view('auth.login');
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
        Route::get('/dashboard', fn() => view('guru.dashboard'))->name('dashboard');
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
    });

require __DIR__.'/auth.php';
