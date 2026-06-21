<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Package;
use App\Models\Course;
use App\Models\Branch;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::with(['cabang', 'guru', 'mataPelajaran', 'siswa']);

        if ($s = $request->search) {
            $query->where('nama_kelas', 'like', "%$s%");
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $registrations = $query->latest()->paginate(15)->appends($request->all());
        $branches = Branch::orderBy('name')->get();

        $stats = [
            'total'   => SchoolClass::count(),
            'aktif'   => SchoolClass::where('status', 'aktif')->count(),
            'privat'  => SchoolClass::where('jenis', 'private')->count(),
            'reguler' => SchoolClass::where('jenis', 'offline')->count(),
        ];

        return view('admin.registration.list', compact('registrations', 'branches', 'stats'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        $packages = Package::where('status', 'aktif')->orderBy('nama')->get();
        $courses  = Course::where('status', 'aktif')->orderBy('nama')->get();
        $teachers = Teacher::where('status', 'aktif')->orderBy('name')->get();
        $students = Student::orderBy('name')->get();

        return view('admin.registration.create', compact(
            'branches', 'packages', 'courses', 'teachers', 'students'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabang_id'          => 'required|exists:branches,id',
            'is_new_student'     => 'required|in:0,1',
            'nama_kelas'         => 'required|string|max:150',
            'jenis'              => 'required|in:offline,online,private',
            'guru_id'            => 'required|exists:teachers,id',
            'tanggal_mulai'      => 'required|date',
            'billing_mode'       => 'required|in:prepaid,postpaid',
        ]);

        DB::beginTransaction();
        try {
            $student = null;
            if ($request->is_new_student == '1') {
                $request->validate([
                    'student_name'  => 'required|string|max:100',
                    'student_phone' => 'nullable|string|max:20',
                    'wali_name'     => 'nullable|string|max:100',
                    'wali_phone'    => 'nullable|string|max:20',
                ]);

                $user = \App\Models\User::create([
                    'name'     => $request->student_name,
                    'email'    => strtolower(str_replace(' ', '.', $request->student_name)) . '.' . time() . '@siswa.local',
                    'password' => bcrypt('password'),
                ]);
                $user->assignRole('siswa');

                $student = Student::create([
                    'user_id'      => $user->id,
                    'name'         => $request->student_name,
                    'phone'        => $request->student_phone,
                    'wali_name'    => $request->wali_name,
                    'wali_phone'   => $request->wali_phone,
                    'branch_id'    => $request->cabang_id,
                    'status'       => 'aktif',
                ]);
            } else {
                $request->validate(['student_id' => 'required|exists:students,id']);
                $student = Student::findOrFail($request->student_id);
            }

            $kelas = SchoolClass::create([
                'cabang_id'         => $request->cabang_id,
                'mata_pelajaran_id' => $request->course_id ?? null,
                'guru_id'           => $request->guru_id,
                'nama_kelas'        => $request->nama_kelas,
                'kapasitas'         => $request->jenis === 'private' ? 1 : ($request->kapasitas ?? 15),
                'jumlah_pertemuan'  => $request->jumlah_pertemuan ?? 8,
                'jenis'             => $request->jenis,
                'status'            => 'aktif',
            ]);

            if ($student) {
                $kelas->siswa()->attach($student->id);
            }

            $packagePrice = (float)($request->package_price ?? 0);
            $adminFee     = ($request->is_new_student == '1') ? 150000 : 0;
            $totalTagihan = $packagePrice + $adminFee;

            if ($request->billing_mode === 'prepaid' && $totalTagihan > 0) {
                Invoice::create([
                    'siswa_id'          => $student->id,
                    'cabang_id'         => $request->cabang_id,
                    'keterangan'        => 'Registrasi: ' . $request->nama_kelas,
                    'jumlah'            => $totalTagihan,
                    'status'            => 'belum_bayar',
                    'tanggal_tagihan'   => $request->tanggal_mulai,
                    'tanggal_jatuh_tempo' => Carbon::parse($request->tanggal_mulai)->addDays(7),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.registration.create')
                ->with('success', 'Registrasi berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan registrasi: ' . $e->getMessage());
        }
    }
}
