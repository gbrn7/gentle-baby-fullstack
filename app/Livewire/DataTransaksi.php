<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\CompanyMember;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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

    public function downloadPDF($transactionId){
            $transaction = DB::table('transactions as t')
            ->join('transactions_detail as dt', 't.id', '=', 'dt.transaction_id')
            ->join('company as c', 't.company_id', '=', 'c.id')
            ->join('users as u', 'u.id', '=', 'c.owner_id')
            ->selectRaw("t.id,t.transaction_code,c.name as companyName, DATE_FORMAT(t.created_at, '%Y-%m-%d') AS transactionDate,
                        t.process_status as processStatus, t.amount as revenue, t.dp_value as dp_value, 
                        t.payment_status as payment_status, t.dp_status as dp_status, t.jatuh_tempo as jatuh_tempo, t.jatuh_tempo_dp as jatuh_tempo_dp, t.dp_payment_receipt, t.full_payment_receipt, 
                        u.name as owner_name ,u.email as owner_email, u.phone_number as owner_phone_number")
            ->where('t.id', $transactionId)
            ->groupBy('t.id')
            ->groupBy('u.email')
            ->groupBy('u.name')
            ->groupBy('u.phone_number')
            ->groupBy('t.transaction_code')
            ->groupBy('c.name')
            ->groupBy('t.created_at')
            ->groupBy('t.process_status')
            ->groupBy('t.amount')
            ->groupBy('t.dp_value')
            ->groupBy('t.payment_status')
            ->groupBy('t.dp_status')
            ->groupBy('t.jatuh_tempo')
            ->groupBy('t.jatuh_tempo_dp')
            ->groupBy('t.dp_payment_receipt')
            ->groupBy('t.full_payment_receipt')
            ->first();

            $detailsTransactions = TransactionDetail::with('transaction')
            ->with('product')
            ->where('transaction_id', $transactionId)
            ->get();

            $pdf = Pdf::loadView('invoice', [
                'transaction' => $transaction, 
                'detailsTransactions' => $detailsTransactions
            ]);
            
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
                }, 'Invoice-'.$transaction->transaction_code.'.pdf');    
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
