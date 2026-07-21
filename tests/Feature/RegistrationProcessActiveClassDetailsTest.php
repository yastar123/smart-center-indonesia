<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Course;
use App\Models\CourseFee;
use App\Models\SchoolClass;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationProcessActiveClassDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_process_page_shows_active_class_details_and_pick_action(): void
    {
        $branch = Branch::create([
            'name' => 'Cabang Detail',
            'city' => 'Test City',
            'status' => 'active',
            'allowed_pages' => ['registration'],
        ]);

        $admin = User::factory()->create([
            'branch_id' => $branch->id,
            'email' => 'admin.classdetail@example.com',
        ]);
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        $teacher = Teacher::create([
            'nig' => 'NIG-DETAIL-001',
            'name' => 'Guru Aktif',
            'status' => 'aktif',
            'jenis_guru' => 'kontrak',
            'salary_base' => 5000000,
            'branch_id' => $branch->id,
        ]);

        $course = Course::create([
            'cabang_id' => $branch->id,
            'kode' => 'MAT-DETAIL-001',
            'nama' => 'Fisika',
            'status' => 'aktif',
        ]);
        $course->guru()->attach($teacher->id);
        CourseFee::create([
            'course_id' => $course->id,
            'amount' => 400000,
        ]);

        SchoolClass::create([
            'cabang_id' => $branch->id,
            'mata_pelajaran_id' => $course->id,
            'guru_id' => $teacher->id,
            'nama_kelas' => 'Kelas Aktif Fisika',
            'kapasitas' => 12,
            'jumlah_pertemuan' => 8,
            'jenis' => 'offline',
            'status' => 'aktif',
        ]);

        $registration = StudentRegistration::create([
            'no_reg' => 'REG-DETAIL-001',
            'name' => 'Siswa Uji',
            'status' => 'pending',
            'academic_status' => 'pending',
            'payment_status' => 'belum_bayar',
            'branch' => $branch->name,
            'interests' => ['Fisika'],
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.registration-list.process', $registration));

        $response->assertOk();
        $response->assertSee('Kelas aktif untuk mata pelajaran ini');
        $response->assertSee('Pakai kelas ini');
        $response->assertSee('Kelas Aktif Fisika');
    }
}
