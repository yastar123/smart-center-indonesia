<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Invoice;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function approve(StudentRegistration $registration)
    {
        $teachers = Teacher::where('status', 'aktif')->orderBy('name')
            ->get(['id', 'name', 'jenis_guru', 'salary_base', 'branch_id']);

        // Look up prices for each interest by matching course name
        $interests   = $registration->interests ?? [];
        $coursePrices = [];
        if (!empty($interests)) {
            $courses = \App\Models\Course::whereIn('nama', $interests)->get()->keyBy('nama');
            foreach ($interests as $interest) {
                $course = $courses->get($interest);
                $price  = null;
                if ($course) {
                    $price = \Illuminate\Support\Facades\DB::table('course_fees')
                        ->where('course_id', $course->id)
                        ->value('amount');
                    if ($price === null) {
                        $price = $course->harga ?? $course->biaya ?? null;
                    }
                }
                $coursePrices[$interest] = $price;
            }
        }

        return view('admin.registration.approve', compact('registration', 'teachers', 'coursePrices'));
    }

    public function sendInvoice(Request $request, StudentRegistration $registration)
    {
        $request->validate([
            'teacher_id'               => 'nullable|exists:teachers,id',
            'total_biaya'              => 'required|numeric|min:0',
            'total_sessions'           => 'nullable|integer|min:1',
            'biaya_per_sesi'           => 'nullable|numeric|min:0',
            'interest_sessions'        => 'nullable|array',
            'interest_sessions.*'      => 'nullable|integer|min:0',
            'interest_teachers'        => 'nullable|array',
            'interest_teachers.*'      => 'nullable|exists:teachers,id',
            'interest_teacher_honor'   => 'nullable|array',
            'interest_teacher_honor.*' => 'nullable|numeric|min:0',
            'interest_teacher_sesi'    => 'nullable|array',
            'interest_teacher_sesi.*'  => 'nullable|integer|min:0',
        ]);

        if (!$registration->student_id) {
            return back()->with('error', 'Siswa belum memiliki akun terdaftar. Verifikasi terlebih dahulu dari dashboard.');
        }

        DB::beginTransaction();
        try {
            $interestTeachers = null;
            if ($request->filled('interest_teachers') && is_array($request->interest_teachers)) {
                $interestTeachers = array_map('intval', array_filter($request->interest_teachers, fn($v) => $v !== null && $v !== ''));
            }
            $primaryTeacherId = $request->teacher_id
                ?? ($interestTeachers ? array_values($interestTeachers)[0] : null);

            $totalBiaya = (float) $request->total_biaya;

            // Generate invoice number
            $year  = date('Y');
            $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
            $count = Invoice::whereYear('created_at', $year)->whereMonth('created_at', date('m'))->count() + 1;
            $nomor = 'INV-REG-' . $year . $month . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            // Resolve branch
            $student  = \App\Models\Student::find($registration->student_id);
            $branchId = $student?->branch_id;

            $invoice = Invoice::create([
                'siswa_id'      => $registration->student_id,
                'cabang_id'     => $branchId,
                'kelas_id'      => null,
                'nomor_invoice' => $nomor,
                'deskripsi'     => 'Biaya Pendaftaran Program: ' . implode(', ', $registration->interests ?? [$registration->program ?? 'Umum']),
                'subtotal'      => $totalBiaya,
                'diskon'        => 0,
                'pajak'         => 0,
                'total'         => $totalBiaya,
                'status'        => 'belum_bayar',
                'jatuh_tempo'   => Carbon::now()->addDays(7),
                'periode'       => date('Y-m'),
            ]);

            $interestSessions = null;
            if ($request->filled('interest_sessions') && is_array($request->interest_sessions)) {
                $interestSessions = array_map('intval', array_filter($request->interest_sessions, fn($v) => $v !== null && $v !== ''));
            }
            $totalSessions = $interestSessions ? array_sum($interestSessions) : ($request->total_sessions ?? null);

            $interestTeacherHonor = null;
            if ($request->filled('interest_teacher_honor') && is_array($request->interest_teacher_honor)) {
                $interestTeacherHonor = array_map('floatval', array_filter($request->interest_teacher_honor, fn($v) => $v !== null && $v !== ''));
            }

            $interestTeacherSesi = null;
            if ($request->filled('interest_teacher_sesi') && is_array($request->interest_teacher_sesi)) {
                $interestTeacherSesi = array_map('intval', array_filter($request->interest_teacher_sesi, fn($v) => $v !== null && $v !== ''));
            }

            $registration->update([
                'assigned_teacher_id'    => $primaryTeacherId,
                'biaya_per_sesi'         => $request->biaya_per_sesi ?? null,
                'total_sessions'         => $totalSessions,
                'interest_sessions'      => $interestSessions,
                'interest_teachers'      => $interestTeachers,
                'interest_teacher_honor' => $interestTeacherHonor,
                'interest_teacher_sesi'  => $interestTeacherSesi,
                'total_biaya'            => $totalBiaya,
                'invoice_id'             => $invoice->id,
                'payment_status'         => 'belum_bayar',
                'academic_status'        => 'menunggu_kelas',
            ]);

            DB::commit();
            return redirect()->route('admin.registration-list.index')
                ->with('success', "Invoice {$nomor} berhasil dikirim ke siswa.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function markLunas(Request $request, StudentRegistration $registration)
    {
        $request->validate([
            'teacher_id'               => 'nullable|exists:teachers,id',
            'total_biaya'              => 'required|numeric|min:0',
            'total_sessions'           => 'nullable|integer|min:1',
            'biaya_per_sesi'           => 'nullable|numeric|min:0',
            'interest_sessions'        => 'nullable|array',
            'interest_sessions.*'      => 'nullable|integer|min:0',
            'interest_teachers'        => 'nullable|array',
            'interest_teachers.*'      => 'nullable|exists:teachers,id',
            'interest_teacher_honor'   => 'nullable|array',
            'interest_teacher_honor.*' => 'nullable|numeric|min:0',
            'interest_teacher_sesi'    => 'nullable|array',
            'interest_teacher_sesi.*'  => 'nullable|integer|min:0',
        ]);

        if (!$registration->student_id) {
            return back()->with('error', 'Siswa belum memiliki akun terdaftar. Verifikasi terlebih dahulu dari dashboard.');
        }

        DB::beginTransaction();
        try {
            $interestTeachersLunas = null;
            if ($request->filled('interest_teachers') && is_array($request->interest_teachers)) {
                $interestTeachersLunas = array_map('intval', array_filter($request->interest_teachers, fn($v) => $v !== null && $v !== ''));
            }
            $primaryTeacherIdLunas = $request->teacher_id
                ?? ($interestTeachersLunas ? array_values($interestTeachersLunas)[0] : null);

            $totalBiaya = (float) $request->total_biaya;

            $year  = date('Y');
            $month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
            $count = Invoice::whereYear('created_at', $year)->whereMonth('created_at', date('m'))->count() + 1;
            $nomor = 'INV-REG-' . $year . $month . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $student  = \App\Models\Student::find($registration->student_id);
            $branchId = $student?->branch_id;

            $invoice = Invoice::create([
                'siswa_id'      => $registration->student_id,
                'cabang_id'     => $branchId,
                'kelas_id'      => null,
                'nomor_invoice' => $nomor,
                'deskripsi'     => 'Biaya Pendaftaran Program (Lunas): ' . implode(', ', $registration->interests ?? [$registration->program ?? 'Umum']),
                'subtotal'      => $totalBiaya,
                'diskon'        => 0,
                'pajak'         => 0,
                'total'         => $totalBiaya,
                'status'        => 'lunas',
                'jatuh_tempo'   => Carbon::now(),
                'periode'       => date('Y-m'),
                'catatan'       => 'Dibayar lunas saat proses registrasi.',
            ]);

            $interestSessions = null;
            if ($request->filled('interest_sessions') && is_array($request->interest_sessions)) {
                $interestSessions = array_map('intval', array_filter($request->interest_sessions, fn($v) => $v !== null && $v !== ''));
            }
            $totalSessions = $interestSessions ? array_sum($interestSessions) : ($request->total_sessions ?? null);

            $interestTeacherHonorLunas = null;
            if ($request->filled('interest_teacher_honor') && is_array($request->interest_teacher_honor)) {
                $interestTeacherHonorLunas = array_map('floatval', array_filter($request->interest_teacher_honor, fn($v) => $v !== null && $v !== ''));
            }

            $interestTeacherSesiLunas = null;
            if ($request->filled('interest_teacher_sesi') && is_array($request->interest_teacher_sesi)) {
                $interestTeacherSesiLunas = array_map('intval', array_filter($request->interest_teacher_sesi, fn($v) => $v !== null && $v !== ''));
            }

            $registration->update([
                'assigned_teacher_id'    => $primaryTeacherIdLunas,
                'biaya_per_sesi'         => $request->biaya_per_sesi ?? null,
                'total_sessions'         => $totalSessions,
                'interest_sessions'      => $interestSessions,
                'interest_teachers'      => $interestTeachersLunas,
                'interest_teacher_honor' => $interestTeacherHonorLunas,
                'interest_teacher_sesi'  => $interestTeacherSesiLunas,
                'total_biaya'            => $totalBiaya,
                'invoice_id'             => $invoice->id,
                'payment_status'         => 'lunas',
                'academic_status'        => 'terjadwal',
            ]);

            DB::commit();
            return redirect()->route('admin.registration-list.index')
                ->with('success', 'Pembayaran dicatat lunas. Invoice tersimpan di halaman Billing.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    public function reject(StudentRegistration $registration)
    {
        $registration->update([
            'status'          => 'rejected',
            'academic_status' => 'pending',
        ]);
        return redirect()->route('admin.registration-list.index')
            ->with('success', 'Pendaftaran telah ditolak.');
    }
}
