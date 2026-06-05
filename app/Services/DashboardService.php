<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Branch;
use App\Models\Payment;
use App\Models\Invoice;

class DashboardService
{
    public function ownerDashboard(): array
    {
        return [
            'total_students'  => Student::count(),
            'active_students' => Student::where('status', 'aktif')->count(),
            'total_teachers'  => Teacher::count(),
            'active_teachers' => Teacher::where('status', 'aktif')->count(),
            'total_branches'  => Branch::count(),
            'active_branches' => Branch::where('status', 'active')->count(),
            'total_revenue'   => 0,
        ];
    }

    public function adminDashboard(?int $branchId): array
    {
        return [
            'total_students'  => Student::when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'active_students' => Student::where('status', 'aktif')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'total_teachers'  => Teacher::when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'active_teachers' => Teacher::where('status', 'aktif')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'total_branches'  => Branch::count(),
            'active_branches' => Branch::where('status', 'active')->count(),
            'total_revenue'   => 0,
        ];
    }

    public function teacherDashboard(int $userId): array
    {
        return [
            'user_id' => $userId,
        ];
    }

    public function studentDashboard(int $userId): array
    {
        return [
            'user_id' => $userId,
        ];
    }

    public function employeeDashboard(int $userId): array
    {
        return [
            'user_id' => $userId,
        ];
    }
}
