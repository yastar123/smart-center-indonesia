<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Course;
use App\Models\CourseFee;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationProcessHonorToggleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_registration_process_can_store_honor_per_sesi_for_contract_teacher_when_enabled(): void
    {
        $branch = Branch::create([
            'name' => 'Cabang Test',
            'city' => 'Test City',
            'status' => 'active',
            'allowed_pages' => ['registration'],
        ]);

        $admin = User::factory()->create([
            'branch_id' => $branch->id,
            'email' => 'admin.process@example.com',
        ]);
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        $teacher = Teacher::create([
            'nig' => 'NIG-TEST-001',
            'name' => 'Guru Kontrak',
            'status' => 'aktif',
            'jenis_guru' => 'kontrak',
            'salary_base' => 5000000,
            'branch_id' => $branch->id,
        ]);

        $course = Course::create([
            'cabang_id' => $branch->id,
            'kode' => 'MAT-001',
            'nama' => 'Matematika',
            'status' => 'aktif',
        ]);
        $course->guru()->attach($teacher->id);
        CourseFee::create([
            'course_id' => $course->id,
            'amount' => 300000,
        ]);
        Room::create([
            'nama_ruangan' => 'Ruang 1',
            'kapasitas' => 10,
            'status' => 'aktif',
        ]);

        $registration = StudentRegistration::create([
            'no_reg' => 'REG-TEST-001',
            'name' => 'Siswa Baru',
            'status' => 'pending',
            'academic_status' => 'pending',
            'payment_status' => 'belum_bayar',
            'branch' => $branch->name,
            'interests' => ['Matematika'],
        ]);

        $this->actingAs($admin);

        $response = $this->postJson(route('admin.registration-list.process.store', $registration->id), [
            'registration_type' => 'baru',
            'name' => 'Siswa Baru',
            'phone' => '081234567890',
            'gender' => 'L',
            'program' => 'kelas',
            'system' => 'offline',
            'education_level' => 'SMP',
            'tempat_belajar' => 'kantor',
            'course_ids' => [$course->id],
            'course_teacher' => [$course->id => $teacher->id],
            'course_sessions' => [$course->id => 4],
            'course_fee' => [$course->id => 300000],
            'course_honor' => [$course->id => 150000],
            'course_use_honor' => [$course->id => '1'],
            'schedule_hari' => [$course->id => '1'],
            'schedule_jam_mulai' => [$course->id => '08:00'],
            'schedule_jam_selesai' => [$course->id => '09:00'],
            'total_biaya' => 300000,
            'payment_status' => 'lunas',
            'payment_method' => 'prabayar',
            'prabayar_type' => 'lunas',
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $schedule = Schedule::where('mata_pelajaran_id', $course->id)->first();
        $this->assertNotNull($schedule);
        $this->assertSame(150000.0, (float) $schedule->honor_per_sesi);
    }

    public function test_registration_process_does_not_store_honor_per_sesi_when_disabled(): void
    {
        $branch = Branch::create([
            'name' => 'Cabang Test 2',
            'city' => 'Test City 2',
            'status' => 'active',
            'allowed_pages' => ['registration'],
        ]);

        $admin = User::factory()->create([
            'branch_id' => $branch->id,
            'email' => 'admin.process2@example.com',
        ]);
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        $teacher = Teacher::create([
            'nig' => 'NIG-TEST-002',
            'name' => 'Guru Kontrak 2',
            'status' => 'aktif',
            'jenis_guru' => 'kontrak',
            'salary_base' => 5000000,
            'branch_id' => $branch->id,
        ]);

        $course = Course::create([
            'cabang_id' => $branch->id,
            'kode' => 'MAT-002',
            'nama' => 'Biologi',
            'status' => 'aktif',
        ]);
        $course->guru()->attach($teacher->id);
        CourseFee::create([
            'course_id' => $course->id,
            'amount' => 250000,
        ]);
        Room::create([
            'nama_ruangan' => 'Ruang 2',
            'kapasitas' => 8,
            'status' => 'aktif',
        ]);

        $registration = StudentRegistration::create([
            'no_reg' => 'REG-TEST-002',
            'name' => 'Siswa Baru 2',
            'status' => 'pending',
            'academic_status' => 'pending',
            'payment_status' => 'belum_bayar',
            'branch' => $branch->name,
            'interests' => ['Biologi'],
        ]);

        $this->actingAs($admin);

        $response = $this->postJson(route('admin.registration-list.process.store', $registration->id), [
            'registration_type' => 'baru',
            'name' => 'Siswa Baru 2',
            'phone' => '081234567891',
            'gender' => 'P',
            'program' => 'kelas',
            'system' => 'offline',
            'education_level' => 'SMA',
            'tempat_belajar' => 'rumah',
            'course_ids' => [$course->id],
            'course_teacher' => [$course->id => $teacher->id],
            'course_sessions' => [$course->id => 4],
            'course_fee' => [$course->id => 250000],
            'course_honor' => [$course->id => 120000],
            'schedule_hari' => [$course->id => '2'],
            'schedule_jam_mulai' => [$course->id => '09:00'],
            'schedule_jam_selesai' => [$course->id => '10:00'],
            'total_biaya' => 250000,
            'payment_status' => 'lunas',
            'payment_method' => 'prabayar',
            'prabayar_type' => 'lunas',
        ]);

        $response->assertOk();
        $schedule = Schedule::where('mata_pelajaran_id', $course->id)->first();
        $this->assertNotNull($schedule);
        $this->assertNull($schedule->honor_per_sesi);
    }
}
