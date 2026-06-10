<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BillingController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) return redirect()->route('dashboard');

        $courses = Course::whereIn('id', function($q) use ($student) {
            $q->select('mata_pelajaran_id')->from('school_classes')
                ->whereIn('id', function($q2) use ($student) {
                    $q2->select('class_id')->from('class_students')->where('student_id', $student->id);
                });
        })->get();

        $fees = DB::table('course_fees')->pluck('amount','course_id')->toArray();
        $payments = DB::table('student_course_payments')->where('student_id', $student->id)->get()->keyBy('course_id');

        return view('siswa.billing.index', compact('courses','fees','payments'));
    }

    public function pay(Request $request, Course $course)
    {
        $student = Student::where('user_id', auth()->id())->first();
        if (! $student) return redirect()->route('siswa.attendance');

        $data = $request->validate([
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $path = $data['proof']->store('payments', 'public');

        DB::table('student_course_payments')->insert([
            'student_id' => $student->id,
            'course_id'  => $course->id,
            'amount'     => DB::table('course_fees')->where('course_id', $course->id)->value('amount') ?? 0,
            'proof'      => $path,
            'status'     => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success','Bukti pembayaran berhasil diunggah. Menunggu verifikasi.');
    }
}
