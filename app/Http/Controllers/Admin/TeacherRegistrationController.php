<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherRegistration;
use Illuminate\Http\Request;

class TeacherRegistrationController extends Controller
{
    public function verify(TeacherRegistration $teacherRegistration)
    {
        $teacherRegistration->update([
            'status' => 'verified',
        ]);

        $prefill = $teacherRegistration->only([
            'name',
            'nig',
            'gender',
            'birth_date',
            'education',
            'phone',
            'email',
            'branch_id',
            'address',
            'jenis_guru',
            'salary_base',
            'course_ids',
        ]);

        return redirect()->route('admin.teachers.create')
            ->withInput($prefill)
            ->with('success', 'Data guru baru telah diteruskan ke form tambah guru.');
    }

    public function destroy(TeacherRegistration $teacherRegistration)
    {
        $teacherRegistration->delete();

        return redirect()->back()->with('success', 'Pendaftaran guru berhasil dihapus.');
    }
}
