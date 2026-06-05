<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BranchCrudTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // run the seeders to ensure roles and owner exist
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_owner_can_create_update_reset_and_delete_branch()
    {
        $owner = User::where('email', 'adminpusatsci@akademi.com')->first();
        if (! $owner) {
            $owner = User::factory()->create(['email' => 'adminpusatsci@akademi.com']);
            // give owner role if roles package available
            if (method_exists($owner, 'assignRole')) {
                $owner->assignRole('owner');
            }
        }

        $this->actingAs($owner);

        // Create branch
        $response = $this->post(route('owner.branches.store'), [
            'name' => 'Test Branch',
            'city' => 'Test City',
            'regency' => 'Test Regency',
            'address' => 'Jl Test 1',
            'phone' => '08123456789',
            'email' => 'branchadmin@example.com',
            'password' => 'secret123',
            'admin_name' => 'Branch Admin',
            'admin_username' => 'branchadmin',
            'can_students' => 'on',
            'can_teachers' => 'on',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('branches', ['name' => 'Test Branch', 'city' => 'Test City']);
        $this->assertDatabaseHas('users', ['email' => 'branchadmin@example.com']);

        $branch = Branch::where('name', 'Test Branch')->first();

        // Update branch
        $response = $this->put(route('owner.branches.update', $branch), [
            'name' => 'Test Branch Updated',
            'city' => 'New City',
            'status' => 'inactive'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('branches', ['id' => $branch->id, 'name' => 'Test Branch Updated', 'status' => 'inactive']);

        // Reset password
        $response = $this->post(route('owner.branches.resetPassword', $branch), [
            'password' => 'newpassword123'
        ]);

        $response->assertStatus(302);

        $admin = $branch->admin ?? \App\Models\User::where('email','branchadmin@example.com')->first();
        $this->assertTrue(password_verify('newpassword123', $admin->password));

        // Delete branch
        $response = $this->delete(route('owner.branches.destroy', $branch));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
    }
}
