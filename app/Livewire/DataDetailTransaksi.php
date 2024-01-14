<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class DataDetailTransaksi extends Component
{
    public $id;
    public $keywords ='';
    public $sortColumn = 'id';
    public $sortDirection = 'desc';

    public function sort($columnName){
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function mount(Request $request,$id){
        $this->id = $id;

    }

    public function render()
    {
        $transaction = Transaction::where('id', $this->id)->first();
        $detailsTransactions = TransactionDetail::with('transaction')
                        ->with('product')
                        ->where('transaction_id', $this->id)
                        ->whereRelation('product', 'name', 'like', '%'.$this->keywords.'%')
                        ->orderBy($this->sortColumn, $this->sortDirection)  
                        ->paginate(10);

                        return view('livewire.data-detail-transaksi', ['detailsTransactions' => $detailsTransactions, 'transaction' => $transaction]);
    }
}
