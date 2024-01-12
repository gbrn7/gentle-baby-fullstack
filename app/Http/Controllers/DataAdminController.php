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
use Illuminate\Support\Facades\Crypt;

class DataAdminController extends Controller
{
    public function index(){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust'){

            if(auth()->user()->role == 'super_admin'){
                $admins = CompanyMember::with('company')
                ->with(["user" => function($q){ //user => name of model
                    $q->whereIn('users.role', ['admin', 'super_admin']); //users => name of table
                }])
                ->where('id', '<>', auth()->user()->id)
                ->whereRelation('company', 'owner_id', auth()->user()->id)
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
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function store(Request $request){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust'){

            $validation = [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:5',
                'role' => 'required|in:super_admin,admin,super_admin_cust,admin_cust',
                'image_profile' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            ];

            $messages = [
                'required' => 'Kolom :attribute harus diisi',
                'string' => 'Kolom :attribute harus bertipe teks atau string',
                'email' => 'Kolom :attribute harus bertipe email',
                'unique' => 'email yang anda berikan sudah dipakai',
                'min' => ':attribute minimal :min digit',
                'max' => ':attribute maksimal :max',
                'role' => ':attribute tidak valid',
                'image' => 'foto profil harus berjenis gambar',
                'mimes' => 'foto profil harus bertipe :values',
                'in' => 'role hanya boleh memiliki :values',
            ];

            $validator = Validator::make($request->all(), $validation, $messages);
    
            if($validator->fails()){
                return back()
                ->with('toast_error', join(', ', $validator->messages()->all()))
                ->withInput()
                ->withErrors($validator->messages()->all());
            }
    
            $currentUser = CompanyMember::where('user_id', auth()->user()->id)->first();
    
            $newAdmin = $request->except('_token');
            $newAdmin['password'] = Crypt::encryptString($newAdmin['password']);
    
            if(!empty( $request->image_profile)){
                $imageProfile = $request->image_profile;
                $imageName = Str::random(10).'.'.$imageProfile->getClientOriginalExtension();
        
                $imageProfile->storeAs('public/avatar', $imageName);
                $newAdmin['image_profile'] = $imageName;
            }
    
            DB::beginTransaction();
            try {
                $newAdmin = User::create($newAdmin);
                $companyMember = CompanyMember::create([
                    'user_id' => $newAdmin->id,
                    'company_id' => $currentUser->company_id,
                ]);
                DB::commit();
    
                return redirect()
                ->route('data.admin')
                ->with('toast_success', 'Admin Ditambahkan!');        
            } catch (\Throwable $th) {
                DB::rollback();
                return back()
                ->with('toast_error', $th->getMessage())
                ->withInput()
                ->withErrors($th->getMessage());
            }
        }else{
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function getForm(Request $request){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust'){
            $id = $request->id;
            if($id){
                $form = DB::table('users')
                        ->select('*')
                        ->where('id', $id)
                        ->first();
        
                if($form->password){
                    $form->password = Crypt::decryptString($form->password);
                }
                return view('modal.data-admin.data-admin-form', ['form' => $form]);
            }

            return response()->json('[Access Denied or id not found]', 404);   
        }else{
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function update(Request $request){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust' || auth()->user()->id == $request->id){
        $adminId = $request->id;

        $validation = [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$adminId.',id',
            'email' => 'required|string|email|unique:company,email',
            'password' => 'required|string|min:5',
            'role' => 'required|in:super_admin,admin,super_admin_cust,admin_cust',
            'image_profile' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
        ];

        $messages = [
            'required' => 'Kolom :attribute harus diisi',
            'string' => 'Kolom :attribute harus bertipe teks atau string',
            'email' => 'Kolom :attribute harus bertipe email',
            'unique' => ':attribute yang anda berikan sudah dipakai',
            'min' => ':attribute minimal :min digit',
            'image_profile.max' => 'Foto profil maksimal berukuran +-2MB',
            'role' => ':attribute tidak valid',
            'image' => 'foto profil harus berjenis gambar',
            'mimes' => 'foto profil harus bertipe :values',
        ];

        $validator = Validator::make($request->all(), $validation, $messages);

        if($validator->fails()){
            return back()
            ->with('toast_error', join(', ', $validator->messages()->all()))
            ->withInput()
            ->withErrors($validator->messages()->all());
        }

        $oldDataAdmin = User::where('id', $adminId)->first();

        $newAdmin = $request->except('_token');
        $newAdmin['password'] = Crypt::encryptString($newAdmin['password']);

        if(!empty( $request->image_profile)){
            $imageProfile = $request->image_profile;
            $imageName = Str::random(10).'.'.$imageProfile->getClientOriginalExtension();
    
            $imageProfile->storeAs('public/avatar/', $imageName);
            $newAdmin['image_profile'] = $imageName;

            //delete old image
            Storage::delete('public/avatar/'.$oldDataAdmin->image_profile);
        }

        DB::beginTransaction();
        try {
            $oldDataAdmin->update($newAdmin);
            DB::commit();        
            return back()
            ->with('toast_success', ((string) auth()->user()->id === $adminId 
            ? 'Data Profil Diperbarui!' 
            :'Data Admin Diperbarui!'));  
        } catch (\Throwable $th) {
            DB::rollback();
            return back()
            ->with('toast_error', $th->getMessage())
            ->withInput()
            ->withErrors($th->getMessage());
        }
        }else{
            return back()->with('toast_error', 'Akses Ditolak!!');
            }
    }

    public function delete(Request $request){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust'){
            $adminId = $request->id;
            $dataAdmin = User::find($adminId);
            if($adminId && $adminId != auth()->user()->id && $dataAdmin){
                $dataAdmin->delete();

            return redirect()
                ->route('data.admin')
                ->with('toast_success', 'Admin '.$dataAdmin->name.' dihapus!');
            }

            return back()
            ->with('toast_error', 'Admin ID not found');
        }else{
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }
}
