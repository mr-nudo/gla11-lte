<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendNewCompanyMail;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Storage;
use Hash;

class CompanyController extends Controller
{
    public function readCompanies(Request $request)
    {
        $companies = Company::where('is_active', true)->get();
        
        //return redirect('/admins')->with(['data' => $users]);
        return view('/companies', ['data' => $companies]);
        
    }

    public function createCompany(Request $request)
    {
        
        //dd($request); 

        $validated = $request->validate([
            'name' => 'required|unique:company',
            'website' => 'nullable',
            'email' => 'nullable|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|dimensions:min_width=100,min_height=100'
        ]);        

        //do file saving first
        $url = NULL;
        if($request->image){
            $extension = $request->image->extension();
            $request->image->storeAs('/public', $validated['name'].".".$extension);
            $url = Storage::url($validated['name'].".".$extension);

        }

        $company = new Company();
        $company->name = $request->name;
        $company->logo = $url;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->created_by = session()->get('user')->id;
        $company->save();

        if($request->email){
            Mail::to($request->email)->send(new SendNewCompanyMail($request->name));
        }
        
        return redirect('/companies')->with('message', 'New Company Created successfully!');
        
    }

    public function readCompany(Request $request, $company_id)
    {
        $company = Company::where(['id' => $company_id, 'is_active' => true])->first();
        if($company){
            $admins = User::where(['company_id' => $company_id, 'role_id' => Role::COMPANY_ADMIN, 'is_active' => true])->get();
            $staff = User::where('company_id', $company_id)->get();
            $data = [
                'company' => $company, 
                'admins' => $admins,
                'counts' => [
                    'admin' => count($admins),
                    'active_staff' => count($staff->where('is_active', true)),
                    'all_staff' => count($staff)
                ]
            ];
            return view('/company', ['data' => $data]);
        }
        return redirect('/companies')->with('error', 'No record found');
        
    }

    public function createCompanyAdmin(Request $request, $company_id)
    {
        
        $validated = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:user|max:255',
            'phone' => 'numeric|nullable',
            'position' => 'nullable|string'
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
        $user->role_id = Role::COMPANY_ADMIN;
        $user->company_id = $company_id;
        $user->position = $request->position;
        $user->save();
        
        return redirect('/companies/' . $company_id)->with('message', 'New Company Admin Created successfully!');
        
    }

    public function readCompanyEmployees(Request $request, $company_id)
    {
        $users = User::where(['role_id' => Role::EMPLOYEE, 'company_id' => $company_id, 'is_active' => true])->get();
        $companies = Company::where('is_active', true)->get();
        $company = Company::where('id', $company_id)->first();
        $data = ['users' => $users, 'companies' => $companies, 'company' => $company];

        return view('/company-employees', ['data' => $data]);
    }

    public function createCompanyEmployee(Request $request, $company_id)
    {
        
        $validated = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:user|max:255',
            'phone' => 'numeric|nullable',
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
        $user->company_id = $company_id;
        $user->save();
        
        return redirect('/companies/' . $company_id . '/employees')->with('message', 'New Company Employee Created successfully!');
        
    }

    public function deleteEmployee(Request $request, $company_id, $employee_id)
    {
        $user = User::where('id', $employee_id)->first();

        if($user){
            $user->is_active = false;
            $user->save();
            
            return redirect()->back()->with('message', $user->firstname . ' deleted successfully!');
        }
        
    }

    public function deleteCompany(Request $request, $company_id)
    {
        Company::where('id', $company_id)->update(['is_active' => false]);
        User::where('company_id', $company_id)->update(['is_active' => false]);
        
        return redirect()->back()->with('message', 'Company & Staff deleted successfully!');
    }
}
