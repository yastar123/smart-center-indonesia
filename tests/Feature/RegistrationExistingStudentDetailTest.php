<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationExistingStudentDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_detail_includes_school_information(): void
    {
        $branch = Branch::create([
            'name' => 'Cabang Uji',
            'city' => 'Test City',
            'status' => 'active',
            'allowed_pages' => ['registration'],
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Role::firstOrCreate(['name' => 'admin']);

        $admin = User::factory()->create([
            'branch_id' => $branch->id,
            'email' => 'admin.studentdetail@example.com',
        ]);
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }
        if (! $admin->hasRole('admin')) {
            $admin->syncRoles(['admin']);
        }

        $student = Student::create([
            'user_id' => $admin->id,
            'branch_id' => $branch->id,
            'nis' => 'S12345',
            'name' => 'Siswa Lama Uji',
            'phone' => '081234567890',
            'school_name' => 'SMAN 1 Test',
            'grade' => 'Kelas 10',
            'status' => 'aktif',
        ]);

        $this->actingAs($admin);

        $response = $this->getJson(route('admin.registration-list.student-detail', $student));

        $response->assertOk()
            ->assertJsonPath('student.school_name', 'SMAN 1 Test')
            ->assertJsonPath('student.grade', 'Kelas 10');
    }
}
