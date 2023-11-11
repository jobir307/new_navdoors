<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // dd(Hash::make($request->password));
        $request->authenticate();

        $request->session()->regenerate();        
        $role_id = Auth::user()->role_id;
        switch (true) {
            case ($role_id == 1): // administrator
                return redirect()->route('dashboard');
                break;
            case ($role_id == 2): // omborxona mudiri
                return redirect()->route('warehouse');
                break;
            case ($role_id == 3): // moderator (zavodda naryadlarni qabul qiladigan odam)
                return redirect()->route('moderator');
                break;
            case ($role_id == 4 || $role_id == 8): // sotuv manageri
                return redirect()->route('orders');
                break;
            case ($role_id == 6): // kassir
                return redirect()->route('cashier', date('Y-m-d'));
                break;
            case ($role_id == 7): // buxgalter
                return redirect()->route('accountant');
                break;
            default:
                return redirect()->route('404');
                break;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
