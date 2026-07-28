<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Branch;

class CheckBranchAccess
{
    /**
     * Handle an incoming request.
     * If the authenticated user is a branch admin, ensure the requested admin page
     * is allowed for that branch (based on branch.allowed_pages or legacy can_* flags).
     * Owners are allowed everywhere.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // Owners bypass checks
        try {
            if (method_exists($user, 'hasRole') && $user->hasRole('owner')) {
                return $next($request);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        // Apply only for admin users accessing admin routes
        try {
            if (! method_exists($user, 'hasRole') || ! $user->hasRole('admin')) {
                return $next($request);
            }
        } catch (\Throwable $e) {
            return $next($request);
        }

        // Determine page key from route name or path
        $pageKey = null;
        $route = $request->route();
        $routeName = $route ? $route->getName() : null;

        if ($routeName && str_starts_with($routeName, 'admin.')) {
            $parts = explode('.', $routeName);
            $seg = $parts[1] ?? null;
            if ($seg) $pageKey = $this->singularize($seg);
        }

        if (! $pageKey) {
            // fallback: use second URI segment (admin/<segment>)
            $seg = $request->segment(2);
            if ($seg) $pageKey = $this->singularize($seg);
        }

        if (! $pageKey) {
            return $next($request);
        }

        // Find branch: prefer impersonation session, then user->branch_id, then branch where admin_id = user
        $branch = null;
        $impersonateBranchId = $request->session()->get('impersonate.branch_id');
        if ($impersonateBranchId) {
            $branch = Branch::find($impersonateBranchId);
        }

        if (! $branch) {
            if (! empty($user->branch_id)) {
                $branch = Branch::find($user->branch_id);
            }
        }

        if (! $branch) {
            $branch = Branch::where('admin_id', $user->id)->first();
        }

        if (! $branch) {
            return redirect()->route('dashboard')->with('error', 'Cabang tidak ditemukan.');
        }

        // Only restrict if allowed_pages is explicitly configured as a non-empty array.
        // Legacy can_* flags are NOT used as a restriction mechanism — they are incomplete
        // (new pages like billing, modules, messages don't have corresponding flags).
        $allowed = $branch->allowed_pages;

        if (empty($allowed) || ! is_array($allowed)) {
            // No explicit page restrictions configured — allow all pages.
            return $next($request);
        }

        if (! in_array($pageKey, $allowed)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Akses ditolak untuk cabang ini'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini untuk cabang ini.');
        }

        return $next($request);
    }

    private function singularize(string $word): string
    {
        $word = Str::lower($word);

        // Explicit mappings for words that end in -es but retain the trailing 'e'
        $explicit = [
            'courses'          => 'course',
            'schedules'        => 'schedule',
            'certificates'     => 'certificate',
            'modules'          => 'module',
            'packages'         => 'package',
            'messages'         => 'message',
            'videocalls'       => 'videocall',
            'course-fees'      => 'course_fee',
            'registration-list'=> 'registration',
        ];
        if (isset($explicit[$word])) return $explicit[$word];

        if (str_ends_with($word, 'ies')) {
            return substr($word, 0, -3).'y';
        }

        if (str_ends_with($word, 'es')) {
            return substr($word, 0, -2);
        }

        if (str_ends_with($word, 's')) {
            return substr($word, 0, -1);
        }

        return $word;
    }
}
