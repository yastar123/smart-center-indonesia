<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Course;
use App\Models\TeacherRegistration;
use Illuminate\Http\Request;

class TeacherRegistrationPublicController extends Controller
{
    public function create()
    {
        $branches = Branch::where('status', 'active')->orderBy('name')->get();
        $courses = Course::where('status', 'aktif')->orderBy('nama')->get();

        return view('public.teacher-registration', compact('branches', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'nig' => ['required', 'string', 'max:50', 'unique:teacher_registrations,nig'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['nullable', 'date'],
            'education' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'unique:teacher_registrations,email'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'branch' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'jenis_guru' => ['nullable', 'in:kontrak,freelance'],
            'salary_base' => ['nullable', 'numeric', 'min:0'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:courses,id'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('teacher-registrations/cv', 'public');
        }

        $registration = TeacherRegistration::create([
            'no_reg' => 'TG-' . now()->format('YmdHis') . '-' . rand(100, 999),
            'name' => $data['name'],
            'nig' => $data['nig'],
            'gender' => $data['gender'],
            'birth_date' => $data['birth_date'] ?? null,
            'education' => $data['education'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'branch_id' => $data['branch_id'] ?? null,
            'branch' => $data['branch'] ?? null,
            'address' => $data['address'] ?? null,
            'jenis_guru' => $data['jenis_guru'] ?? null,
            'salary_base' => $data['salary_base'] ?? 0,
            'course_ids' => array_values(array_filter(array_map('intval', $data['course_ids'] ?? []))),
            'cv_path' => $cvPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran guru berhasil dikirim dan menunggu verifikasi admin.',
            'data' => [
                'id' => $registration->id,
                'no_reg' => $registration->no_reg,
            ],
        ], 201);
    }
}
