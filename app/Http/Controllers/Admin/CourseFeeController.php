<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseFee;
use Illuminate\Http\Request;

class CourseFeeController extends Controller
{
    public function index()
    {
        $courses = Course::with(['cabang', 'fee'])->get();

        return view('admin.courses.fees', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id|unique:course_fees,course_id',
            'amount'    => 'required|numeric|min:0',
        ]);

        CourseFee::create($data);

        return redirect()->route('admin.courses.fees')->with('success', 'Biaya mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate(['amount' => 'nullable|numeric|min:0']);

        CourseFee::updateOrCreate(
            ['course_id' => $course->id],
            ['amount' => $data['amount'] ?? 0]
        );

        return redirect()->route('admin.courses.fees')->with('success', 'Biaya mata pelajaran disimpan.');
    }

    public function destroy(CourseFee $fee)
    {
        $fee->delete();

        return redirect()->route('admin.courses.fees')->with('success', 'Biaya mata pelajaran dihapus.');
    }
}
