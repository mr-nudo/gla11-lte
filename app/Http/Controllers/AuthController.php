<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && (Hash::check($request->pass, $user->password))) {
            if($user->is_active == false){
                return redirect()->back()->with('error', 'Oops, Your account has been wiped from our systems! Contact your Admin!');
            }
            // Success
            session(['user' => $user]);
            return redirect('/dashboard');
        }
        //fail and return
        return redirect()->back()->with('error', 'Invalid Credentials');
    }

    public function logout(Request $request){
        $request->session()->flush();
        return redirect('/login');
    }
}
