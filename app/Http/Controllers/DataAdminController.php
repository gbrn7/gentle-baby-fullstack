<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DataAdminController extends Controller
{
    public function index(){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust'){

            if(auth()->user()->role == 'super_admin'){
                $admins = User::where('id', '<>', auth()->user()->id)
                         ->whereIn('role', ['admin', 'super_admin'])
                         ->get();
            }else{
                $admins = CompanyMember::with('company')
                         ->with('user')
                         ->where('id', '<>', auth()->user()->id)
                         ->whereRelation('company', 'owner_id', auth()->user()->id)
                         ->get();
            }
            return view('data-admin', compact('admins'));
        }else{
            return back()->with('toast_error', 'Access Denied!!');
        }
    }

    public function store(Request $request){

        $currenUser = CompanyMember::where('company_id', auth()->user()->id)->first();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:super_admin,admin,super_admin_cust, admin_cust',
            'image_profile' => 'image|mimes:png,jpg,jpeg|max:10024',
        ]);

        if($validator->fails()){
            return back()
            ->with('toast_error', join(', ', $validator->messages()->all()))
            ->withInput()
            ->withErrors($validator->messages()->all());
        }

        $newAdmin = $request->except('_token');

        if(!empty( $request->image_profile)){
            $imageProfile = $request->image_profile;
            $imageName = Str::random(10).$imageProfile->getClientOriginalName();
    
            $imageProfile->storeAs('public/Storage/avatar', $imageName);
            $newAdmin['image_profile'] = $imageName;
        }

        DB::beginTransaction();
        $newAdmin = User::create($newAdmin);
        $companyMember = CompanyMember::create([
            'user_id' => $newAdmin->id,
            'company_id' => $currenUser->company_id,
        ]);
        DB::commit();

        return redirect()->route('data.admin')->with('toast_success', 'Admin Ditambahkan!');
    }

}
