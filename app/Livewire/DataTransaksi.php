<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\CompanyMember;
use Livewire\WithPagination;
class DataTransaksi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
 
    public $keywords;
    public $sortColumn = 'id';
    public $sortDirection = 'desc';
    public $companyMember;

    public function sort($columnName){
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function mount(){
        if(auth()->user()->role === 'admin'){
            $this->companyMember = CompanyMember::with('company')
            ->where('user_id', auth()->user()->id)
            ->first();
        }
    }

    public function render()
    {
            if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin')
            $transactions = Transaction::with('company')
            ->whereRelation('company','name', 'like', '%'.$this->keywords.'%')
            ->orWhere('id', 'like', '%'.$this->keywords.'%')
            ->orWhere('transaction_code', 'like', '%'.$this->keywords.'%')
            ->orWhere('amount', 'like', '%'.$this->keywords.'%')
            ->orderBy($this->sortColumn, $this->sortDirection)    
            ->paginate(10);
            else if(auth()->user()->role == 'super_admin_cust'){
                $transactions = Transaction::with('company')
                ->whereRelation('company','name', 'like', '%'.$this->keywords.'%')
                ->whereRelation('company','owner_id', auth()->user()->id)
                ->orWhere('id', 'like', '%'.$this->keywords.'%')
                ->orWhere('transaction_code', 'like', '%'.$this->keywords.'%')
                ->orWhere('amount', 'like', '%'.$this->keywords.'%')
                ->orderBy($this->sortColumn, $this->sortDirection)    
                ->paginate(10);
            }else{
                $transactions = Transaction::with('company')
                ->whereRelation('company','name', 'like', '%'.$this->keywords.'%')
                ->whereRelation('company','owner_id', $this->companyMember)
                ->orWhere('id', 'like', '%'.$this->keywords.'%')
                ->orWhere('transaction_code', 'like', '%'.$this->keywords.'%')
                ->orWhere('amount', 'like', '%'.$this->keywords.'%')
                ->orderBy($this->sortColumn, $this->sortDirection)    
                ->paginate(10);
            }


        return view('livewire.data-transaksi', compact('transactions'));
    }
}
