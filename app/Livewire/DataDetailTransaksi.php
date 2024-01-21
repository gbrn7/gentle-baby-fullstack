<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class DataDetailTransaksi extends Component
{

    public $id;
    public $keywords ='';
    public $sortColumn = 'id';
    public $sortDirection = 'desc';
    public $transaction;
    public $cursorWait =  false;

    public function sort($columnName){
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function updateTransaction(Request $request, $id){
        if(auth()->user()->role != 'admin'){

            $cursorWait = true;

            $validation = [
                'dp_payment_receipt' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
                'full_payment_receipt' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            ];
    
            $messages = [
                'max' => 'File maksimal :max kB',
                'image' => 'File harus berjenis gambar',
                'mimes' => 'File harus bertipe :values',
            ];

            $validator = Validator::make($request->all(), $validation, $messages);

            if($validator->fails()){
                return back()
                ->with('toast_error', join(', ', $validator->messages()->all()))
                ->withInput()
                ->withErrors($validator->messages()->all());
            }

            $transaction = Transaction::find($id);
            $imagesName = [];

            if(!empty($request->dp_payment_receipt)){
                $dpPaymentReceipt = $request['dp_payment_receipt'];
                $dpPaymentReceiptImageName = Str::random(10).'.'.$dpPaymentReceipt->getClientOriginalExtension();
                $dpPaymentReceipt->storeAs('public/paymentReceipt', $dpPaymentReceiptImageName);

                $imagesName['dp_payment_receipt'] = $dpPaymentReceiptImageName;

                //delete old image
                 Storage::delete('public/paymentReceipt/'.$transaction->dp_payment_receipt);
            }

            if(!empty($request->full_payment_receipt)){
                $fullPaymentReceipt = $request['full_payment_receipt'];
                $fullPaymentReceiptImageName = Str::random(10).'.'.$fullPaymentReceipt->getClientOriginalExtension();
                $fullPaymentReceipt->storeAs('public/paymentReceipt', $fullPaymentReceiptImageName);

                $imagesName['full_payment_receipt'] = $fullPaymentReceiptImageName;

                //delete old image
                Storage::delete('public/paymentReceipt/'.$transaction->full_payment_receipt);
            }
            

            DB::beginTransaction();
            try {
                $transaction->update($imagesName);                
                
                DB::commit();   
                
                session()->flash('success', 'Data Transaksi di Perbarui!!');


                return back();
            } catch (\Throwable $th) {
                DB::rollback();

                session()->flash('error', 'Internal Server Error');


                return back()
                ->with('toast_error', $th->getMessage())
                ->withInput()
                ->withErrors($th->getMessage());
            }

            
        }else{
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }



    public function mount(Request $request,$id){
        $this->id = $id;

        $this->transaction = DB::table('transactions as t')
        ->join('transactions_detail as dt', 't.id', '=', 'dt.transaction_id')
        ->join('company as c', 't.company_id', '=', 'c.id')
        ->selectRaw("t.id,t.transaction_code,c.name as companyName, DATE_FORMAT(t.created_at, '%Y-%m-%d') AS transactionDate,
                    t.process_status as processStatus, t.amount as revenue, (t.amount - sum(dt.hpp * qty)) as profit,
                    sum(dt.cashback_value * dt.qty_cashback_item) as cashback, t.dp_value as dp_value, 
                    t.payment_status as payment_status, t.dp_status as dp_status, sum(dt.qty_cashback_item) as cashback_item,
                    t.jatuh_tempo as jatuh_tempo, t.jatuh_tempo_dp as jatuh_tempo_dp, t.dp_payment_receipt, t.full_payment_receipt")
        ->where('t.id', $this->id)
        ->groupBy('t.id')
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

    }

    public function render()
    {

        $detailsTransactions = TransactionDetail::with('transaction')
                        ->with('product')
                        ->where('transaction_id', $this->id)
                        ->whereRelation('product', 'name', 'like', '%'.$this->keywords.'%')
                        ->orderBy($this->sortColumn, $this->sortDirection)  
                        ->paginate(10);

        return view('livewire.data-detail-transaksi', ['detailsTransactions' => $detailsTransactions, 'transaction' => $this->transaction]);
    }
}
