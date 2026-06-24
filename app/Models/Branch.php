<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\ChatRoom;
use App\Models\Payment;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\Schedule;
use App\Models\Salary;
use App\Models\Tryout;
use App\Models\SchoolClass;
use App\Models\User;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'city',
        'regency',
        'address',
        'phone',
        'email',
        'password',
        'status',

        'can_students',
        'can_teachers',
        'can_schedules',
        'can_payments',
        'can_tryouts',
        'allowed_pages',
    ];

    protected $casts = [
        'can_students' => 'boolean',
        'can_teachers' => 'boolean',
        'can_schedules' => 'boolean',
        'can_payments' => 'boolean',
        'can_tryouts' => 'boolean',
        'allowed_pages' => 'array',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'branch_id');
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'cabang_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    protected static function booted()
    {
        static::deleting(function (Branch $branch) {
            try {
                // delete students and teachers
                Student::where('branch_id', $branch->id)->each(fn($m) => $m->delete());
                Teacher::where('branch_id', $branch->id)->each(fn($m) => $m->delete());

                // delete models referencing cabang_id
                Certificate::where('cabang_id', $branch->id)->delete();
                Course::where('cabang_id', $branch->id)->delete();
                ChatRoom::where('cabang_id', $branch->id)->delete();
                Payment::where('cabang_id', $branch->id)->delete();
                Package::where('cabang_id', $branch->id)->delete();
                Invoice::where('cabang_id', $branch->id)->delete();
                Schedule::where('cabang_id', $branch->id)->delete();
                Salary::where('cabang_id', $branch->id)->delete();
                Tryout::where('cabang_id', $branch->id)->delete();
                SchoolClass::where('cabang_id', $branch->id)->delete();

                // delete user accounts that belong to this branch (non-owner accounts)
                User::where('branch_id', $branch->id)->each(fn($u) => $u->delete());
            } catch (\Throwable $e) {
                Log::error('Failed cascading delete for branch '.$branch->id, ['err' => $e->getMessage()]);
            }
        });
    }
}