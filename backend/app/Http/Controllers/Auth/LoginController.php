<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->status == 0) {
                return redirect()->back()->withErrors(__('These email is not active yet. Contact to admin'));
            }

            if (Hash::check($request->password, $user->password)) {
                $data = [ "email" => $request->email, "password" => $request->password];
                if (\Auth::attempt($data)) {
                    return redirect('/home');
                }
            } else {
                return redirect()->back()->withErrors(__('These credentials do not match our records'));
            }

        } else {
            return redirect()->back()->withErrors(__('These email do not match our records.'));
        }
    }
}
