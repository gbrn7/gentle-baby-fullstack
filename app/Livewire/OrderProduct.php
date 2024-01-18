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

    public function render()
    {
        $companies = DB::table('company as c')
                          ->join('users as u', 'c.owner_id', 'u.id')
                          ->selectRaw('c.name as companyName, u.name as ownerName, c.address as companyAddress')
                          ->where('u.role', '<>', 'super_admin')
                          ->where('u.role', '<>', 'admin')
                          ->where($this->companyColFil, 'like', '%'.$this->companyFilVal.'%')
                          ->paginate(10);

        return view('livewire.order-product', ['companies'=> $companies]);
    }
}
