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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $teachers = Teacher::with('branch')
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();
        $students = Student::with(['branch', 'package.cabang', 'package.guru'])
            ->orderBy('name')
            ->get();

        return view('admin.registration.create', compact(
            'branches', 'packages', 'courses', 'teachers', 'students'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabang_id'          => 'required|exists:branches,id',
            'is_new_student'     => 'required|in:0,1',
            'jenis'              => 'required|in:offline,online,private',
            'guru_id'            => 'required|exists:teachers,id',
            'tanggal_mulai'      => 'required|date',
            'billing_mode'       => 'required|in:prepaid,postpaid',
        ]);

        $namaKelas = trim((string) $request->input('nama_kelas'));
        if ($namaKelas === '') {
            $courseName = optional(\App\Models\Course::find($request->course_id))->nama;
            $packageName = optional(\App\Models\Package::find($request->package_id))->nama;
            $teacherName = optional(\App\Models\Teacher::find($request->guru_id))->name;

            $namaKelas = collect([
                $packageName ?: $courseName,
                $teacherName,
            ])
                ->filter(fn ($value) => filled($value))
                ->implode(' - ');

            if ($namaKelas === '') {
                $namaKelas = 'Kelas ' . now()->format('Y-m-d');
            }
        }

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

                $baseEmail = Str::slug($request->student_name, '.')
                    ?: 'siswa';
                $email = strtolower($baseEmail) . '.' . time() . '@siswa.local';
                $password = 'password';

                $user = User::create([
                    'name'     => $request->student_name,
                    'email'    => $email,
                    'password' => bcrypt($password),
                    'phone'    => $request->student_phone,
                    'branch_id' => $request->cabang_id,
                    'is_active' => true,
                ]);
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('siswa');
                }

                do {
                    $nis = 'S' . now()->format('YmdHis') . Str::upper(Str::random(3));
                } while (Student::where('nis', $nis)->exists());

                $student = Student::create([
                    'user_id'       => $user->id,
                    'nis'           => $nis,
                    'name'          => $request->student_name,
                    'gender'        => 'L',
                    'phone'         => $request->student_phone,
                    'parent_name'   => $request->wali_name,
                    'parent_phone'  => $request->wali_phone,
                    'branch_id'     => $request->cabang_id,
                    'status'        => 'aktif',
                    'join_date'     => now()->toDateString(),
                ]);

                $temporaryCredentials = "Email sementara: {$email} | Password sementara: {$password}";
            } else {
                $request->validate(['student_id' => 'required|exists:students,id']);
                $student = Student::findOrFail($request->student_id);
                $temporaryCredentials = null;
            }

            if ($request->filled('package_id')) {
                $student->update([
                    'package_id' => $request->package_id,
                ]);
            }

            $kelas = SchoolClass::create([
                'cabang_id'         => $request->cabang_id,
                'mata_pelajaran_id' => $request->course_id ?? null,
                'guru_id'           => $request->guru_id,
                'nama_kelas'        => $namaKelas,
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
                $year  = date('Y');
                $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
                $count = Invoice::whereYear('created_at', $year)->whereMonth('created_at', date('m'))->count() + 1;
                $nomor = 'INV-' . $year . '-' . $month . str_pad($count, 3, '0', STR_PAD_LEFT);

                Invoice::create([
                    'siswa_id'      => $student->id,
                    'cabang_id'     => $request->cabang_id,
                    'nomor_invoice' => $nomor,
                    'deskripsi'     => 'Registrasi: ' . $namaKelas,
                    'subtotal'      => $totalTagihan,
                    'diskon'        => 0,
                    'pajak'         => 0,
                    'total'         => $totalTagihan,
                    'status'        => 'belum_bayar',
                    'jatuh_tempo'   => Carbon::parse($request->tanggal_mulai)->addDays(7),
                    'periode'       => date('Y-m'),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.registration.create')
                ->with('success', $temporaryCredentials
                    ? 'Registrasi berhasil disimpan. ' . $temporaryCredentials
                    : 'Registrasi berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan registrasi: ' . $e->getMessage());
        }
    }
}
