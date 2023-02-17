<?php

namespace App\Http\Controllers\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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


    protected function authenticated(Request $request, $user)
    {
        //Check user role, if it is not admin then logout
        $adminRole = config('constants.user_roles.admin');
        $managerRole = config('constants.user_roles.manager');

        if(!in_array($user->role, [$adminRole, $managerRole]))
        {
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('/login')->withErrors('You are unauthorized to login');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'employee_code';
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');

    }
}
