<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
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

    public function login()
    {

        return view('admin.login');
    }


    public function loginStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if(Auth::guard('admin')->attempt($request->only('email','password'))){

            return redirect('/admin/dashboard');
        }

        return redirect()->back()->withErrors(['email'=>'invalid crediential']);
    }

    function dashboard(){
        return view('admin.dashboard');
    }

    function BusinessLogin($id){
       $business=Business::find($id);
       $user=User::find($business->owner_id);
        Auth::guard('web')->login($user);
        return redirect('/home');
    }

}
