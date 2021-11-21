<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Hash;
use Str;

class UserController extends Controller
{
    public function readAdmins(Request $request)
    {
        $users = User::where(['role_id' => Role::ADMIN, 'is_active' => true])->paginate(10);
        
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
        $user->created_by = session()->get('user')->id;
        $user->role_id = Role::ADMIN;
        $user->save();
        
        return redirect('/admins')->with('message', 'New Admin Created successfully!');
        
    }

    public function readEmployees(Request $request)
    {
        $users = User::where(['role_id' => Role::EMPLOYEE, 'is_active' => true])->paginate(10);
        $companies = Company::where('is_active', true)->get();
        $data = ['users' => $users, 'companies' => $companies];
        
        return view('/employees', ['data' => $data]);
        
    }

    public function createEmployee(Request $request)
    {
        
        $validated = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:user|max:255',
            'phone' => 'numeric|nullable',
            'company_id' => 'required'
        ]);

        //dd($request);

        //create user & redirect with success message
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->firstname);
        $user->created_by = session()->get('user')->id;
        $user->role_id = Role::EMPLOYEE;
        $user->company_id = $request->company_id;
        $user->save();
        
        return redirect('/employees')->with('message', 'New Employee Created successfully!');
        
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        if($user){
            $user->is_active = false;
            $user->save();
            
            return redirect()->back()->with('message', $user->firstname . ' deleted successfully!');
        }
        
    }
}
