<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentRegistration;

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

    /** DELETE /admin/student-registrations/{id} */
    public function destroy(StudentRegistration $studentRegistration)
    {
        $studentRegistration->delete();

        return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil dihapus.']);
    }
}
