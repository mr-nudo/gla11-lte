<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && (Hash::check($request->pass, $user->password))) {
            // Success
            return redirect('/dashboard')->with(['data' => []]);
            //return 'Success';
        }
        //fail and return
        return 'Invalid Credentials';
    }
}
