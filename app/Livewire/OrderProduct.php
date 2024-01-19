<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Company;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class OrderProduct extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $allProduct ;
    public $allcompanies ;
    public $companyColFil = 'c.name';
    public $companyFilVal = '';
    public $productColFil = 'name';
    public $productFilVal = '';
    public $page = 1;
    public $limit = 10;
    public $productsCart = [];
    public $companyCart = [];


    public function productPagination($page){
        $this->page = $page;
    }

    public function addCompanyCart($company)
    {
        $this->companyCart = [
            "companyId" => $company['companyName'],
            "companyName" => $company['companyName'],
            "ownerName" => $company['ownerName'],
            "companyAddress" => $company['companyAddress'],
        ];
    }

    public function removeCompanyCart()
    {
        $this->companyCart = null;
    }

    public function incrementProductCart($index)
    {
        $carts = $this->productsCart->toArray();
        $carts[$index]['qty']++;
        $this->productsCart = collect($carts);

    }

    public function decrementProductCart($index)
    {
        $carts = $this->productsCart->toArray();
        $carts[$index]['qty']--;
        if($carts[$index]['qty'] == 0){
            $this->removeProductCart($index);
        }else{
            $this->productsCart = collect($carts);
        }
    }

    public function removeProductCart($index)
    {
        unset($this->productsCart[$index]);
        $this->productsCart->values();
    }

    public function addProductCart($product)
    {
       if($this->productsCart->doesntContain('id', $product['id'])){
        $this->productsCart->push([
            'id' => $product['id'],
            'name' => $product['name'],
            'thumbnail' => $product['thumbnail'],
            'price' => $product['price'],
            'qty' => 1
        ]);

       }else{
        $this->productsCart->transform(function($item, $key) use ($product) {
            if($item['id'] == $product['id']){
                $item['qty']++;
            }
            return $item;
        });
       };

    }

    public function mount()
    {
        $this->productsCart = collect($this->productsCart);
    }

    public function render()
    {
        if($this->companyFilVal){
        $companies = DB::table('company as c')
                            ->join('users as u', 'c.owner_id', 'u.id')
                            ->selectRaw('c.id as companyId, c.name as companyName, u.name as ownerName, c.address as companyAddress')
                            ->where('u.role', '<>', 'super_admin')
                            ->where('u.role', '<>', 'admin')
                            ->where($this->companyColFil, 'like', '%'.$this->companyFilVal.'%')
                            ->orderBy('c.id', 'desc')
                            ->limit($this->limit)
                            ->get();
        }else{
            $companies = DB::table('company as c')
            ->join('users as u', 'c.owner_id', 'u.id')
            ->selectRaw('c.id as companyId, c.name as companyName, u.name as ownerName, c.address as companyAddress')
            ->where('u.role', '<>', 'super_admin')
            ->where('u.role', '<>', 'admin')
            ->where($this->companyColFil, 'like', '%'.$this->companyFilVal.'%')
            ->orderBy('c.id', 'desc')
            ->paginate($this->limit);
        }
        
        $products = Product::where('status', 'active')
                            ->where($this->productColFil, 'like', '%'.$this->productFilVal.'%')
                            ->orderBy('id', 'desc')
                            ->limit($this->limit)
                            ->skip($this->limit * ($this->productFilVal ? 0 :  $this->page-1))
                            ->get();

        $productCount = Product::where('status', 'active')
                                ->where($this->productColFil, 'like', '%'.$this->productFilVal.'%')
                                ->orderBy('id', 'desc')
                                ->count();

        $productPages = ceil($productCount/$this->limit);


        return view('livewire.order-product', ['companies'=> $companies, 'products'=> $products, 'productPages' => $productPages]);
    }

}
