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
            return view('modal.data-admin.data-admin-form', 
            ['form' => $currentUser]);
        }else{
            return response()->json('[Akses Ditolak atau Id Tidak Ditemukan!!]', 404);   
        }
    }

}
