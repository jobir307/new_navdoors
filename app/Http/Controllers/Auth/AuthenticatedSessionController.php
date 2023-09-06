<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        $request->authenticate();

        $request->session()->regenerate();        
        $role_id = Auth::user()->role_id;
        switch ($role_id) {
            case 1: // administrator
                return redirect()->route('dashboard');
                break;
            // case 2: // omborxona mudiri
            //     return redirect()->route('dashboard');
            //     break;
            case 3: // moderator (zavodda naryadlarni qabul qiladigan odam)
                return redirect()->route('moderator');
                break;
            case 4: // sotuv manageri
                return redirect()->route('orders');
                break;
            /*case 5: // boshliq
                return redirect()->route('chief');
                break;
            */
            case 6: // kassir
                return redirect()->route('cashier', date('Y-m-d'));
                break;
            case 7: // buxgalter
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
