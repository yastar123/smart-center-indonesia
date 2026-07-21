<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Course;
use App\Models\TeacherRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TeacherRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_teacher_registration_page_can_be_opened_without_login(): void
    {
        $response = $this->get(route('public.teacher-registration.create'));

        $response->assertStatus(200);
    }

    public function test_public_teacher_registration_can_be_stored_pending(): void
    {
        Course::create([
            'kode' => 'MAT-001',
            'nama' => 'Matematika',
            'kategori' => 'Akademik',
            'jenis_kursus' => 'reguler',
            'status' => 'aktif',
        ]);

        Course::create([
            'kode' => 'IPA-001',
            'nama' => 'IPA',
            'kategori' => 'Akademik',
            'jenis_kursus' => 'reguler',
            'status' => 'aktif',
        ]);

        $payload = [
            'name' => 'Siti Rahma',
            'nig' => 'NIG-001',
            'gender' => 'P',
            'birth_date' => '1995-04-15',
            'education' => 'S1',
            'phone' => '081234567891',
            'email' => 'siti.rahma@example.com',
            'address' => 'Jl. Pendidikan No. 10',
            'jenis_guru' => 'freelance',
            'branch' => 'Bandung',
            'course_ids' => [1, 2],
        ];

        $response = $this->postJson(route('public.teacher-registrations.store'), $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('teacher_registrations', [
            'name' => 'Siti Rahma',
            'email' => 'siti.rahma@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_public_teacher_registration_can_be_stored_without_email(): void
    {
        Course::create([
            'kode' => 'MAT-002',
            'nama' => 'Fisika',
            'kategori' => 'Akademik',
            'jenis_kursus' => 'reguler',
            'status' => 'aktif',
        ]);

        $payload = [
            'name' => 'Budi Santoso',
            'nig' => 'NIG-002',
            'gender' => 'L',
            'birth_date' => '1990-01-01',
            'education' => 'S1',
            'phone' => '081234567892',
            'jenis_guru' => 'kontrak',
            'address' => 'Jl. Merdeka No. 5',
        ];

        $response = $this->postJson(route('public.teacher-registrations.store'), $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('teacher_registrations', [
            'name' => 'Budi Santoso',
            'status' => 'pending',
        ]);
        $this->assertDatabaseMissing('teacher_registrations', [
            'name' => 'Budi Santoso',
            'email' => '',
        ]);
    }

    public function test_admin_verify_redirects_to_teacher_create_with_prefilled_fields(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $branch = Branch::create([
            'name' => 'Cabang Test',
            'city' => 'Bandung',
            'regency' => 'Bandung',
            'address' => 'Jl. Test No.1',
            'phone' => '081234567890',
            'email' => 'branch@test.com',
            'status' => 'active',
            'can_students' => true,
            'can_teachers' => true,
            'can_schedules' => true,
            'can_payments' => true,
            'can_tryouts' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'branch_id' => $branch->id,
        ]);
        $user->assignRole('admin');

        $registration = TeacherRegistration::create([
            'no_reg' => 'TG-TEST-001',
            'name' => 'Siti Rahma',
            'nig' => 'NIG-VERIFY-001',
            'gender' => 'P',
            'birth_date' => '1995-04-15',
            'education' => 'S1',
            'phone' => '081234567891',
            'email' => 'siti.verify@example.com',
            'branch_id' => null,
            'branch' => 'Bandung',
            'address' => 'Jl. Pendidikan No. 10',
            'jenis_guru' => 'freelance',
            'salary_base' => 0,
            'course_ids' => [1, 2],
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->post(route('admin.teacher-registrations.verify', $registration));

        $response->assertRedirect(route('admin.teachers.create'))
            ->assertSessionHasInput('name', 'Siti Rahma')
            ->assertSessionHasInput('nig', 'NIG-VERIFY-001')
            ->assertSessionHasInput('email', 'siti.verify@example.com');

        $this->assertDatabaseHas('teacher_registrations', [
            'id' => $registration->id,
            'status' => 'verified',
        ]);
    }
}
