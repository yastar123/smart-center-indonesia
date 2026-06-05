<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isTeacher())  return redirect()->route('guru.dashboard');
        if ($user->isStudent())  return redirect()->route('siswa.dashboard');

        $data = match(true) {
            $user->isOwner()    => $this->dashboardService->ownerDashboard(),
            $user->isAdmin()    => $this->dashboardService->adminDashboard($user->branch_id),
            $user->isEmployee() => $this->dashboardService->employeeDashboard($user->id),
            default             => [],
        };

        return view('dashboard', compact('data', 'user'));
    }
}