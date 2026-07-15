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

}
