<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationListController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentRegistration::latest();

        if ($s = $request->search) {
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%")
                  ->orWhere('no_reg', 'like', "%$s%");
            });
        }
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->academic_status) {
            $query->where('academic_status', $request->academic_status);
        }
        if ($request->reg_status) {
            $query->where('status', $request->reg_status);
        }

        $registrations = $query->paginate(15)->appends($request->all());

        $stats = [
            'total'      => StudentRegistration::count(),
            'pending'    => StudentRegistration::where('status', 'pending')->count(),
            'verified'   => StudentRegistration::where('status', 'verified')->count(),
            'rejected'   => StudentRegistration::where('status', 'rejected')->count(),
        ];

        return view('admin.registration.registration-list', compact('registrations', 'stats'));
    }

    /** GET /admin/registration-list/{registration}/process — multi-step setup wizard */
    public function process(StudentRegistration $registration)
    {
        if ($registration->status === 'verified') {
            return redirect()->route('admin.registration-list.index')
                ->with('error', 'Pendaftaran ini sudah diverifikasi dan memiliki akun.');
        }

        $branches = Branch::orderBy('name')->get(['id', 'name']);

        $matchedBranch = null;
        if ($registration->branch) {
            $matchedBranch = Branch::where('name', 'like', '%' . $registration->branch . '%')->first();
        }
        if (!$matchedBranch && auth()->user()->branch_id) {
            $matchedBranch = Branch::find(auth()->user()->branch_id);
        }

        $interests = $registration->interests ?? [];

        $allCoursesFull = Course::with(['fee', 'guru'])->orderBy('nama')->get();

        // Some course names exist both as a branch-specific record and as a global
        // (cabang_id = null) master record. Without deduping, whereIn('nama', ...)
        // would match both and show the same mata pelajaran twice. Prefer the
        // branch-specific course when one exists, otherwise fall back to global.
        $courses = $allCoursesFull
            ->filter(fn ($c) => in_array($c->nama, $interests))
            ->when($matchedBranch, function ($collection) use ($matchedBranch) {
                return $collection->sortByDesc(fn ($c) => (int) ($c->cabang_id === $matchedBranch->id));
            })
            ->unique('nama')
            ->values();

        // Metadata (fee + guru) for the entire course catalog, used both to build the
        // "tambah mata pelajaran" dropdown and to let the admin re-add a mata pelajaran
        // after removing it — see admin.registration.process for the CRUD controls.
        $courseMeta = $allCoursesFull->map(function ($c) {
            return [
                'id'   => $c->id,
                'nama' => $c->nama,
                'fee'  => (float) ($c->fee->amount ?? 0),
                'guru' => $c->guru->map(fn ($t) => ['id' => $t->id, 'name' => $t->name])->values(),
            ];
        })->values();

        $packages = Package::where('status', 'aktif')->orderBy('nama')->get(['id', 'cabang_id', 'nama', 'harga', 'tipe_kelas', 'jumlah_pertemuan']);

        $rooms = Room::where('status', 'aktif')
            ->orderBy('nama_ruangan')
            ->get(['id', 'nama_ruangan', 'kapasitas']);

        return view('admin.registration.process', compact(
            'registration', 'branches', 'matchedBranch', 'courses', 'courseMeta', 'packages', 'rooms'
        ));
    }

    /**
     * AJAX: cek apakah seorang guru sudah punya jadwal lain yang bentrok pada
     * hari & jam yang sama, dipakai di wizard verifikasi pendaftaran (Langkah 3)
     * sebelum admin menetapkan guru untuk mata pelajaran baru.
     */
    public function guruConflictCheck(Request $request)
    {
        $validated = $request->validate([
            'guru_id'     => 'required|numeric',
            'hari'        => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
        ]);

        // Manual time comparison — safer than Laravel's after: rule for HH:MM strings
        if ($validated['jam_mulai'] >= $validated['jam_selesai']) {
            return response()->json(['success' => true, 'conflict' => false,
                'detail' => 'Jam berakhir harus setelah jam mulai.']);
        }

        $data = $validated;

        $overlap = Schedule::where('guru_id', $data['guru_id'])
            ->where('status', '!=', 'dibatalkan')
            ->whereNotNull('tanggal')
            ->whereRaw('EXTRACT(DOW FROM tanggal) = ?', [$data['hari']])
            ->where(function ($q) use ($data) {
                $q->whereBetween('jam_mulai', [$data['jam_mulai'], $data['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$data['jam_mulai'], $data['jam_selesai']])
                  ->orWhere(function ($i) use ($data) {
                      $i->where('jam_mulai', '<=', $data['jam_mulai'])
                        ->where('jam_selesai', '>=', $data['jam_selesai']);
                  });
            })
            ->with(['paket', 'mataPelajaran'])
            ->first();

        if (!$overlap) {
            return response()->json(['success' => true, 'conflict' => false]);
        }

        $hariNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];

        return response()->json([
            'success'  => true,
            'conflict' => true,
            'detail'   => sprintf(
                'Guru sudah mengajar %s pada %s %s–%s (%s)',
                $overlap->mataPelajaran->nama ?? ($overlap->paket->nama ?? 'kelas lain'),
                $hariNames[$data['hari']],
                substr($overlap->jam_mulai, 0, 5),
                substr($overlap->jam_selesai, 0, 5),
                $overlap->topik ?: 'tanpa topik'
            ),
        ]);
    }

    /** POST /admin/registration-list/{registration}/process — finalize setup, create account */
    public function processStore(Request $request, StudentRegistration $registration)
    {
        if ($registration->status === 'verified') {
            return response()->json(['success' => false, 'message' => 'Pendaftaran ini sudah diverifikasi.'], 422);
        }

        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'phone'                => 'required|string|max:30',
            'gender'               => 'nullable|in:L,P',
            'birth_place'          => 'nullable|string|max:255',
            'birth_date'           => 'nullable|date',
            'address'              => 'nullable|string',
            'parent_name'          => 'nullable|string|max:255',
            'parent_phone'         => 'nullable|string|max:30',
            'program'              => 'nullable|in:kelas,privat',
            'system'               => 'nullable|in:online,offline',
            'branch_id'            => 'required|exists:branches,id',
            'is_custom_package'    => 'nullable|in:0,1',
            'package_id'           => 'nullable|exists:packages,id',
            'custom_package_name'  => 'required_if:is_custom_package,1|nullable|string|max:150',
            'custom_jenis'         => 'required_if:is_custom_package,1|nullable|in:reguler,intensif,privat,online',
            'jumlah_pertemuan'     => 'nullable|integer|min:1',
            'custom_metode_absensi'=> 'nullable|in:manual,otomatis',
            'custom_tipe_kelas'    => 'nullable|in:offline,online,private',
            'custom_package_price' => 'nullable|numeric|min:0',
            'custom_status'        => 'nullable|in:aktif,nonaktif',
            'custom_deskripsi'     => 'nullable|string',
            'course_ids'           => 'required|array|min:1',
            'course_ids.*'         => 'exists:courses,id',
            'course_teacher'       => 'nullable|array',
            'course_teacher.*'     => 'nullable|exists:teachers,id',
            'course_sessions'      => 'nullable|array',
            'course_sessions.*'    => 'nullable|integer|min:0',
            'course_fee'           => 'nullable|array',
            'course_fee.*'         => 'nullable|numeric|min:0',
            'total_biaya'            => 'required|numeric|min:0',
            'biaya_per_sesi'         => 'nullable|numeric|min:0',
            'biaya_admin'            => 'nullable|numeric|min:0',
            'payment_status'         => 'required|in:belum_bayar,lunas',
            'payment_method'         => 'nullable|in:prabayar,pascabayar',
            'prabayar_type'          => 'nullable|in:lunas,cicilan',
            'cicilan_nominal'        => 'nullable|array',
            'cicilan_nominal.*'      => 'nullable|numeric|min:0',
            'cicilan_mulai'          => 'nullable|array',
            'cicilan_mulai.*'        => 'nullable|date',
            'cicilan_jatuh_tempo'    => 'nullable|array',
            'cicilan_jatuh_tempo.*'  => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $branch = Branch::find($data['branch_id']);

            // Persist any corrections the admin made to the student's own data
            // back onto the original registration record before using it.
            $registration->fill([
                'name'         => $data['name'],
                'phone'        => $data['phone'],
                'gender'       => $data['gender'] ?? $registration->gender,
                'birth_place'  => $data['birth_place'] ?? null,
                'birth_date'   => $data['birth_date'] ?? null,
                'address'      => $data['address'] ?? null,
                'parent_name'  => $data['parent_name'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
                'program'      => $data['program'] ?? $registration->program,
                'system'       => $data['system'] ?? $registration->system,
            ]);
            $registration->save();

            $baseName = Str::slug($registration->name, '.');
            $baseName = $baseName ?: 'siswa';
            $email    = strtolower($baseName) . '.' . now()->format('His') . '@siswa.akademi.com';
            $password = Str::random(8);

            $user = User::create([
                'name'      => $registration->name,
                'email'     => $email,
                'password'  => Hash::make($password),
                'phone'     => $registration->phone,
                'branch_id' => $branch->id,
                'is_active' => true,
            ]);
            $user->assignRole('siswa');

            do {
                $nis = 'S' . now()->format('YmdHis') . Str::upper(Str::random(3));
            } while (Student::where('nis', $nis)->exists());

            $courseSessions = $data['course_sessions'] ?? [];
            $totalSesi      = array_sum(array_map('intval', $courseSessions));

            // Resolve teacher(s) chosen per mata pelajaran before the package is created,
            // so a custom package can be linked to the primary teacher.
            $courseTeachers   = array_filter($data['course_teacher'] ?? []);
            $primaryTeacherId = $courseTeachers ? array_values($courseTeachers)[0] : null;

            // Paket Custom — create a new Package record in the master data instead of
            // reusing an existing one, mirroring the "Konfigurasi Kelas & Paket" flow used
            // in admin/registrasi-baru (RegistrationController@store).
            $resolvedPackageId = $data['package_id'] ?? null;
            if (($data['is_custom_package'] ?? '0') == '1' && !empty($data['custom_package_name'])) {
                $customPkg = Package::create([
                    'cabang_id'        => $branch->id,
                    'guru_id'          => $primaryTeacherId,
                    'nama'             => $data['custom_package_name'],
                    'deskripsi'        => $data['custom_deskripsi'] ?? null,
                    'harga'            => (float) ($data['custom_package_price'] ?? 0),
                    'jumlah_pertemuan' => (int) ($data['jumlah_pertemuan'] ?? 8),
                    'durasi_bulan'     => 3,
                    'jenis'            => $data['custom_jenis'] ?? 'privat',
                    'tipe_kelas'       => $data['custom_tipe_kelas'] ?? 'offline',
                    'metode_absensi'   => $data['custom_metode_absensi'] ?? 'manual',
                    'status'           => $data['custom_status'] ?? 'aktif',
                ]);
                if (!empty($data['course_ids'])) {
                    $customPkg->mataPelajaran()->syncWithoutDetaching($data['course_ids']);
                }
                $resolvedPackageId = $customPkg->id;
            }

            $student = Student::create([
                'user_id'                => $user->id,
                'nis'                    => $nis,
                'name'                   => $registration->name,
                'gender'                 => $registration->gender ?? 'L',
                'phone'                  => $registration->phone,
                'birth_place'            => $registration->birth_place,
                'birth_date'             => $registration->birth_date,
                'address'                => $registration->address,
                'parent_name'            => $registration->parent_name,
                'parent_phone'           => $registration->parent_phone,
                'branch_id'              => $branch->id,
                'package_id'             => $resolvedPackageId,
                'total_sesi'             => $totalSesi ?: null,
                'status'                 => 'aktif',
                'join_date'              => now()->toDateString(),
                'kategori_peserta_didik' => $registration->education_level,
            ]);

            // Assign each chosen teacher to the student (one row per course's teacher)
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
            $baseCount = Invoice::whereYear('created_at', $year)->whereMonth('created_at', date('m'))->count();
            $totalBiaya    = (float) $data['total_biaya'];
            $paymentMethod = $data['payment_method'] ?? 'prabayar';
            $prabayarType  = $data['prabayar_type']  ?? 'lunas';
            $deskripsi     = 'Biaya Pendaftaran Program: ' . implode(', ', $registration->interests ?? [$registration->program ?? 'Umum']);

            $invoice = null; // will hold the first/primary invoice

            if ($paymentMethod === 'prabayar' && $prabayarType === 'cicilan') {
                // ── Cicilan: create N invoice records, one per installment ──
                $cicilanNominals = array_values(array_filter($data['cicilan_nominal'] ?? [], fn($v) => $v !== null && $v !== ''));
                $cicilanMulai    = array_values($data['cicilan_mulai']          ?? []);
                $cicilanTempo    = array_values($data['cicilan_jatuh_tempo']    ?? []);
                foreach ($cicilanNominals as $idx => $nominal) {
                    $seqCount  = $baseCount + $idx + 1;
                    $nomor     = 'INV-CIC-' . $year . $month . '-' . str_pad($seqCount, 3, '0', STR_PAD_LEFT) . '-' . ($idx + 1);
                    $tempo     = !empty($cicilanTempo[$idx]) ? $cicilanTempo[$idx] : now()->addDays(30 * ($idx + 1))->toDateString();
                    $inv = Invoice::create([
                        'siswa_id'      => $student->id,
                        'cabang_id'     => $branch->id,
                        'kelas_id'      => null,
                        'nomor_invoice' => $nomor,
                        'deskripsi'     => $deskripsi . ' — Cicilan ' . ($idx + 1) . ' dari ' . count($cicilanNominals),
                        'subtotal'      => (float) $nominal,
                        'diskon'        => 0,
                        'pajak'         => 0,
                        'total'         => (float) $nominal,
                        'status'        => 'belum_bayar',
                        'jatuh_tempo'   => $tempo,
                        'periode'       => date('Y-m'),
                        'catatan'       => 'Cicilan ' . ($idx + 1) . ' dari ' . count($cicilanNominals),
                    ]);
                    if ($idx === 0) $invoice = $inv;
                }
            } elseif ($paymentMethod === 'pascabayar') {
                // ── Pascabayar: invoice for admin fee (Rp 0 if none), per-session invoicing later ──
                $biayaAdmin = (float) ($data['biaya_admin'] ?? 0);
                $nomor      = 'INV-REG-' . $year . $month . '-' . str_pad($baseCount + 1, 3, '0', STR_PAD_LEFT);
                $invoice    = Invoice::create([
                    'siswa_id'      => $student->id,
                    'cabang_id'     => $branch->id,
                    'kelas_id'      => null,
                    'nomor_invoice' => $nomor,
                    'deskripsi'     => $deskripsi . ' (Pascabayar per sesi)',
                    'subtotal'      => $biayaAdmin,
                    'diskon'        => 0,
                    'pajak'         => 0,
                    'total'         => $biayaAdmin,
                    'status'        => $biayaAdmin > 0 ? 'belum_bayar' : 'lunas',
                    'jatuh_tempo'   => now()->addDays(7),
                    'periode'       => date('Y-m'),
                    'catatan'       => 'Pascabayar — invoice sesi digenerate otomatis dari jurnal mengajar.',
                ]);
            } else {
                // ── Prabayar Lunas (default) ──
                $nomor   = 'INV-REG-' . $year . $month . '-' . str_pad($baseCount + 1, 3, '0', STR_PAD_LEFT);
                $invoice = Invoice::create([
                    'siswa_id'      => $student->id,
                    'cabang_id'     => $branch->id,
                    'kelas_id'      => null,
                    'nomor_invoice' => $nomor,
                    'deskripsi'     => $deskripsi,
                    'subtotal'      => $totalBiaya,
                    'diskon'        => 0,
                    'pajak'         => 0,
                    'total'         => $totalBiaya,
                    'status'        => $data['payment_status'],
                    'jatuh_tempo'   => $data['payment_status'] === 'lunas' ? now() : now()->addDays(7),
                    'periode'       => date('Y-m'),
                    'catatan'       => $data['payment_status'] === 'lunas' ? 'Dibayar lunas saat proses registrasi.' : null,
                ]);
            }

            $registration->update([
                'status'               => 'verified',
                'student_id'           => $student->id,
                'branch'               => $branch->name,
                'assigned_teacher_id'  => $primaryTeacherId,
                'interest_teachers'    => $courseTeachers ?: null,
                'interest_sessions'    => $courseSessions ?: null,
                'total_sessions'       => $totalSesi ?: null,
                'biaya_per_sesi'       => $data['biaya_per_sesi'] ?? null,
                'total_biaya'          => $totalBiaya,
                'invoice_id'           => $invoice->id,
                'payment_status'       => $data['payment_status'],
                'academic_status'      => $data['payment_status'] === 'lunas' ? 'terjadwal' : 'menunggu_kelas',
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Pendaftaran berhasil diproses. Akun siswa telah dibuat.',
                'name'     => $registration->name,
                'email'    => $email,
                'password' => $password,
                'nis'      => $nis,
                'phone'    => $registration->phone,
                'no_reg'   => $registration->no_reg,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memproses: ' . $e->getMessage()], 500);
        }
    }
}
