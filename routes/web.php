<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
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

        /*
        |--------------------------------------------------------------------------
        | STUDENTS
        |--------------------------------------------------------------------------
        */

        Route::get('/students', [StudentController::class, 'index'])
            ->name('students.index');

        Route::post('/students', [StudentController::class, 'store'])
            ->name('students.store');

        Route::get('/students/{student}', [StudentController::class, 'show'])
            ->name('students.show');

        Route::put('/students/{student}', [StudentController::class, 'update'])
            ->name('students.update');

        Route::delete('/students/{student}', [StudentController::class, 'destroy'])
            ->name('students.destroy');

        /*
        |--------------------------------------------------------------------------
        | TEACHERS
        |--------------------------------------------------------------------------
        */

        Route::get('/teachers', [TeacherController::class, 'index'])
            ->name('teachers.index');

        Route::post('/teachers', [TeacherController::class, 'store'])
            ->name('teachers.store');

        Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])
            ->name('teachers.show');

        Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])
            ->name('teachers.update');

        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])
            ->name('teachers.destroy');

        /*
        |--------------------------------------------------------------------------
        | MENU LAIN
        |--------------------------------------------------------------------------
        */

        Route::get('/schedules', fn() => view('admin.schedules.index'))
            ->name('schedules.index');

        Route::get('/payments', fn() => view('admin.payments.index'))
            ->name('payments.index');

        Route::get('/tryouts', fn() => view('admin.tryouts.index'))
            ->name('tryouts.index');
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

        Route::put('/branches/{branch}', [BranchController::class, 'update'])
            ->name('branches.update');

        Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])
            ->name('branches.destroy');

        Route::get('/branches/export/excel', [BranchController::class, 'exportExcel'])
            ->name('branches.export.excel');

        Route::get('/branches/export/pdf', [BranchController::class, 'exportPdf'])
            ->name('branches.export.pdf');

        // RESET PASSWORD
        Route::post('/branches/{branch}/reset-password',
            [BranchController::class, 'resetPassword'])
            ->name('branches.resetPassword');

    });

require __DIR__.'/auth.php';