<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بالوصول'
                ], 403);
            }
            return redirect()->route('login');
        }

        if ($user->isCashier()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للقيام بهذه العملية'
                ], 403);
            }
            abort(403, 'ليس لديك صلاحية للقيام بهذه العملية');
        }

        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للقيام بهذه العملية'
                ], 403);
            }
            abort(403, 'ليس لديك صلاحية للقيام بهذه العملية');
        }

        return $next($request);
    }
}
