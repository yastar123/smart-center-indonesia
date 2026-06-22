<?php

namespace Tests\Feature;

use App\Models\Branch;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BranchPageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_owner_can_view_branches_index()
    {
        $owner = User::factory()->create([
            'name' => 'Owner Test',
            'email' => 'owner.test@example.com',
        ]);
        $owner->assignRole('owner');
        $this->actingAs($owner);

        $response = $this->get(route('owner.branches.index'));
        $response->assertStatus(200);
        $response->assertSee('Monitoring Cabang');
    }

    public function test_admin_can_open_schedule_create_when_branch_permissions_are_empty()
    {
        $branch = Branch::create([
            'name' => 'Cabang Tanpa Config',
            'city' => 'Test City',
            'status' => 'active',
            'allowed_pages' => [],
            'can_students' => false,
            'can_teachers' => false,
            'can_schedules' => false,
            'can_payments' => false,
            'can_tryouts' => false,
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin Cabang Kosong',
            'email' => 'admin.empty@example.com',
            'branch_id' => $branch->id,
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin);

        $response = $this->get(route('admin.schedules.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Jadwal Sesi');
    }

    public function test_export_endpoints_return_download()
    {
        $owner = User::factory()->create([
            'name' => 'Owner Export',
            'email' => 'owner.export@example.com',
        ]);
        $owner->assignRole('owner');
        $this->actingAs($owner);

        $respX = $this->get(route('owner.branches.export.excel'));
        $respX->assertStatus(200);

        $respP = $this->get(route('owner.branches.export.pdf'));
        $respP->assertStatus(200);
    }
}
