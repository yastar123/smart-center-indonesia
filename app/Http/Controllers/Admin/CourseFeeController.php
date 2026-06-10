<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseFeeController extends Controller
{
    public function index()
    {
        $courses = Course::with('cabang')->get();
        $fees = DB::table('course_fees')->pluck('amount','course_id')->toArray();
        return view('admin.courses.fees', compact('courses','fees'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate(['amount' => 'nullable|numeric|min:0']);
        DB::table('course_fees')->updateOrInsert(
            ['course_id' => $course->id],
            ['amount' => $data['amount'] ?? 0, 'updated_at' => now(), 'created_at' => now()]
        );
        return redirect()->route('admin.courses.fees')->with('success','Biaya mata pelajaran disimpan.');
    }
}
