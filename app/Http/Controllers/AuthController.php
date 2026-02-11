<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginField => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $user->load('posPoint');

            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'login' => 'بيانات الدخول غير صحيحة',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isCashier() || $user->isSales()) {
            if ($user->posPoint && $user->posPoint->active) {
                return redirect()->route('pos.show', $user->posPoint->slug);
            }
            return redirect()->route('cashier.index');
        }

        return redirect()->route('dashboard');
    }
}
