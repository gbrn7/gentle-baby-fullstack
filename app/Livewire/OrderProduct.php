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

class OrderProduct extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $companyColFil = 'c.name';
    public $companyFilVal = '';
    public $productColFil = 'name';
    public $productFilVal = '';
    public $page = 1;
    public $limit = 10;

    public function productPagination($page){
        $this->page = $page;
    }

    public function render()
    {
        $companies = DB::table('company as c')
                          ->join('users as u', 'c.owner_id', 'u.id')
                          ->selectRaw('c.name as companyName, u.name as ownerName, c.address as companyAddress')
                          ->where('u.role', '<>', 'super_admin')
                          ->where('u.role', '<>', 'admin')
                          ->where($this->companyColFil, 'like', '%'.$this->companyFilVal.'%')
                          ->orderBy('c.id', 'desc')
                          ->paginate(10);
        
        $products = Product::where('status', 'active')
                            ->where($this->productColFil, 'like', '%'.$this->productFilVal.'%')
                            ->orderBy('id', 'desc')
                            ->limit($this->limit)
                            ->skip($this->limit * ($this->page-1))
                            ->get();

        $productCount = Product::where('status', 'active')
                                ->where($this->productColFil, 'like', '%'.$this->productFilVal.'%')
                                ->orderBy('id', 'desc')
                                ->count();

        $productPages = ceil($productCount/$this->limit);

        return view('livewire.order-product', ['companies'=> $companies, 'products'=> $products, 'productPages' => $productPages]);
    }
}
