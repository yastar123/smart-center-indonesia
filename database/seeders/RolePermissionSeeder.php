<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Branch
            'branch.view', 'branch.create', 'branch.edit', 'branch.delete',
            // Students
            'student.view', 'student.create', 'student.edit', 'student.delete',
            // Teachers
            'teacher.view', 'teacher.create', 'teacher.edit', 'teacher.delete',
            // Employees
            'employee.view', 'employee.create', 'employee.edit', 'employee.delete',
            // Schedules
            'schedule.view', 'schedule.create', 'schedule.edit', 'schedule.delete',
            // Payments
            'payment.view', 'payment.create', 'payment.edit', 'payment.approve',
            // Tryout
            'tryout.view', 'tryout.create', 'tryout.edit', 'tryout.delete',
            // Reports
            'report.view', 'report.export',
            // Settings
            'setting.view', 'setting.edit',
            // Salary
            'salary.view', 'salary.create', 'salary.edit',
            // Certificate
            'certificate.view', 'certificate.create', 'certificate.download',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Create Roles & assign permissions
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $owner->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'student.view', 'student.create', 'student.edit', 'student.delete',
            'teacher.view', 'teacher.create', 'teacher.edit',
            'employee.view', 'employee.create', 'employee.edit',
            'schedule.view', 'schedule.create', 'schedule.edit', 'schedule.delete',
            'payment.view', 'payment.create', 'payment.approve',
            'tryout.view', 'tryout.create', 'tryout.edit',
            'report.view', 'report.export',
            'salary.view', 'salary.create',
            'certificate.view', 'certificate.create', 'certificate.download',
        ]);

        $guru = Role::firstOrCreate(['name' => 'guru']);
        $guru->syncPermissions([
            'schedule.view', 'student.view',
            'salary.view', 'certificate.download',
        ]);

        $siswa = Role::firstOrCreate(['name' => 'siswa']);
        $siswa->syncPermissions([
            'schedule.view', 'payment.view',
            'tryout.view', 'certificate.download',
        ]);

        $karyawan = Role::firstOrCreate(['name' => 'karyawan']);
        $karyawan->syncPermissions(['schedule.view', 'salary.view']);

        // Create Super Admin (Admin Pusat)
        $ownerUser = User::where('email', 'adminpusatsci@akademi.com')->first();
        if (! $ownerUser) {
            $ownerUser = new User();
            $ownerUser->email = 'adminpusatsci@akademi.com';
        }
        $ownerUser->forceFill([
            'name'      => 'Admin Pusat SCI',
            'password'  => bcrypt('password'),
            'is_active' => true,
        ]);
        $ownerUser->save();
        $ownerUser->syncRoles(['owner']);

        // Create Admin Cabang
        $adminUser = User::where('email', 'admincabangsci@akademi.com')->first();
        if (! $adminUser) {
            $adminUser = new User();
            $adminUser->email = 'admincabangsci@akademi.com';
        }
        // Assign to first branch (Cabang Pusat) so CheckBranchAccess can resolve the branch
        $firstBranch = \App\Models\Branch::first();
        $adminUser->forceFill([
            'name'      => 'Admin Cabang SCI',
            'password'  => bcrypt('password'),
            'is_active' => true,
            'branch_id' => $firstBranch?->id,
        ]);
        $adminUser->save();
        $adminUser->syncRoles(['admin']);

        $this->command->info('✅ Roles, Permissions & Users seeded!');
    }
}