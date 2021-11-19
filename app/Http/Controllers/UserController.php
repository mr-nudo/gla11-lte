<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Hash;
use Str;

class UserController extends Controller
{
    public function readAdmins(Request $request)
    {
        $users = User::where('role_id', Role::ADMIN)->get();
        
        //return redirect('/admins')->with(['data' => $users]);
        return view('/admins', ['data' => $users]);
        
    }

    public function createAdmin(Request $request)
    {
        
        $validated = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:user|max:255',
            'phone' => 'numeric|nullable'
        ]);

        //dd($request);

        //create user & redirect with success message
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->firstname);
        $user->access_token = Str::random(10);
        $user->created_by = 1;
        $user->role_id = Role::ADMIN;
        $user->save();
        
        return redirect('/admins')->with('message', 'New Admin Created successfully!');
        
    }
}
