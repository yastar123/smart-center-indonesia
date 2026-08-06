<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Course;
use App\Models\CourseFee;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\SchoolClass;
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
            'branch_id' => $branch->id,
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

    public function test_registration_process_can_store_multiple_schedule_slots_for_one_course(): void
    {
        $branch = Branch::create([
            'name' => 'Cabang Slot Test',
            'city' => 'Test City 3',
            'status' => 'active',
            'allowed_pages' => ['registration'],
        ]);

        $admin = User::factory()->create([
            'branch_id' => $branch->id,
            'email' => 'admin.slots@example.com',
        ]);
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        $teacher = Teacher::create([
            'nig' => 'NIG-TEST-003',
            'name' => 'Guru Slot',
            'status' => 'aktif',
            'jenis_guru' => 'kontrak',
            'salary_base' => 5000000,
            'branch_id' => $branch->id,
        ]);

        $course = Course::create([
            'cabang_id' => $branch->id,
            'kode' => 'MAT-003',
            'nama' => 'Fisika',
            'status' => 'aktif',
        ]);
        $course->guru()->attach($teacher->id);
        CourseFee::create([
            'course_id' => $course->id,
            'amount' => 350000,
        ]);
        $room = Room::create([
            'branch_id' => $branch->id,
            'nama_ruangan' => 'Ruang Slot',
            'kapasitas' => 12,
            'status' => 'aktif',
        ]);

        $registration = StudentRegistration::create([
            'no_reg' => 'REG-TEST-003',
            'name' => 'Siswa Slot',
            'status' => 'pending',
            'academic_status' => 'pending',
            'payment_status' => 'belum_bayar',
            'branch' => $branch->name,
            'interests' => ['Fisika'],
        ]);

        $this->actingAs($admin);

        $response = $this->postJson(route('admin.registration-list.process.store', $registration->id), [
            'registration_type' => 'baru',
            'name' => 'Siswa Slot',
            'phone' => '081234567892',
            'gender' => 'L',
            'program' => 'kelas',
            'system' => 'offline',
            'education_level' => 'SMP',
            'tempat_belajar' => 'kantor',
            'course_ids' => [$course->id],
            'course_teacher' => [$course->id => $teacher->id],
            'course_sessions' => [$course->id => 2],
            'course_fee' => [$course->id => 350000],
            'schedule_hari' => [$course->id => ['0' => '1', '1' => '3']],
            'schedule_jam_mulai' => [$course->id => ['0' => '08:00', '1' => '10:00']],
            'schedule_jam_selesai' => [$course->id => ['0' => '09:00', '1' => '11:00']],
            'schedule_room' => [$course->id => ['0' => $room->id, '1' => $room->id]],
            'total_biaya' => 350000,
            'payment_status' => 'lunas',
            'payment_method' => 'prabayar',
            'prabayar_type' => 'lunas',
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $schedules = Schedule::where('mata_pelajaran_id', $course->id)->get();
        $this->assertCount(4, $schedules);
        $this->assertSame([1, 3], $schedules->pluck('tanggal')->map(fn ($date) => (int) now()->parse($date)->dayOfWeek)->unique()->sort()->values()->all());
    }

    public function test_registration_process_creates_schedules_for_existing_class_when_slots_are_provided(): void
    {
        $branch = Branch::create([
            'name' => 'Cabang Existing Class',
            'city' => 'Test City 4',
            'status' => 'active',
            'allowed_pages' => ['registration'],
        ]);

        $admin = User::factory()->create([
            'branch_id' => $branch->id,
            'email' => 'admin.existingclass@example.com',
        ]);
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        $teacher = Teacher::create([
            'nig' => 'NIG-TEST-004',
            'name' => 'Guru Existing',
            'status' => 'aktif',
            'jenis_guru' => 'kontrak',
            'salary_base' => 5000000,
            'branch_id' => $branch->id,
        ]);

        $course = Course::create([
            'cabang_id' => $branch->id,
            'kode' => 'MAT-004',
            'nama' => 'Kimia',
            'status' => 'aktif',
        ]);
        $course->guru()->attach($teacher->id);
        CourseFee::create([
            'course_id' => $course->id,
            'amount' => 280000,
        ]);
        Room::create([
            'branch_id' => $branch->id,
            'nama_ruangan' => 'Ruang 4',
            'kapasitas' => 8,
            'status' => 'aktif',
        ]);

        $existingClass = SchoolClass::create([
            'cabang_id' => $branch->id,
            'mata_pelajaran_id' => $course->id,
            'guru_id' => $teacher->id,
            'kapasitas' => 20,
            'jumlah_pertemuan' => 4,
            'jenis' => 'offline',
            'status' => 'aktif',
            'nama_kelas' => 'Kimia - Guru Existing',
        ]);

        $registration = StudentRegistration::create([
            'no_reg' => 'REG-TEST-004',
            'name' => 'Siswa Existing Class',
            'status' => 'pending',
            'academic_status' => 'pending',
            'payment_status' => 'belum_bayar',
            'branch' => $branch->name,
            'interests' => ['Kimia'],
        ]);

        $this->actingAs($admin);

        $response = $this->postJson(route('admin.registration-list.process.store', $registration->id), [
            'registration_type' => 'baru',
            'name' => 'Siswa Existing Class',
            'phone' => '081234567893',
            'gender' => 'L',
            'program' => 'kelas',
            'system' => 'offline',
            'education_level' => 'SMP',
            'tempat_belajar' => 'kantor',
            'course_ids' => [$course->id],
            'course_class' => [$course->id => $existingClass->id],
            'course_teacher' => [$course->id => $teacher->id],
            'course_sessions' => [$course->id => 2],
            'course_fee' => [$course->id => 280000],
            'schedule_hari' => [$course->id => ['0' => '2']],
            'schedule_jam_mulai' => [$course->id => ['0' => '09:00']],
            'schedule_jam_selesai' => [$course->id => ['0' => '10:00']],
            'total_biaya' => 280000,
            'payment_status' => 'lunas',
            'payment_method' => 'prabayar',
            'prabayar_type' => 'lunas',
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $schedules = Schedule::where('kelas_id', $existingClass->id)->get();
        $this->assertNotEmpty($schedules);
        $this->assertSame(2, $schedules->count());
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
            'branch_id' => $branch->id,
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
