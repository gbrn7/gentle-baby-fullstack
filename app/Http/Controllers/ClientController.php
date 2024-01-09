<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ClientController extends Controller
{
    public function index(){
        return view('home');
    }

    public function getCurrentUser(){
        $currentUser = auth()->user();
        $currentUser['password'] = Crypt::decryptString($currentUser['password']);
        if($currentUser){
            return view('modal.data-admin-modal.data-admin-form', 
            ['form' => $currentUser]);
        }else{
            return response()->json('[Access Denied or id not found]', 404);   
        }
    }

    public function updateCurrentUser(){
        $adminId = $request->id;
        if(auth()->user()->id == $adminId){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email,'.$adminId.',id',
                'password' => 'required|string|min:5',
                'role' => 'required|in:super_admin,admin,super_admin_cust,admin_cust',
                'image_profile' => 'image|mimes:png,jpg,jpeg|max:10024',
            ]);
    
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
                $imageName = Str::random(10);
        
                $imageProfile->storeAs('public/avatar', $imageName);
                $newAdmin['image_profile'] = $imageName;
    
                //delete old image
                Storage::delete('public/avatar'.$oldDataAdmin->image_profile);
            }
    
            DB::beginTransaction();
            try {
                $oldDataAdmin->update($newAdmin);
                DB::commit();        
    
                return redirect()
                ->route('data.admin')
                ->with('toast_success', 'Data Admin Diperbarui!');  
            } catch (\Throwable $th) {
                DB::rollback();
                dd($th);
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
