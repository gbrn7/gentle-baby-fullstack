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
            dd('test');
            return response()->json('[Access Denied or id not found]', 404);   
        }
    }

}
