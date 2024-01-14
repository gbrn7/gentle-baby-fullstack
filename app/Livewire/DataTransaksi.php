<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
class DataTransaksi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
 
    public $keywords;
    public $sortColumn = 'id';
    public $sortDirection = 'desc';

    public function sort($columnName){
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        if($this->keywords != null){
            $transactions = Transaction::with('company')
            ->whereRelation('company','name', 'like', '%'.$this->keywords.'%')
            ->orWhere('id', 'like', '%'.$this->keywords.'%')
            ->orWhere('transaction_code', 'like', '%'.$this->keywords.'%')
            ->orWhere('amount', 'like', '%'.$this->keywords.'%')
            ->orderBy($this->sortColumn, $this->sortDirection)    
            ->paginate(10);
        }else{
            $transactions = Transaction::with('company')
            ->orderBy($this->sortColumn, $this->sortDirection)    
            ->paginate(10);
        }

        return view('livewire.data-transaksi', compact('transactions'));
    }
}
