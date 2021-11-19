<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Storage;

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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|dimensions:min_width=100,min_height=100'
        ]);        

        //do file saving first
        $extension = $request->image->extension();
        $request->image->storeAs('/public', $validated['name'].".".$extension);
        $url = Storage::url($validated['name'].".".$extension);

        $company = new Company();
        $company->name = $request->name;
        $company->logo = $url;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->created_by = 1;
        $company->save();
        
        return redirect('/companies')->with('message', 'New Company Created successfully!');
        
    }
}
