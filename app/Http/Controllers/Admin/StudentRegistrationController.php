<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Student;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentRegistrationController extends Controller
{
    /** GET /admin/student-registrations/{id} — return JSON for detail modal */
    public function show(StudentRegistration $studentRegistration)
    {
        return response()->json([
            'id'             => $studentRegistration->id,
            'no_reg'         => $studentRegistration->no_reg,
            'name'           => $studentRegistration->name,
            'phone'          => $studentRegistration->phone,
            'gender'         => $studentRegistration->gender,
            'birth_place'    => $studentRegistration->birth_place,
            'birth_date'     => $studentRegistration->birth_date?->format('d M Y'),
            'address'        => $studentRegistration->address,
            'parent_name'    => $studentRegistration->parent_name,
            'parent_phone'   => $studentRegistration->parent_phone,
            'job'            => $studentRegistration->job,
            'program'        => $studentRegistration->program,
            'system'         => $studentRegistration->system,
            'learning_place' => $studentRegistration->learning_place,
            'pickup_mode'    => $studentRegistration->pickup_mode,
            'branch'         => $studentRegistration->branch,
            'interests'      => $studentRegistration->interests ?? [],
            'day_preferences'=> $studentRegistration->day_preferences ?? [],
            'schedule_time'  => $studentRegistration->schedule_time,
            'start_date'     => $studentRegistration->start_date?->format('d M Y'),
            'notes'          => $studentRegistration->notes,
            'status'         => $studentRegistration->status,
            'created_at'     => $studentRegistration->created_at->format('d M Y, H:i'),
        ]);
    }

    /** POST /admin/student-registrations/{id}/verify — create User+Student, return credentials */
    public function verify(StudentRegistration $studentRegistration)
    {
        if ($studentRegistration->status === 'verified') {
            return response()->json(['success' => false, 'message' => 'Pendaftaran sudah terverifikasi.'], 422);
        }

        // Try to match branch by name
        $branch = null;
        if ($studentRegistration->branch) {
            $branch = Branch::where('name', 'like', '%' . $studentRegistration->branch . '%')->first();
        }
        // Fall back to admin's own branch if available
        if (!$branch && auth()->user()->branch_id) {
            $branch = Branch::find(auth()->user()->branch_id);
        }

        // Generate email from name
        $baseName  = Str::slug($studentRegistration->name, '.');
        $baseName  = $baseName ?: 'siswa';
        $email     = strtolower($baseName) . '.' . now()->format('His') . '@siswa.akademi.com';
        $password  = Str::random(8);

        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name'      => $studentRegistration->name,
                'email'     => $email,
                'password'  => Hash::make($password),
                'phone'     => $studentRegistration->phone,
                'branch_id' => $branch?->id,
                'is_active' => true,
            ]);
            $user->assignRole('siswa');

            // Generate NIS
            do {
                $nis = 'S' . now()->format('YmdHis') . Str::upper(Str::random(3));
            } while (Student::where('nis', $nis)->exists());

            // Create student record
            $student = Student::create([
                'user_id'      => $user->id,
                'nis'          => $nis,
                'name'         => $studentRegistration->name,
                'gender'       => $studentRegistration->gender ?? 'L',
                'phone'        => $studentRegistration->phone,
                'birth_place'  => $studentRegistration->birth_place,
                'birth_date'   => $studentRegistration->birth_date,
                'address'      => $studentRegistration->address,
                'parent_name'  => $studentRegistration->parent_name,
                'parent_phone' => $studentRegistration->parent_phone,
                'branch_id'    => $branch?->id,
                'status'       => 'aktif',
                'join_date'    => now()->toDateString(),
                'kategori_peserta_didik' => $studentRegistration->education_level,
            ]);

            // Mark registration as verified and link the created student
            $studentRegistration->update([
                'status'          => 'verified',
                'student_id'      => $student->id,
                'payment_status'  => 'belum_bayar',
                'academic_status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Pendaftaran berhasil diverifikasi. Akun siswa telah dibuat.',
                'name'     => $studentRegistration->name,
                'email'    => $email,
                'password' => $password,
                'nis'      => $nis,
                'phone'    => $studentRegistration->phone,
                'no_reg'   => $studentRegistration->no_reg,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal membuat akun: ' . $e->getMessage()], 500);
        }
    }

    /** DELETE /admin/student-registrations/{id} */
    public function destroy(StudentRegistration $studentRegistration)
    {
        $studentRegistration->delete();

        return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil dihapus.']);
    }
}
