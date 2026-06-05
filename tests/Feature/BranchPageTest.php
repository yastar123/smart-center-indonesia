<?php

namespace Tests\Feature;

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
        $this->artisan('db:seed', ['--class' => 'AdminUserSeeder']);
    }

    public function test_owner_can_view_branches_index()
    {
        $owner = User::where('email','adminpusatsci@akademi.com')->first();
        $this->actingAs($owner);

        $response = $this->get(route('owner.branches.index'));
        $response->assertStatus(200);
        $response->assertSee('Monitoring Cabang');
    }

    public function test_export_endpoints_return_download()
    {
        $owner = User::where('email','adminpusatsci@akademi.com')->first();
        $this->actingAs($owner);

        $respX = $this->get(route('owner.branches.export.excel'));
        $respX->assertStatus(200);

        $respP = $this->get(route('owner.branches.export.pdf'));
        $respP->assertStatus(200);
    }
}
