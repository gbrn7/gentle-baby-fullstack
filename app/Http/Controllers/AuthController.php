<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    public function index(){        
        return auth()->check() ? redirect()->route('client') : view('siginIn');
    }

    public function authenticate(Request $request){

        $validation = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $messages = [
            'required' => 'Kolom :attribute harus diisi',
            'email' => 'Kolom :attribute harus bertipe email',
        ];


        $validator = Validator::make($request->all(), $validation, $messages);

        if($validator->fails()){
            return back()->with('toast_error', join(', ', $validator->messages()->all()))
            ->withInput()
            ->withErrors($validator->messages());
        }

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if($user){
            try {
                $pw = Crypt::decryptString($user->password);
            } catch (DecryptException $e) {
                return back()->with('toast_error', $e);
            } 
    
            if($pw === $credentials['password']){
                Auth::loginUsingId($user->id);
    
                $request->session()->regenerate();
    
                return redirect()->route('client')->with('toast_success', 'Berhasil Masuk');
            }
        }

        return back()->with('toast_error', 'Email atau password tidak valid!');
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('sign-in')->with('toast_success', 'Berhasil Keluar');
    }
}
