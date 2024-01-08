<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyMember;
use Illuminate\Database\Eloquent\Builder;

class DataAdminController extends Controller
{
    public function index(){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust'){

            if(auth()->user()->role == 'super_admin'){
                $admins = CompanyMember::with('company')
                         ->with('user')
                         ->where('id', '<>', auth()->user()->id)
                         ->whereRelation('user', 'role', 'admin')
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

}
