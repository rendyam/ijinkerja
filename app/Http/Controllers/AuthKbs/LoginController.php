<?php

namespace App\Http\Controllers\AuthKbs;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest:kbs')->except(['logout']);
    }

    public function showLoginForm()
    {
        return view('auth-kbs.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credential = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // Attempt to log the user in
        if (Auth::guard('kbs')->attempt($credential, $request->member)) {
            // If login succesful, then redirect to their intended location
            return redirect()->route('kbs.home');
        }

        // If Unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::guard('kbs')->logout();
        // // return redirect('kbs.login');
        // // return redirect()->intended(route('logoutKbs'));
        // return view('auth-kbs.login');
        // dd($request);


        // $this->guard()->logout();
 
        $request->session()->flush();
 
        $request->session()->regenerate();
 
        return redirect()->route('kbs.login')->withSuccess('Terimakasih, selamat datang kembali!');
    }
}
