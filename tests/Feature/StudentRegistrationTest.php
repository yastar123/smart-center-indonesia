<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_form_can_store_pending_registration(): void
    {
        $payload = [
            'nama_lengkap' => 'Budi Santoso',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2010-05-15',
            'alamat' => 'Jl. Contoh No. 1',
            'hp_siswa' => '081234567890',
            'jenis_kelamin' => 'Laki-laki',
            'nama_ortu' => 'Sari Santoso',
            'hp_ortu' => '081234567891',
            'pekerjaan' => 'Guru',
            'program' => 'Kelas',
            'sistem' => 'Offline',
            'tempat' => 'Kantor',
            'pengambilan' => 'Paket',
            'cabang' => 'Bandung',
            'minat' => ['Matematika'],
            'hari' => ['Senin'],
            'jam_belajar' => '13:00 – 14:00',
            'tanggal_mulai' => '2026-07-01',
            'catatan' => 'Coba test'
        ];

        $response = $this->postJson(route('public.student-registrations.store'), $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('student_registrations', [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'status' => 'pending'
        ]);
    }
}
