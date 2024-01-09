<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;


class CompanyController extends Controller
{
    public function index(){
    if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin'){
        $ownerCompany = CompanyMember::find(auth()->user()->id);
        
        $companies = Company::with('owner')
                    ->where('id', '<>', $ownerCompany->id)
                    ->get();
        return view('data-pelanggan', compact('companies'));
    }else{
        return back()->with('toast_error', 'Access Denied!');
    }
    }

    public function store(Request $request){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust'){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:5',
                'phone_number' => 'required',
                'name_company' => 'required|string',
                'email_company' => 'nullable|email|unique:company,email',
            ]);
    
            if($validator->fails()){
                return back()
                ->with('toast_error', join(', ', $validator->messages()->all()))
                ->withInput()
                ->withErrors($validator->messages()->all());
            }


            $newUser = $request->except('name_company', 'email_company', 'address_company', 'phone_number_company', '_token');
            $newUser['password'] = Crypt::encryptString($newUser['password']);
            $newUser['role'] = 'super_admin_cust';

            DB::beginTransaction();
            try {
                $newUser = User::create($newUser);

                $newCompany = Company::create([
                    'name' => $request->name_company,
                    'email' => $request->email_company,
                    'phone_number' => $request->phone_number_company,
                    'address' => $request->address_company,
                    'owner_id' => $newUser->id,
                ]);
                
                CompanyMember::create([
                    'company_id' => $newCompany->id,
                    'user_id' => $newUser->id,
                ]);

                DB::commit();

                return back()
                ->with('toast_success', 'pelanggan Ditambahkan!');        
            } catch (\Throwable $th) {
                DB::rollback();
                return back()
                ->with('toast_error', $th->getMessage())
                ->withInput()
                ->withErrors($th->getMessage());
            }
        }else{
            return back()->with('toast_error', 'Access Denied!');

        }
    }
}
