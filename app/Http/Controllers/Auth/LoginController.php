<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    // LoginController.php
    public function authenticated(Request $request, $user)
    {   
        if ($user->role === 'super-admin') {
            return redirect()->route('super-admin-tickets'); // Arahkan ke halaman Super Admin
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin-dashboard'); // Arahkan ke halaman Admin
        }

        return redirect()->route('user-dashboard'); // Arahkan ke halaman User
    }


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
