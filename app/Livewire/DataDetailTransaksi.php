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

    #[On('deleteReceipt')]
    public function deleteDPReceiptt($data)
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {
            $transactionId = $this->id;
            DB::beginTransaction();
            try {
                $transaction = Transaction::find($transactionId);
                $transaction->update([
                    $data['type'] => null
                ]);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
            }
        }
    }

    public function changeStatus(Request $request, $id)
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:unprocessed,processed,taken,cancel'
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


    public function updateTransaction(Request $request, $id)
    {
        if (auth()->user()->role != 'admin' || auth()->user()->role != 'admincust') {
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

            if ($validator->fails()) {
                return back()
                    ->with('toast_error', join(', ', $validator->messages()->all()))
                    ->withInput()
                    ->withErrors($validator->messages()->all());
            }

            $transaction = Transaction::find($id);
            $imagesName = [];

            if (!empty($request->dp_payment_receipt)) {
                $dpPaymentReceipt = $request['dp_payment_receipt'];
                $dpPaymentReceiptImageName = Str::random(10) . '.' . $dpPaymentReceipt->getClientOriginalExtension();
                $dpPaymentReceipt->storeAs('public/paymentReceipt', $dpPaymentReceiptImageName);

                $imagesName['dp_payment_receipt'] = $dpPaymentReceiptImageName;

                //delete old image
                Storage::delete('public/paymentReceipt/' . $transaction->dp_payment_receipt);
            }

            if (!empty($request->full_payment_receipt)) {
                $fullPaymentReceipt = $request['full_payment_receipt'];
                $fullPaymentReceiptImageName = Str::random(10) . '.' . $fullPaymentReceipt->getClientOriginalExtension();
                $fullPaymentReceipt->storeAs('public/paymentReceipt', $fullPaymentReceiptImageName);

                $imagesName['full_payment_receipt'] = $fullPaymentReceiptImageName;

                //delete old image
                Storage::delete('public/paymentReceipt/' . $transaction->full_payment_receipt);
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
            ->where('transaction_id', $this->id)
            ->whereRelation('product', 'name', 'like', '%' . $this->keywords . '%')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.data-detail-transaksi', ['detailsTransactions' => $detailsTransactions, 'transaction' => $this->transaction]);
    }
}
