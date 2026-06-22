<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentRegistration::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('no_reg', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status));

        $registrations = $query->latest()->paginate(15)->appends($request->query());

        $stats = [
            'total'    => StudentRegistration::count(),
            'pending'  => StudentRegistration::where('status', 'pending')->count(),
            'verified' => StudentRegistration::where('status', 'verified')->count(),
            'rejected' => StudentRegistration::where('status', 'rejected')->count(),
        ];

        return view('admin.student-registrations.index', compact('registrations', 'stats'));
    }

    public function show(StudentRegistration $studentRegistration)
    {
        return response()->json([
            'success' => true,
            'data'    => $studentRegistration,
        ]);
    }

    public function verify(StudentRegistration $studentRegistration)
    {
        if ($studentRegistration->status !== 'pending') {
            return back()->with('error', 'Status pendaftaran ini sudah tidak dapat diverifikasi lagi.');
        }

        DB::transaction(function () use ($studentRegistration) {
            $gender = match ($studentRegistration->gender) {
                'Perempuan', 'P' => 'P',
                'Laki-laki', 'L' => 'L',
                default          => 'L',
            };

            $baseName  = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $studentRegistration->name));
            $baseEmail = $baseName . '.' . time() . '@akademibimbel.id';
            $password  = 'password';

            $user = User::create([
                'name'     => $studentRegistration->name,
                'email'    => $baseEmail,
                'password' => Hash::make($password),
            ]);
            $user->assignRole('siswa');

            $nis = $studentRegistration->no_reg ?: ('STU-' . now()->format('YmdHis') . '-' . rand(100, 999));

            Student::create([
                'user_id'      => $user->id,
                'nis'          => $nis,
                'name'         => $studentRegistration->name,
                'gender'       => $gender,
                'birth_date'   => $studentRegistration->birth_date,
                'birth_place'  => $studentRegistration->birth_place,
                'address'      => $studentRegistration->address,
                'phone'        => $studentRegistration->phone,
                'parent_name'  => $studentRegistration->parent_name,
                'parent_phone' => $studentRegistration->parent_phone,
                'school_name'  => $studentRegistration->education_level,
                'grade'        => null,
                'status'       => 'aktif',
                'join_date'    => now()->toDateString(),
            ]);

            $studentRegistration->update([
                'status'    => 'verified',
                'notes'     => ($studentRegistration->notes ? $studentRegistration->notes . "\n" : '') .
                               '[Auto] Email: ' . $baseEmail . ' | Password: ' . $password,
            ]);
        });

        return redirect()->route('admin.student-registrations.index')
            ->with('success', 'Pendaftaran berhasil diverifikasi dan akun siswa telah dibuat (email & password otomatis).');
    }

    public function destroy(StudentRegistration $studentRegistration)
    {
        $studentRegistration->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pendaftaran berhasil dihapus.',
        ]);
    }
}
