<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Student;
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
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.registration.store'), [
            'cabang_id' => $branch->id,
            'is_new_student' => '1',
            'jenis' => 'offline',
            'guru_id' => $teacher->id,
            'tanggal_mulai' => now()->toDateString(),
            'billing_mode' => 'postpaid',
            'student_name' => 'Yayan',
            'student_phone' => '081234567890',
            'wali_name' => 'Bapak Yayan',
            'wali_phone' => '082345678901',
        ]);

        $response->assertRedirect(route('admin.registration.create'));
        $response->assertSessionHas('success');
        $this->assertStringContainsString('Email', session('success'));
        $this->assertStringContainsString('Password', session('success'));

        $student = Student::where('name', 'Yayan')->first();
        $this->assertNotNull($student);
        $this->assertNotEmpty($student->nis);
        $this->assertSame('Bapak Yayan', $student->parent_name);
        $this->assertSame('082345678901', $student->parent_phone);

        $user = User::where('email', 'like', '%yayan%')->first();
        $this->assertNotNull($user);
        $this->assertSame('Yayan', $user->name);
        $this->assertTrue($user->hasRole('siswa') || method_exists($user, 'hasRole') === false);
    }
}
