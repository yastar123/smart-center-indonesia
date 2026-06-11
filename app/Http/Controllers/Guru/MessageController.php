<?php

namespace App\Http\Controllers\Guru;

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

        // Get teacher
        $teacher = Teacher::where('user_id', auth()->id())->first();
        
        // Get students who take subjects taught by this teacher, with their subjects
        $contacts = collect();
        if ($teacher) {
            // Get all class IDs where this teacher teaches
            $classIds = SchoolClass::where('guru_id', $teacher->id)->pluck('id');
            
            // Get students with their courses
            $contacts = DB::table('class_students as cs')
                ->join('students as s', 's.id', '=', 'cs.student_id')
                ->join('school_classes as sc', 'sc.id', '=', 'cs.class_id')
                ->join('courses as c', 'c.id', '=', 'sc.mata_pelajaran_id')
                ->whereIn('cs.class_id', $classIds)
                ->where('s.status', 'aktif')
                ->select(
                    's.id as student_id',
                    's.name as student_name',
                    's.photo as student_photo',
                    'c.id as course_id',
                    'c.nama as course_name',
                    's.user_id'
                )
                ->orderBy('s.name')
                ->get()
                ->groupBy('student_id')
                ->map(function ($items, $studentId) {
                    $first = $items->first();
                    return (object) [
                        'student_id' => $studentId,
                        'student_name' => $first->student_name,
                        'student_photo' => $first->student_photo,
                        'user_id' => $first->user_id,
                        'courses' => $items->pluck('course_name')->toArray(),
                        'courses_str' => implode(', ', $items->pluck('course_name')->toArray()),
                    ];
                })
                ->values();
        }

        $messageBaseUrl = url('guru/messages');
        $messageCreateRoute = route('guru.messages.createRoom');
        $allowCreateRoom = false;
        return view('admin.messages.index', compact('rooms', 'contacts', 'messageBaseUrl', 'messageCreateRoute', 'allowCreateRoom'));
    }
}
