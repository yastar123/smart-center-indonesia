<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Salary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminSalaryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_admin_can_create_salary_with_bukti()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['email' => 'admin@example.com']);
        if (method_exists($admin, 'assignRole')) {
            $admin->assignRole('admin');
        }

        $teacher = Teacher::create([
            'nig' => 'NIG-TEST-001',
            'name' => 'Test Guru',
            'status' => 'aktif'
        ]);

        $this->actingAs($admin);

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->post(route('admin.salaries.store'), [
            'guru_id' => $teacher->id,
            'tipe_gaji' => 'bulanan',
            'periode' => '2026-06',
            'gaji_pokok' => 1500000,
            'status' => 'pending',
            'bukti_pembayaran' => $file,
        ]);

        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('salaries', [
            'guru_id' => $teacher->id,
            'periode' => '2026-06',
            'tipe_gaji' => 'bulanan',
        ]);

        $salary = Salary::first();
        $this->assertNotNull($salary->bukti_pembayaran);
        Storage::disk('public')->assertExists($salary->bukti_pembayaran);
    }

    public function test_teacher_can_view_payments()
    {
        $user = User::factory()->create();
        if (method_exists($user, 'assignRole')) $user->assignRole('guru');

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'nig' => 'NIG-TEST-002',
            'name' => 'Guru Viewer',
            'status' => 'aktif'
        ]);

        // create a salary for the teacher
        Salary::create([
            'guru_id' => $teacher->id,
            'periode' => '2026-06',
            'tipe_gaji' => 'bulanan',
            'gaji_pokok' => 1000000,
            'status' => 'dibayar'
        ]);

        $this->actingAs($user);
        $response = $this->get('/guru/payments');
        $response->assertStatus(200);
        $response->assertSee('Guru Viewer');
    }

    public function test_admin_can_view_reports()
    {
        $admin = User::factory()->create();
        if (method_exists($admin, 'assignRole')) $admin->assignRole('admin');

        $this->actingAs($admin);
        $response = $this->get(route('admin.reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Laporan');
    }
}
