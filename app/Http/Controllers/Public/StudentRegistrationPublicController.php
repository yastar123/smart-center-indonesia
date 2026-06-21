<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;

class StudentRegistrationPublicController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'hp_siswa' => 'required|string|max:20',
            'jenis_kelamin' => 'nullable|string|max:20',
            'nama_ortu' => 'nullable|string|max:150',
            'hp_ortu' => 'nullable|string|max:20',
            'pekerjaan' => 'nullable|string|max:150',
            'program' => 'nullable|string|max:50',
            'sistem' => 'nullable|string|max:50',
            'tempat' => 'nullable|string|max:50',
            'pengambilan' => 'nullable|string|max:50',
            'cabang' => 'nullable|string|max:100',
            'minat' => 'nullable|array',
            'hari' => 'nullable|array',
            'jam_belajar' => 'nullable|string|max:50',
            'tanggal_mulai' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        $registration = StudentRegistration::create([
            'no_reg' => $request->input('no_reg', 'AK-' . now()->format('YmdHis') . '-' . rand(100, 999)),
            'name' => $data['nama_lengkap'],
            'phone' => $data['hp_siswa'],
            'gender' => $data['jenis_kelamin'],
            'birth_place' => $data['tempat_lahir'],
            'birth_date' => $data['tanggal_lahir'],
            'address' => $data['alamat'],
            'parent_name' => $data['nama_ortu'],
            'parent_phone' => $data['hp_ortu'],
            'job' => $data['pekerjaan'],
            'program' => $data['program'],
            'system' => $data['sistem'],
            'learning_place' => $data['tempat'],
            'pickup_mode' => $data['pengambilan'],
            'branch' => $data['cabang'],
            'interests' => $data['minat'] ?? [],
            'day_preferences' => $data['hari'] ?? [],
            'schedule_time' => $data['jam_belajar'],
            'start_date' => $data['tanggal_mulai'],
            'notes' => $data['catatan'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil disimpan sebagai menunggu verifikasi.',
            'data' => [
                'id' => $registration->id,
                'no_reg' => $registration->no_reg,
            ],
        ], 201);
    }
}
