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
use Livewire\Attributes\On;

class DataDetailTransaksi extends Component
{

    public $id;
    public $keywords = '';
    public $sortColumn = 'id';
    public $sortDirection = 'desc';
    public $transaction;

    public function sort($columnName)
    {
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function changeStatus(Request $request, $id)
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:unprocessed,processing,processed,taken,cancel'
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('toast_error', join(', ', $validator->messages()->all()))
                    ->withInput()
                    ->withErrors($validator->messages()->all());
            }

            $transactionDetail = TransactionDetail::find($id);


            if (!$transactionDetail) return back()->with('toast_error', 'Detail transaksi tidak ditemukan');

            try {
                $transactionDetail->update([
                    'process_status' => $request->status,
                ]);
                session()->flash('success', 'Detail transaksi berhasil di Perbarui!');


                return back();
            } catch (\Throwable $th) {
                return back()
                    ->with('toast_error', $th->getMessage())
                    ->withInput()
                    ->withErrors($th->getMessage());
            }
        } else {
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function mount(Request $request, $id)
    {
        $this->id = $id;

        $this->transaction = DB::table('transactions as t')
            ->join('transactions_detail as dt', 't.id', '=', 'dt.transaction_id')
            ->join('company as c', 't.company_id', '=', 'c.id')
            ->selectRaw("t.id,t.transaction_code,c.name as companyName, DATE_FORMAT(t.created_at, '%Y-%m-%d') AS transactionDate, sum(dt.price * dt.qty) as revenue, sum(dt.price * dt.qty) - sum(dt.hpp * qty) as profit,
                    sum(dt.cashback_value * dt.qty_cashback_item) as cashback, sum(dt.qty_cashback_item) as cashback_item")
            ->where('t.id', $this->id)
            ->where('dt.process_status', '!=', 'cancel')
            ->groupBy('t.id')
            ->groupBy('t.transaction_code')
            ->groupBy('c.name')
            ->groupBy('t.created_at')
            ->first();
    }

    public function render()
    {
        $detailsTransactions = TransactionDetail::with('transaction')
            ->with('product')
            ->with('invoice')
            ->where('transaction_id', $this->id)
            ->whereRelation('product', 'name', 'like', '%' . $this->keywords . '%')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.data-detail-transaksi', ['detailsTransactions' => $detailsTransactions, 'transaction' => $this->transaction]);
    }
}
