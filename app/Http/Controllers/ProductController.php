<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();

        return view('data-produk.data-produk', compact('products'));
    }

    public function createProduct(){
        return view('data-produk.create-data-produk');
    }
    
}
