<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();

        return view('data-produk.data-produk', compact('products'));
    }

    public function createProduct()
    {
        return view('data-produk.create-data-produk');
    }
    
    public function store(Request $request){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin'){
            $validation = [
                'name' => 'required|string',
                'hpp' => 'required|numeric',
                'price' => 'required|numeric',
                'size_volume' => 'required|string',
                'is_cashback' => 'required|numeric|in:0,1',
                'cashback_value' => 'required|numeric',
                'status' => 'required|in:active,inactive',
                'thumbnail' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            ];

            $messages = [
                'required' => 'Kolom :attribute harus diisi',
                'string' => 'Kolom :attribute harus bertipe teks atau string',
                'email' => 'Kolom :attribute harus bertipe email',
                'unique' => 'email yang anda berikan sudah dipakai',
                'min' => ':attribute minimal :min digit',
                'max' => ':attribute maksimal :max kB',
                'role' => ':attribute tidak valid',
                'image' => 'foto profil harus berjenis gambar',
                'mimes' => 'foto profil harus bertipe :values',
                'in' => 'role hanya boleh :values',
            ];

            $validator = Validator::make($request->all(), $validation, $messages);

            if($validator->fails()){
                return back()
                ->with('toast_error', join(', ', $validator->messages()->all()))
                ->withInput()
                ->withErrors($validator->messages()->all());
            }

            $newProduct = $request->except('_token');
            if(!empty( $newProduct['thumbnail'])){
                $thumbnail = $newProduct['thumbnail'];
                $imageName = Str::random(10).'.'.$thumbnail->getClientOriginalExtension();
        
                $thumbnail->storeAs('public/produk', $imageName);
                $newProduct['thumbnail'] = $imageName;            
            }

            DB::beginTransaction();
            try {
                $newProduct = Product::create($newProduct);
                DB::commit();
    
                return redirect()
                ->route('data.product')
                ->with('toast_success', 'Produk Ditambahkan!');    

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

    public function edit(Request $request, $id){
        $product = Product::find($id);

        return view('data-produk.edit-data-produk', compact('product'));
    }

    public function update(Request $request, $id){
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin'){
            $validation = [
                'name' => 'required|string',
                'hpp' => 'required|numeric',
                'price' => 'required|numeric',
                'size_volume' => 'required|string',
                'is_cashback' => 'required|numeric|in:0,1',
                'cashback_value' => 'required|numeric',
                'status' => 'required|in:active,inactive',
                'thumbnail' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            ];

            $messages = [
                'required' => 'Kolom :attribute harus diisi',
                'string' => 'Kolom :attribute harus bertipe teks atau string',
                'email' => 'Kolom :attribute harus bertipe email',
                'unique' => 'email yang anda berikan sudah dipakai',
                'min' => ':attribute minimal :min digit',
                'max' => ':attribute maksimal :max kB',
                'role' => ':attribute tidak valid',
                'image' => 'foto profil harus berjenis gambar',
                'mimes' => 'foto profil harus bertipe :values',
                'in' => 'role hanya boleh :values',
            ];

            $validator = Validator::make($request->all(), $validation, $messages);

            if($validator->fails()){
                return back()
                ->with('toast_error', join(', ', $validator->messages()->all()))
                ->withInput()
                ->withErrors($validator->messages()->all());
            }

            $oldProduct = Product::find($id);

            $updatedProduct = $request->except('_token');


            if(!empty( $updatedProduct['thumbnail'])){
                $thumbnail = $updatedProduct['thumbnail'];
                $imageName = Str::random(10).'.'.$thumbnail->getClientOriginalExtension();
        
                $thumbnail->storeAs('public/produk', $imageName);
                $updatedProduct['thumbnail'] = $imageName;
                
            //delete old image
            Storage::delete('public/produk/'.$oldProduct->thumbnail);
            }

            DB::beginTransaction();
            try {
                $oldProduct->update($updatedProduct);
                DB::commit();     

                return redirect()
                ->route('data.product')
                ->with('toast_success', 'Data Produk Diperbarui!!');  

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
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin'){
            $produkId = $request->id;
            $dataProduk = Product::find($produkId);
            if($dataProduk){

                if(!empty( $dataProduk->thumbnail)){
                //delete old image
                Storage::delete('public/produk/'.$dataProduk->thumbnail);
                }

                $dataProduk->delete();

            return redirect()
                ->route('data.product')
                ->with('toast_success', 'Produk '.$dataProduk->name.' dihapus!');
            }

            return back()
            ->with('toast_error', 'Produk ID not found');
        }else{
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function deleteThumbnail(Request $request){
    if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin'){
            $productId = $request->productId;
            DB::beginTransaction();
            try {
                $product = Product::find($productId);
                
                $product->update([
                    'thumbnail' => null
                ]);

                DB::commit();     

                return response()->json(['message' => 'Product '.$product->name.' updated']);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
}
