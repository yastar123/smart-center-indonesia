<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Course;
use App\Models\CourseFee;
use App\Models\Student;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_admin_can_register_new_student_and_create_account()
    {
        $branch = Branch::create([
            'name' => 'Cabang Registrasi',
            'city' => 'Test City',
            'status' => 'active',
            'allowed_pages' => ['registration'],
        ]);

        $admin = User::factory()->create([
            'branch_id' => $branch->id,
            'email' => 'admin.reg@example.com',
        ]);
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        $teacher = Teacher::create([
            'nig' => 'NIG-REG-001',
            'name' => 'Guru Registrasi',
            'status' => 'aktif',
            'branch_id' => $branch->id,
        ]);

        $course = Course::create([
            'cabang_id' => $branch->id,
            'kode' => 'REG-001',
            'nama' => 'Matematika',
            'status' => 'aktif',
        ]);
        $course->guru()->attach($teacher->id);

        CourseFee::create([
            'course_id' => $course->id,
            'amount' => 250000,
        ]);

        $registration = StudentRegistration::create([
            'no_reg' => 'REG-TEST-001',
            'name' => 'Siswa Pendaftaran',
            'status' => 'pending',
            'academic_status' => 'pending',
            'payment_status' => 'belum_bayar',
            'branch' => $branch->name,
            'interests' => ['Matematika'],
        ]);

        $this->actingAs($admin);

        $response = $this->postJson(route('admin.registration-list.process.store', $registration->id), [
            'registration_type' => 'baru',
            'name' => 'Yayan',
            'phone' => '081234567890',
            'gender' => 'L',
            'program' => 'kelas',
            'system' => 'offline',
            'education_level' => 'SMP',
            'tempat_belajar' => 'kantor',
            'course_ids' => [$course->id],
            'course_teacher' => [$course->id => $teacher->id],
            'course_sessions' => [$course->id => 4],
            'course_fee' => [$course->id => 250000],
            'schedule_hari' => [$course->id => '1'],
            'schedule_jam_mulai' => [$course->id => '08:00'],
            'schedule_jam_selesai' => [$course->id => '09:00'],
            'total_biaya' => 250000,
            'payment_status' => 'lunas',
            'payment_method' => 'prabayar',
            'prabayar_type' => 'lunas',
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('name', 'Yayan');

        $student = Student::where('name', 'Yayan')->first();
        $this->assertNotNull($student);
        $this->assertNotEmpty($student->nis);
        $this->assertSame('aktif', $student->status);

        $user = User::where('email', 'like', '%yayan%')->first();
        $this->assertNotNull($user);
        $this->assertSame('Yayan', $user->name);
        $this->assertTrue($user->hasRole('siswa') || method_exists($user, 'hasRole') === false);
    }
}
