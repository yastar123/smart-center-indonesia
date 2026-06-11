<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $rooms = ChatRoom::with(['pesan' => fn($q) => $q->latest()->limit(1)])
            ->where(function ($q) {
                $q->whereJsonContains('peserta_id', auth()->id())
                  ->orWhere('jenis_room', 'broadcast');
            })
            ->orderByDesc('waktu_pesan_terakhir')
            ->get();

        // Get student
        $student = Student::where('user_id', auth()->id())->first();
        
        // Get teachers who teach subjects taken by this student, with their subjects
        $contacts = collect();
        if ($student) {
            // Get all class IDs where this student is enrolled
            $classIds = DB::table('class_students')
                ->where('student_id', $student->id)
                ->pluck('class_id');
            
            // Get teachers with their courses
            $contacts = DB::table('school_classes as sc')
                ->join('teachers as t', 't.id', '=', 'sc.guru_id')
                ->join('courses as c', 'c.id', '=', 'sc.mata_pelajaran_id')
                ->whereIn('sc.id', $classIds)
                ->where('t.status', 'aktif')
                ->select(
                    't.id as teacher_id',
                    't.name as teacher_name',
                    't.photo as teacher_photo',
                    'c.id as course_id',
                    'c.nama as course_name',
                    't.user_id'
                )
                ->orderBy('t.name')
                ->get()
                ->groupBy('teacher_id')
                ->map(function ($items, $teacherId) {
                    $first = $items->first();
                    return (object) [
                        'teacher_id' => $teacherId,
                        'teacher_name' => $first->teacher_name,
                        'teacher_photo' => $first->teacher_photo,
                        'user_id' => $first->user_id,
                        'courses' => $items->pluck('course_name')->toArray(),
                        'courses_str' => implode(', ', $items->pluck('course_name')->toArray()),
                    ];
                })
                ->values();
        }

        $messageBaseUrl = url('siswa/messages');
        $messageCreateRoute = route('siswa.messages.createRoom');
        $allowCreateRoom = false;
        return view('admin.messages.index', compact('rooms', 'contacts', 'messageBaseUrl', 'messageCreateRoute', 'allowCreateRoom'));
    }
}
