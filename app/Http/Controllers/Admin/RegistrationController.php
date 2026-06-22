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
                $invoiceStatus = ($cicilanCount <= 1) ? 'lunas' : 'belum_bayar';

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
