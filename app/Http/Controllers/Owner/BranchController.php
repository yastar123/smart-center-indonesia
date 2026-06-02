<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount('students')->latest()->get();

        return view('owner.branches.index', [
            'branches' => $branches,
            'total' => Branch::count(),
            'active' => Branch::where('status', 'active')->count(),
            'students' => Student::count(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'email' => 'required|email|unique:branches,email',
            'password' => 'required|min:6',
        ]);

        Branch::create([
            'name' => $request->name,
            'city' => $request->city,
            'regency' => $request->regency,

            'email' => $request->email,
            'password' => Hash::make($request->password),

            'status' => 'active',

            'can_students' => $request->has('can_students'),
            'can_teachers' => $request->has('can_teachers'),
            'can_schedules' => $request->has('can_schedules'),
            'can_payments' => $request->has('can_payments'),
            'can_tryouts' => $request->has('can_tryouts'),
        ]);

        return back()->with('success', 'Cabang berhasil dibuat');
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'status' => 'required',
        ]);

        $branch->update([
            'name' => $request->name,
            'city' => $request->city,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Cabang berhasil diupdate');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return back()->with('success', 'Cabang berhasil dihapus');
    }
    public function resetPassword(Request $request, Branch $branch)
{
    $request->validate([
        'password' => 'required|min:6'
    ]);

    $branch->update([
        'password' => Hash::make($request->password)
    ]);

    return back()->with(
        'success',
        'Password cabang berhasil direset'
    );
}
    
}