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
        $invoiceRevenue = Payment::where('status', 'verified')->sum('jumlah');
        $courseRevenue = \App\Models\StudentCoursePayment::where('status', 'verified')->sum('amount');

        return [
            'total_students'  => Student::count(),
            'active_students' => Student::where('status', 'aktif')->count(),
            'total_teachers'  => Teacher::count(),
            'active_teachers' => Teacher::where('status', 'aktif')->count(),
            'total_branches'  => Branch::count(),
            'active_branches' => Branch::where('status', 'active')->count(),
            'total_revenue'   => $invoiceRevenue + $courseRevenue,
            'pending_invoices' => Invoice::where('status', 'belum_bayar')->count(),
        ];
    }

    public function adminDashboard(?int $branchId): array
    {
        $invoiceRevenue = Payment::where('status', 'verified')
                                ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
                                ->sum('jumlah');
        $courseRevenue = \App\Models\StudentCoursePayment::where('status', 'verified')
                                ->when($branchId, function($q) use ($branchId) {
                                    $q->whereHas('student', fn($sq) => $sq->where('branch_id', $branchId));
                                })
                                ->sum('amount');

        return [
            'total_students'  => Student::when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'active_students' => Student::where('status', 'aktif')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'total_teachers'  => Teacher::when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'active_teachers' => Teacher::where('status', 'aktif')->when($branchId, fn($q) => $q->where('branch_id', $branchId))->count(),
            'total_branches'  => Branch::count(),
            'active_branches' => Branch::where('status', 'active')->count(),
            'total_revenue'   => $invoiceRevenue + $courseRevenue,
            'pending_invoices' => Invoice::where('status', 'belum_bayar')
                                    ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
                                    ->count(),
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
