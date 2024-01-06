<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(){
        // auth()->user() ? redirect()->route('dashboard')  : null; 
        // dd('test');
        return view('siginIn');
    }
}
