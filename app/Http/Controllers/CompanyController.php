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
        if(auth()->user()->role == 'super_admin'){
            $ownerCompany = CompanyMember::find(auth()->user()->id);
            
            $companies = Company::with('owner')
                        ->where('id', '<>', $ownerCompany->id)
                        ->orderBy('id', 'desc')
                        ->get();
            return view('data-pelanggan.data-pelanggan', compact('companies'));
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
                'phone_number' => 'required',
                'name_company' => 'required|string',
                'email_company' => 'nullable|email|unique:company,email',
            ];

            $messages = [
                'required' => 'Kolom :attribute harus diisi',
                'string' => 'Kolom :attribute harus bertipe teks atau string',
                'email' => 'Kolom :attribute harus bertipe email',
                'unique' => 'email yang anda berikan sudah dipakai',
                'min' => ':attribute minimal :min digit',
            ];

            $validator = Validator::make($request->all(), $validation, $messages);

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
                ->with('toast_success', 'Perusahaan Ditambahkan!');        
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
                $form = Company::find($id);
                return view('modal.data-pelanggan.data-pelanggan-form', ['form' => $form]);
            }

            return response()->json('[Access Denied or id not found]', 404);   
        }else{
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function update(Request $request){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'super_admin_cust'){
            $companyId = $request->id;

            $validation = [
                'name' => 'required|string',
                'email' => 'nullable|string|email|unique:company,email,'.$companyId.',id',
                'address' => 'nullable|string',
                'phone_number' => 'nullable|string',
            ];

            $messages = [
                'required' => 'Kolom :attribute harus diisi',
                'string' => 'Kolom :attribute harus bertipe teks atau string',
                'email' => 'Kolom :attribute harus bertipe email',
                'unique' => 'email yang anda berikan sudah dipakai',
            ];

            $validator = Validator::make($request->all(), $validation, $messages);
    
            if($validator->fails()){
                return back()
                ->with('toast_error', join(', ', $validator->messages()->all()))
                ->withInput()
                ->withErrors($validator->messages()->all());
            }

            $oldCompany = Company::find($companyId);

            $updatedCompany = $request->except('_token');

            DB::beginTransaction();
            try {
                $oldCompany->update($updatedCompany);
                DB::commit();

                return back()
                ->with('toast_success', 'Data Perusahaan Diperbarui');

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

    public function getCurrentCompany(Request $request){
        $currentUser = auth()->user()->id;
        $currentCompany = CompanyMember::with('company')
                          ->where('user_id', $currentUser)->first();
        
        if($currentCompany){
            return view('modal.data-pelanggan.data-pelanggan-form', ['form' => $currentCompany->company]);
        }else{
            return back()->with('toast_error', 'Data tidak ditemukan!');
        }
    }

    public function detailCompany(Request $request, $id){
        if(auth()->user()->role == 'super_admin'){
            $adminCompany = CompanyMember::with('user')
                                            ->with('company')
                                            ->where('company_id', $id)
                                            ->get();

            return view('data-pelanggan.data-admin-pelanggan', ['admins' => $adminCompany]);
        }
            return back()->with('toast_error', 'Akses Ditolak!!');
    }

    public function storeAdmin(Request $request){
        if(auth()->user()->role == 'super_admin'){
            $validation = [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:5',
                'image_profile' => 'nullable|image|mimes:png,jpg,jpeg|max:10024',
            ];

            $messages = [
                'required' => 'Kolom :attribute harus diisi',
                'string' => 'Kolom :attribute harus bertipe teks atau string',
                'email' => 'Kolom :attribute harus bertipe email',
                'unique' => 'email yang anda berikan sudah dipakai',
                'min' => ':attribute minimal :min digit',
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
    
            $companyId = $request->idCompany;
    
            $newAdmin = $request->except('_token');
            $newAdmin['password'] = Crypt::encryptString($newAdmin['password']);
            $newAdmin['admin'] = 'admin_cust';
    
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
                    'company_id' => $companyId,
                ]);
                DB::commit();
    
                return back()
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

    public function getFormAdmin(Request $request){
        if(auth()->user()->role == 'super_admin'){
            $id = $request->id;

            if($id){
                $form = DB::table('users')
                ->select('*')
                ->where('id', $id)
                ->first();
                
                if($form->password){
                    $form->password = Crypt::decryptString($form->password);
                }

                return view('modal.data-pelanggan.data-admin-pelanggan-form', ['form' => $form]);
            }

            return response()->json('[Access Denied or id not found]', 404);   
        }else{
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function updateAdmin(Request $request){
        if(auth()->user()->role == 'super_admin'){
            $adminId = $request->id;
            $validation = [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email,'.$adminId.',id',
                'password' => 'required|string|min:5',
                'image_profile' => 'nullable|image|mimes:png,jpg,jpeg|max:10024',
            ];

            $messages = [
                'required' => 'Kolom :attribute harus diisi',
                'string' => 'Kolom :attribute harus bertipe teks atau string',
                'email' => 'Kolom :attribute harus bertipe email',
                'unique' => 'email yang anda berikan sudah dipakai',
                'min' => ':attribute minimal :min digit',
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
                ->with('toast_success', 'Data Admin Diperbarui');  
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

    public function deleteAdmin(Request $request){
        if(auth()->user()->role == 'super_admin'){
            $adminId = $request->id;
            $dataAdmin = User::find($adminId);
            if($adminId && $adminId != auth()->user()->id && $dataAdmin->role !== 'super_admin_cust'){
                $dataAdmin->delete();

            return back()
                ->with('toast_success', 'Admin '.$dataAdmin->name.' dihapus!');
            }

            return back()
            ->with('toast_error', 'Cannot delete admin');
        }else{
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

}
