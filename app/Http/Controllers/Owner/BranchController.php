<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;
use PDF;

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

    public function __construct()
    {
        // allow only owner (Admin Pusat) to manage branches
        $this->middleware(function ($request, $next) {
            if (! auth()->check() || ! auth()->user()->hasRole('owner')) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'email' => 'nullable|email',
            'password' => 'nullable|min:6',
            'admin_name' => 'nullable|string',
            'admin_username' => 'nullable|string',
        ]);

        // create branch first (without admin_id)
        $branch = Branch::create([
            'name' => $request->name,
            'city' => $request->city,
            'regency' => $request->regency,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'active',
            'can_students' => $request->has('can_students'),
            'can_teachers' => $request->has('can_teachers'),
            'can_schedules' => $request->has('can_schedules'),
            'can_payments' => $request->has('can_payments'),
            'can_tryouts' => $request->has('can_tryouts'),
            'created_by' => auth()->id(),
        ]);

        // create admin user if email provided
        if ($request->email && $request->password) {
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->admin_name ?? $request->email,
                    'username' => $request->admin_username ?? null,
                    'password' => Hash::make($request->password),
                    'is_active' => true,
                ]
            );

            // assign role admin
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('admin');
            }

            $branch->admin_id = $user->id;
            $branch->save();
        }

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
            'address' => $request->address ?? $branch->address,
            'phone' => $request->phone ?? $branch->phone,
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Cabang berhasil diupdate');
    }

    public function destroy(Branch $branch)
    {
        // do not delete admin user automatically; just remove relation
        $branch->admin_id = null;
        $branch->save();
        $branch->delete();

        return back()->with('success', 'Cabang berhasil dihapus');
    }
    public function resetPassword(Request $request, Branch $branch)
{
    $request->validate([
        'password' => 'required|min:6'
    ]);

    if ($branch->admin && $branch->admin->exists()) {
        $branch->admin->update(['password' => Hash::make($request->password)]);
    } else {
        $branch->update(['password' => Hash::make($request->password)]);
    }

    return back()->with('success', 'Password cabang berhasil direset');
}
    public function exportExcel()
    {
        $filename = 'branches_' . date('Ymd_His') . '.csv';

        $branches = Branch::select('id','name','address','city','regency','phone','email','status','created_at')->get();

        $callback = function() use ($branches) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID','Name','Address','City','Regency','Phone','Email','Status','Created At']);

            foreach($branches as $b){
                fputcsv($out, [
                    $b->id,
                    $b->name,
                    $b->address,
                    $b->city,
                    $b->regency,
                    $b->phone,
                    $b->email,
                    $b->status,
                    $b->created_at,
                ]);
            }

            fclose($out);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPdf()
    {
        $branches = Branch::all();

        // If PDF facade exists (barryvdh/laravel-dompdf), use it; otherwise return HTML view
        if (class_exists('\Barryvdh\DomPDF\Facade')) {
            $pdf = PDF::loadView('owner.branches.pdf', compact('branches'));
            return $pdf->download('branches.pdf');
        }

        return response()->view('owner.branches.pdf', compact('branches'));
    }

}