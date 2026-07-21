<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\SchoolClass;
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

    /**
     * GET /admin/registration-list/create — entry point for admin-initiated registration
     * (replaces the old standalone /admin/registration-create page). Creates a blank
     * pending lead and immediately redirects into the process wizard, where the admin
     * picks "Daftar Siswa Baru" or "Siswa Lama" in Langkah 1.
     */
    public function createNew()
    {
        $registration = StudentRegistration::create([
            'no_reg'          => 'REG-' . now()->format('YmdHis') . '-' . rand(100, 999),
            'name'            => 'Siswa Baru',
            'status'          => 'pending',
            'academic_status' => 'pending',
            'payment_status'  => 'belum_bayar',
            'branch'          => optional(Branch::find(auth()->user()->branch_id))->name,
        ]);

        return redirect()->route('admin.registration-list.process', $registration);
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
                'guru' => $c->guru->map(fn ($t) => [
                    'id'          => $t->id,
                    'name'        => $t->name,
                    'jenis_guru'  => $t->jenis_guru,
                    'salary_base' => (float) ($t->salary_base ?? 0),
                ])->values(),
            ];
        })->values();

        $packages = Package::where('status', 'aktif')->orderBy('nama')->get(['id', 'cabang_id', 'nama', 'harga', 'tipe_kelas', 'jumlah_pertemuan']);

        $rooms = Room::where('status', 'aktif')
            ->orderBy('nama_ruangan')
            ->get(['id', 'nama_ruangan', 'kapasitas']);

        $activeClassesByCourse = [];
        if ($matchedBranch) {
            $activeClasses = SchoolClass::with(['guru', 'siswa.user', 'jadwal'])
                ->where('cabang_id', $matchedBranch->id)
                ->where('status', 'aktif')
                ->orderBy('nama_kelas')
                ->get();

            foreach ($courses as $course) {
                $activeClassesByCourse[$course->id] = $activeClasses
                    ->filter(fn ($klass) => (int) $klass->mata_pelajaran_id === (int) $course->id)
                    ->map(function ($klass) {
                        return $klass->setRelation('siswa', $klass->siswa)->only([
                            'id', 'nama_kelas', 'guru', 'siswa', 'jumlah_pertemuan', 'jadwal', 'jenis'
                        ]);
                    })
                    ->values();
            }
        }

        return view('admin.registration.process', compact(
            'registration', 'branches', 'matchedBranch', 'courses', 'courseMeta', 'packages', 'rooms', 'activeClassesByCourse'
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

    /** AJAX: ambil detail lengkap satu siswa untuk panel edit "Siswa Lama" */
    public function studentDetail(Student $student)
    {
        $student->load(['branch:id,name', 'user:id,email']);
        return response()->json([
            'success' => true,
            'student' => [
                'id'                     => $student->id,
                'nis'                    => $student->nis,
                'name'                   => $student->name,
                'phone'                  => $student->phone,
                'gender'                 => $student->gender,
                'birth_place'            => $student->birth_place,
                'birth_date'             => $student->birth_date?->format('Y-m-d'),
                'address'                => $student->address,
                'parent_name'            => $student->parent_name,
                'parent_phone'           => $student->parent_phone,
                'kategori_peserta_didik' => $student->kategori_peserta_didik,
                'status'                 => $student->status,
                'branch_id'              => $student->branch_id,
                'branch_name'            => $student->branch->name ?? null,
                'email'                  => $student->user->email ?? null,
            ],
        ]);
    }

    /** AJAX PATCH: update data siswa dari panel "Siswa Lama" di wizard pendaftaran */
    public function studentUpdate(Request $request, Student $student)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:100',
            'phone'                  => 'nullable|string|max:20',
            'gender'                 => 'nullable|in:L,P',
            'birth_place'            => 'nullable|string|max:100',
            'birth_date'             => 'nullable|date',
            'address'                => 'nullable|string',
            'parent_name'            => 'nullable|string|max:100',
            'parent_phone'           => 'nullable|string|max:20',
            'kategori_peserta_didik' => 'nullable|string',
            'status'                 => 'nullable|in:aktif,nonaktif',
            'branch_id'              => 'nullable|exists:branches,id',
        ]);

        $student->update($data);

        if ($student->user) {
            $student->user->update([
                'name'      => $data['name'],
                'phone'     => $data['phone'] ?? $student->user->phone,
                'branch_id' => $data['branch_id'] ?? $student->user->branch_id,
                'is_active' => ($data['status'] ?? 'aktif') === 'aktif',
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Data siswa berhasil diperbarui.']);
    }

    /**
     * AJAX: cari siswa lama (nama/no HP/NIS) untuk step "Siswa Lama" pada wizard
     * proses pendaftaran — supaya admin bisa mendaftarkan program/kelas baru untuk
     * siswa yang sudah punya akun tanpa membuat akun & data siswa duplikat.
     */
    public function studentSearch(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['students' => []]);
        }

        $students = Student::with('branch:id,name')
            ->where(function ($query) use ($q) {
                $query->where('name', 'ilike', "%{$q}%")
                    ->orWhere('phone', 'ilike', "%{$q}%")
                    ->orWhere('nis', 'ilike', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'phone', 'nis', 'branch_id']);

        return response()->json([
            'students' => $students->map(fn ($s) => [
                'id'     => $s->id,
                'name'   => $s->name,
                'phone'  => $s->phone,
                'nis'    => $s->nis,
                'branch' => $s->branch->name ?? null,
            ])->values(),
        ]);
    }

    /** POST /admin/registration-list/{registration}/process — finalize setup, create account */
    public function processStore(Request $request, StudentRegistration $registration)
    {
        if ($registration->status === 'verified') {
            return response()->json(['success' => false, 'message' => 'Pendaftaran ini sudah diverifikasi.'], 422);
        }

        $data = $request->validate([
            'registration_type'    => 'nullable|in:baru,lama',
            'existing_student_id'  => 'required_if:registration_type,lama|nullable|exists:students,id',
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
            'education_level'      => 'nullable|string|max:255',
            'tempat_belajar'       => 'nullable|in:kantor,rumah',
            'hari_belajar'         => 'nullable|array',
            'hari_belajar.*'       => 'nullable|string|max:20',
            'jam_detail'           => 'nullable|array',
            'is_custom_package'    => 'nullable|in:0,1',
            'package_mode'         => 'nullable|in:standard,custom,request',
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
            'course_honor'         => 'nullable|array',
            'course_honor.*'       => 'nullable|numeric|min:0',
            'course_use_honor'     => 'nullable|array',
            'course_use_honor.*'   => 'nullable|in:0,1,true,false',
            'schedule_hari'        => 'nullable|array',
            'schedule_hari.*'      => 'nullable|string|max:20',
            'schedule_jam_mulai'   => 'nullable|array',
            'schedule_jam_mulai.*' => 'nullable',
            'schedule_jam_selesai' => 'nullable|array',
            'schedule_jam_selesai.*' => 'nullable',
            'schedule_room'        => 'nullable|array',
            'schedule_room.*'      => 'nullable|exists:rooms,id',
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
            // Branch is no longer a user-supplied field on this wizard — resolve it
            // server-side the same way the process() view matches it, with a final
            // fallback so verification/account creation never fails for missing branch_id.
            $branch = null;
            if ($registration->branch) {
                $branch = Branch::where('name', 'like', '%' . $registration->branch . '%')->first();
            }
            if (!$branch && auth()->user()->branch_id) {
                $branch = Branch::find(auth()->user()->branch_id);
            }
            if (!$branch) {
                $branch = Branch::orderBy('id')->first();
            }
            if (!$branch) {
                throw new \Exception('Tidak ada data cabang di sistem. Tambahkan cabang terlebih dahulu sebelum memproses pendaftaran.');
            }

            // Persist any corrections the admin made to the student's own data
            // back onto the original registration record before using it.
            // Build schedule_time string from jam_detail array
            $scheduleTime = null;
            if (!empty($data['jam_detail'])) {
                $lines = [];
                foreach ($data['jam_detail'] as $day => $slots) {
                    $slots = array_filter((array) $slots);
                    if (!empty($slots)) {
                        $lines[] = $day . ': ' . implode(' dan ', $slots);
                    }
                }
                $scheduleTime = implode("\n", $lines) ?: null;
            }

            $registration->fill([
                'name'            => $data['name'],
                'phone'           => $data['phone'],
                'gender'          => $data['gender'] ?? $registration->gender,
                'birth_place'     => $data['birth_place'] ?? null,
                'birth_date'      => $data['birth_date'] ?? null,
                'address'         => $data['address'] ?? null,
                'parent_name'     => $data['parent_name'] ?? null,
                'parent_phone'    => $data['parent_phone'] ?? null,
                'program'         => $data['program'] ?? $registration->program,
                'system'          => $data['system'] ?? $registration->system,
                'education_level' => $data['education_level'] ?? $registration->education_level,
                'learning_place'  => $data['tempat_belajar'] ?? $registration->learning_place,
                'day_preferences' => $data['hari_belajar'] ?? $registration->day_preferences ?? [],
                'schedule_time'   => $scheduleTime ?? $registration->schedule_time,
            ]);
            $registration->save();

            // "Siswa Lama": admin picked an existing student instead of registering a
            // brand-new one — reuse that Student + User record so a returning student
            // enrolling in a new program/kelas never ends up with a duplicate account.
            $isExistingStudent = ($data['registration_type'] ?? 'baru') === 'lama'
                && !empty($data['existing_student_id']);

            $existingStudent = null;
            if ($isExistingStudent) {
                $existingStudent = Student::with('user')->find($data['existing_student_id']);
                if (!$existingStudent || !$existingStudent->user) {
                    throw new \Exception('Siswa lama yang dipilih tidak valid atau tidak memiliki akun.');
                }
                // Keep using the existing student's own branch for package/class
                // creation, so their class stays consistent with their cabang.
                $branch = Branch::find($existingStudent->branch_id) ?? $branch;
            }

            $email    = null;
            $password = null;

            if ($isExistingStudent) {
                $user = $existingStudent->user;
                $nis  = $existingStudent->nis;
            } else {
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
            }

            $courseSessions = $data['course_sessions'] ?? [];
            $totalSesi      = array_sum(array_map('intval', $courseSessions));

            // Resolve teacher(s) chosen per mata pelajaran before the package is created,
            // so a custom package can be linked to the primary teacher.
            $courseTeachers   = array_filter($data['course_teacher'] ?? []);
            $primaryTeacherId = $courseTeachers ? array_values($courseTeachers)[0] : null;

            // Paket Custom / Request — create a new Package record in the master data instead of
            // reusing an existing one, mirroring the "Konfigurasi Kelas & Paket" flow used
            // in admin/registrasi-baru (RegistrationController@store).
            $resolvedPackageId = $data['package_id'] ?? null;
            $packageMode = $data['package_mode'] ?? (($data['is_custom_package'] ?? '0') == '1' ? 'custom' : 'standard');
            if (in_array($packageMode, ['custom', 'request'], true) && !empty($data['custom_package_name'])) {
                $deskripsi = $data['custom_deskripsi'] ?? null;
                if ($packageMode === 'request') {
                    $deskripsi = trim(($deskripsi ? $deskripsi . "\n" : '') . 'Paket dibuat melalui request admin saat proses pendaftaran siswa.');
                }

                $customPkg = Package::create([
                    'cabang_id'        => $branch->id,
                    'guru_id'          => $primaryTeacherId,
                    'nama'             => $data['custom_package_name'],
                    'deskripsi'        => $deskripsi,
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

            if ($isExistingStudent) {
                // Update the existing student's record with any corrections made on
                // the form and their new package, but never touch join_date/nis —
                // this is an additional enrollment, not a fresh registration.
                $existingStudent->fill([
                    'gender'                 => $registration->gender ?? $existingStudent->gender,
                    'phone'                  => $registration->phone ?? $existingStudent->phone,
                    'birth_place'            => $registration->birth_place ?? $existingStudent->birth_place,
                    'birth_date'             => $registration->birth_date ?? $existingStudent->birth_date,
                    'address'                => $registration->address ?? $existingStudent->address,
                    'parent_name'            => $registration->parent_name ?? $existingStudent->parent_name,
                    'parent_phone'           => $registration->parent_phone ?? $existingStudent->parent_phone,
                    'package_id'             => $resolvedPackageId ?: $existingStudent->package_id,
                    'total_sesi'             => $totalSesi ? ($existingStudent->total_sesi ?? 0) + $totalSesi : $existingStudent->total_sesi,
                    'status'                 => 'aktif',
                    'kategori_peserta_didik' => $registration->education_level ?? $existingStudent->kategori_peserta_didik,
                ]);
                $existingStudent->save();
                $student = $existingStudent;
            } else {
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
            }

            // Assign each chosen teacher to the student (one row per course's teacher)
            foreach (array_unique($courseTeachers) as $teacherId) {
                DB::table('student_teachers')->insertOrIgnore([
                    'student_id' => $student->id,
                    'teacher_id' => $teacherId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Auto-enroll the student into a class for each chosen course — reuse an
            // existing active class for the same branch+course+teacher, or create one
            // (mirroring SchoolClassController@store's auto-naming), then attach the
            // student via class_students. This is what actually places a newly
            // verified student "into their class", not just gives them an account.
            $jenisKelas = ($data['system'] ?? null) === 'online'
                ? 'online'
                : ((($data['program'] ?? null) === 'privat') ? 'private' : 'offline');

            $courseHonor  = $data['course_honor']         ?? [];
            $courseUseHonor = $data['course_use_honor']   ?? [];
            $scheduleHari = $data['schedule_hari']        ?? [];
            $scheduleJamMulai   = $data['schedule_jam_mulai']   ?? [];
            $scheduleJamSelesai = $data['schedule_jam_selesai'] ?? [];
            $scheduleRoom = $data['schedule_room']        ?? [];
            $programBelajarSchedule = ($data['program'] ?? null) === 'privat' ? 'private' : 'kelas';

            foreach ($data['course_ids'] as $courseId) {
                $teacherId = $courseTeachers[$courseId] ?? $primaryTeacherId ?? null;
                $selectedClassId = (int) ($data['course_class'][$courseId] ?? 0);
                $schoolClass = null;
                $isNewClass = false;

                if ($selectedClassId > 0) {
                    $schoolClass = SchoolClass::where('id', $selectedClassId)
                        ->where('cabang_id', $branch->id)
                        ->where('status', 'aktif')
                        ->first();
                }

                if (!$schoolClass) {
                    $classQuery = SchoolClass::where('cabang_id', $branch->id)
                        ->where('mata_pelajaran_id', $courseId)
                        ->where('status', 'aktif');
                    $teacherId ? $classQuery->where('guru_id', $teacherId) : $classQuery->whereNull('guru_id');
                    $schoolClass = $classQuery->first();
                }

                if (!$schoolClass) {
                    $isNewClass = true;
                    $course  = Course::find($courseId);
                    $teacher = $teacherId ? Teacher::find($teacherId) : null;
                    $parts   = array_filter([
                        $course->nama ?? null,
                        $teacher->name ?? null,
                        $branch->name,
                        ucfirst($jenisKelas),
                    ]);

                    $schoolClass = SchoolClass::create([
                        'cabang_id'         => $branch->id,
                        'mata_pelajaran_id' => $courseId,
                        'guru_id'           => $teacherId,
                        'kapasitas'         => 20,
                        'jumlah_pertemuan'  => (int) ($courseSessions[$courseId] ?? 8),
                        'jenis'             => $jenisKelas,
                        'status'            => 'aktif',
                        'nama_kelas'        => implode(' - ', $parts) ?: 'Kelas Baru',
                    ]);
                }

                DB::table('class_students')->insertOrIgnore([
                    'class_id'   => $schoolClass->id,
                    'student_id' => $student->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Generate the recurring weekly sessions for this brand-new class from
                // the "Jadwal Kelas" table on the process form (hari/jam/ruang per mapel).
                // Only for classes we just created — an existing/reused class already
                // has its own schedule, so we never duplicate sessions onto it.
                $hari   = $scheduleHari[$courseId] ?? null;
                $mulai  = $scheduleJamMulai[$courseId] ?? null;
                $selesai = $scheduleJamSelesai[$courseId] ?? null;
                if ($isNewClass && $hari !== null && $hari !== '' && $mulai && $selesai) {
                    $roomId   = $scheduleRoom[$courseId] ?? null;
                    $roomName = $roomId ? optional(Room::find($roomId))->nama_ruangan : null;
                    $useHonor = !empty($courseUseHonor[$courseId]) && ($courseUseHonor[$courseId] !== '0' && $courseUseHonor[$courseId] !== false && $courseUseHonor[$courseId] !== 'false');
                    $honorPerSesi = $useHonor && array_key_exists($courseId, $courseHonor) ? (float) $courseHonor[$courseId] : null;
                    $jumlahSesi   = (int) ($courseSessions[$courseId] ?? 8);

                    $tanggal = now()->startOfDay();
                    while ((int) $tanggal->dayOfWeek !== (int) $hari) {
                        $tanggal = $tanggal->addDay();
                    }

                    for ($i = 0; $i < $jumlahSesi; $i++) {
                        Schedule::create([
                            'kelas_id'          => $schoolClass->id,
                            'mata_pelajaran_id' => $courseId,
                            'guru_id'           => $teacherId,
                            'cabang_id'         => $branch->id,
                            'tanggal'           => $tanggal->copy()->addWeeks($i)->toDateString(),
                            'jam_mulai'         => $mulai,
                            'jam_selesai'       => $selesai,
                            'program_belajar'   => $programBelajarSchedule,
                            'jenis'             => $jenisKelas,
                            'ruangan'           => $roomName,
                            'honor_per_sesi'    => $honorPerSesi,
                            'status'            => 'dijadwalkan',
                        ]);
                    }
                }
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
                $total           = count($cicilanNominals);
                foreach ($cicilanNominals as $idx => $nominal) {
                    $seqCount  = $baseCount + $idx + 1;
                    $nomor     = 'INV-CIC-' . $year . $month . '-' . str_pad($seqCount, 3, '0', STR_PAD_LEFT) . '-' . ($idx + 1);
                    $tempo     = !empty($cicilanTempo[$idx]) ? $cicilanTempo[$idx] : now()->addDays(30 * ($idx + 1))->toDateString();
                    $mulai     = !empty($cicilanMulai[$idx])  ? $cicilanMulai[$idx]  : now()->addDays(($idx) * 30)->toDateString();
                    $inv = Invoice::create([
                        'siswa_id'      => $student->id,
                        'cabang_id'     => $branch->id,
                        'kelas_id'      => null,
                        'nomor_invoice' => $nomor,
                        'deskripsi'     => $deskripsi . ' — Cicilan ' . ($idx + 1) . ' dari ' . $total,
                        'subtotal'      => (float) $nominal,
                        'diskon'        => 0,
                        'pajak'         => 0,
                        'total'         => (float) $nominal,
                        'status'        => 'belum_bayar',
                        'jatuh_tempo'   => $tempo,
                        'periode'       => date('Y-m', strtotime($mulai)),
                        'catatan'       => 'Cicilan ' . ($idx + 1) . ' dari ' . $total . ' · Mulai tagih: ' . $mulai . ' · Jatuh tempo: ' . $tempo,
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

            // interest_sessions/interest_teachers are shown to the student keyed by
            // subject NAME (see resources/views/siswa/kelas/index.blade.php), but the
            // form submits course_sessions/course_teacher keyed by course ID — remap
            // here so the student portal doesn't display raw course IDs as labels.
            $courseNamesById = Course::whereIn('id', array_keys($courseSessions + $courseTeachers))
                ->pluck('nama', 'id');
            $interestSessionsByName = [];
            foreach ($courseSessions as $cId => $sesi) {
                $interestSessionsByName[$courseNamesById[$cId] ?? $cId] = $sesi;
            }
            $interestTeachersByName = [];
            foreach ($courseTeachers as $cId => $tId) {
                $interestTeachersByName[$courseNamesById[$cId] ?? $cId] = $tId;
            }

            $registration->update([
                'status'               => 'verified',
                'student_id'           => $student->id,
                'branch'               => $branch->name,
                'assigned_teacher_id'  => $primaryTeacherId,
                'interest_teachers'    => $interestTeachersByName ?: null,
                'interest_sessions'    => $interestSessionsByName ?: null,
                'total_sessions'       => $totalSesi ?: null,
                'biaya_per_sesi'       => $data['biaya_per_sesi'] ?? null,
                'total_biaya'          => $totalBiaya,
                'invoice_id'           => $invoice->id,
                'payment_status'       => $data['payment_status'],
                'academic_status'      => $data['payment_status'] === 'lunas' ? 'terjadwal' : 'menunggu_kelas',
            ]);

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => $isExistingStudent
                    ? 'Pendaftaran berhasil diproses. Siswa lama telah didaftarkan ke program/kelas baru.'
                    : 'Pendaftaran berhasil diproses. Akun siswa telah dibuat.',
                'is_existing'    => $isExistingStudent,
                'name'           => $registration->name,
                'email'          => $email ?? ($user->email ?? null),
                'password'       => $password,
                'nis'            => $nis,
                'phone'          => $registration->phone,
                'no_reg'         => $registration->no_reg,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memproses: ' . $e->getMessage()], 500);
        }
    }
}
