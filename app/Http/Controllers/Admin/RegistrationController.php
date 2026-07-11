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

    /** GET /admin/registrasi-baru — new 5-step wizard: Informasi Siswa, Paket Kelas, Mapel & Guru, Pembayaran, Preview */
    public function wizardCreate()
    {
        $branches = Branch::orderBy('name')->get(['id', 'name']);
        $packages = Package::where('status', 'aktif')->orderBy('nama')->get(['id', 'cabang_id', 'nama', 'harga', 'tipe_kelas', 'jumlah_pertemuan']);
        $courses  = Course::where('status', 'aktif')->with(['fee', 'guru'])->orderBy('nama')->get();

        return view('admin.registration.wizard', compact('branches', 'packages', 'courses'));
    }

    /** POST /admin/registrasi-baru — create student account, invoice, then return credentials for WA send */
    public function wizardStore(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:100',
            'phone'                => 'required|string|max:20',
            'gender'               => 'required|in:L,P',
            'birth_place'          => 'nullable|string|max:100',
            'birth_date'           => 'nullable|date',
            'address'              => 'nullable|string|max:255',
            'parent_name'          => 'nullable|string|max:100',
            'parent_phone'         => 'nullable|string|max:20',
            'branch_id'            => 'required|exists:branches,id',
            'package_id'           => 'nullable|exists:packages,id',
            'course_ids'           => 'required|array|min:1',
            'course_ids.*'         => 'exists:courses,id',
            'course_teacher'       => 'nullable|array',
            'course_teacher.*'     => 'nullable|exists:teachers,id',
            'course_sessions'      => 'nullable|array',
            'course_sessions.*'    => 'nullable|integer|min:0',
            'course_fee'           => 'nullable|array',
            'course_fee.*'         => 'nullable|numeric|min:0',
            'total_biaya'          => 'required|numeric|min:0',
            'biaya_per_sesi'       => 'nullable|numeric|min:0',
            'payment_status'       => 'required|in:belum_bayar,lunas',
        ]);

        DB::beginTransaction();
        try {
            $branch = Branch::find($data['branch_id']);

            $baseName = Str::slug($data['name'], '.');
            $baseName = $baseName ?: 'siswa';
            $email    = strtolower($baseName) . '.' . now()->format('His') . '@siswa.akademi.com';
            $password = Str::random(8);

            $user = User::create([
                'name'      => $data['name'],
                'email'     => $email,
                'password'  => bcrypt($password),
                'phone'     => $data['phone'],
                'branch_id' => $branch->id,
                'is_active' => true,
            ]);
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('siswa');
            }

            do {
                $nis = 'S' . now()->format('YmdHis') . Str::upper(Str::random(3));
            } while (Student::where('nis', $nis)->exists());

            $courseSessions = $data['course_sessions'] ?? [];
            $totalSesi      = array_sum(array_map('intval', $courseSessions));

            $student = Student::create([
                'user_id'                => $user->id,
                'nis'                    => $nis,
                'name'                   => $data['name'],
                'gender'                 => $data['gender'],
                'phone'                  => $data['phone'],
                'birth_place'            => $data['birth_place'] ?? null,
                'birth_date'             => $data['birth_date'] ?? null,
                'address'                => $data['address'] ?? null,
                'parent_name'            => $data['parent_name'] ?? null,
                'parent_phone'           => $data['parent_phone'] ?? null,
                'branch_id'              => $branch->id,
                'package_id'             => $data['package_id'] ?? null,
                'total_sesi'             => $totalSesi ?: null,
                'status'                 => 'aktif',
                'join_date'              => now()->toDateString(),
            ]);

            $courseTeachers = array_filter($data['course_teacher'] ?? []);
            foreach (array_unique($courseTeachers) as $teacherId) {
                DB::table('student_teachers')->insertOrIgnore([
                    'student_id' => $student->id,
                    'teacher_id' => $teacherId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $year  = date('Y');
            $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
            $count = Invoice::whereYear('created_at', $year)->whereMonth('created_at', date('m'))->count() + 1;
            $nomor = 'INV-REG-' . $year . $month . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $totalBiaya = (float) $data['total_biaya'];
            $courseNames = Course::whereIn('id', $data['course_ids'])->pluck('nama')->implode(', ');

            Invoice::create([
                'siswa_id'      => $student->id,
                'cabang_id'     => $branch->id,
                'kelas_id'      => null,
                'nomor_invoice' => $nomor,
                'deskripsi'     => 'Biaya Pendaftaran Program: ' . ($courseNames ?: 'Umum'),
                'subtotal'      => $totalBiaya,
                'diskon'        => 0,
                'pajak'         => 0,
                'total'         => $totalBiaya,
                'status'        => $data['payment_status'],
                'jatuh_tempo'   => $data['payment_status'] === 'lunas' ? now() : now()->addDays(7),
                'periode'       => date('Y-m'),
                'catatan'       => $data['payment_status'] === 'lunas' ? 'Dibayar lunas saat proses registrasi.' : null,
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Registrasi berhasil. Akun siswa telah dibuat.',
                'name'     => $student->name,
                'email'    => $email,
                'password' => $password,
                'nis'      => $nis,
                'phone'    => $student->phone,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memproses: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabang_id'          => 'required|exists:branches,id',
            'is_new_student'     => 'required|in:0,1',
            'jenis'              => 'required|in:offline,online,private',
            'guru_id'            => 'required|exists:teachers,id',
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

            // Handle custom package — create a Package record in DB
            $resolvedPackageId = $request->package_id;
            if ($request->is_custom_package == '1' && $request->filled('custom_package_name')) {
                $customPkg = Package::create([
                    'cabang_id'         => $request->cabang_id,
                    'guru_id'           => $request->guru_id,
                    'nama'              => $request->custom_package_name,
                    'deskripsi'         => $request->custom_deskripsi ?? null,
                    'harga'             => (float)($request->custom_package_price ?? 0),
                    'jumlah_pertemuan'  => (int)($request->jumlah_pertemuan ?? 8),
                    'durasi_bulan'      => 3,
                    'jenis'             => $request->custom_jenis ?? 'privat',
                    'tipe_kelas'        => $request->custom_tipe_kelas ?? ($request->jenis ?? 'offline'),
                    'metode_absensi'    => $request->custom_metode_absensi ?? 'manual',
                    'status'            => $request->custom_status ?? 'aktif',
                ]);
                $courseIds = array_filter((array)$request->custom_course_ids);
                if (!empty($courseIds)) {
                    $customPkg->mataPelajaran()->syncWithoutDetaching($courseIds);
                }
                $resolvedPackageId = $customPkg->id;
            }

            if ($resolvedPackageId) {
                $student->update(['package_id' => $resolvedPackageId]);
            }

            // Determine billing_mode for SchoolClass
            $billingMode = $request->billing_mode === 'postpaid' ? 'postpaid'
                : (((int)($request->cicilan ?? 1)) > 1 ? 'cicilan' : 'prepaid');

            $kelas = SchoolClass::create([
                'cabang_id'         => $request->cabang_id,
                'mata_pelajaran_id' => $request->course_id ?? null,
                'guru_id'           => $request->guru_id,
                'nama_kelas'        => $namaKelas,
                'kapasitas'         => $request->jenis === 'private' ? 1 : ($request->kapasitas ?? 15),
                'jumlah_pertemuan'  => $request->jumlah_pertemuan ?? 8,
                'jenis'             => $request->jenis,
                'status'            => 'aktif',
                'billing_mode'      => $billingMode,
            ]);

            if ($student) {
                $kelas->siswa()->attach($student->id);
            }

            $packagePrice = (float)($request->package_price ?? 0);
            $totalTagihan = $packagePrice;

            if ($request->billing_mode === 'prepaid' && $totalTagihan > 0) {
                $year  = date('Y');
                $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
                $count = Invoice::whereYear('created_at', $year)->whereMonth('created_at', date('m'))->count() + 1;
                $nomor = 'INV-' . $year . '-' . $month . str_pad($count, 3, '0', STR_PAD_LEFT);

                $cicilanCount  = (int)($request->cicilan ?? 1);
                $invoiceStatus = 'belum_bayar';

                $firstAmount = $totalTagihan;
                if ($cicilanCount > 1 && $request->filled('cicilan_pertama')) {
                    $parsedFirst = (float) str_replace(['.', ','], ['', '.'], $request->cicilan_pertama);
                    if ($parsedFirst > 0) {
                        $firstAmount = $parsedFirst;
                    }
                }

                $desc = $cicilanCount > 1
                    ? 'Registrasi (Cicilan 1/' . $cicilanCount . '): ' . $namaKelas
                    : 'Registrasi: ' . $namaKelas;

                Invoice::create([
                    'siswa_id'      => $student->id,
                    'cabang_id'     => $request->cabang_id,
                    'kelas_id'      => $kelas->id,
                    'nomor_invoice' => $nomor,
                    'deskripsi'     => $desc,
                    'subtotal'      => $firstAmount,
                    'diskon'        => 0,
                    'pajak'         => 0,
                    'total'         => $firstAmount,
                    'status'        => $invoiceStatus,
                    'jatuh_tempo'   => Carbon::now()->addDays(7),
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
