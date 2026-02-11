<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCashier
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isCashier() && !$user->isAdmin()) {
            abort(403, 'غير مصرح لك بالوصول');
        }

        return $next($request);
    }
}
