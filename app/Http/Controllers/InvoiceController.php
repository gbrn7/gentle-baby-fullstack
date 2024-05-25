<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {

        $invoices = Invoice::with('company')->orderBy('id', 'desc')->get();

        return view('data-invoice.index', compact('invoices'));
    }

    public function show(string $id)
    {
        $invoice = Invoice::with('detailTransactions.transaction')->find($id);

        if (!$invoice) return back()->with('toast_error', 'Invoice tidak ditemukan');

        $detailTransaction = $invoice->detailTransactions;

        $aggregationData = $detailTransaction->map(function ($item, $key) {
            return [
                'capital' => $item->hpp * $item->qty,
                'cashback' => $item->is_cashback ? $item->cashback_value * $item->qty_cashback_item : 0,
                'qty_cashback' => $item->is_cashback ? $item->qty_cashback_item : 0,
            ];
        });

        $totalHpp = $aggregationData->pluck('capital')->sum();

        $totalProfit = $invoice->amount - ($aggregationData->pluck('capital')->sum());

        $totalCashback = $aggregationData->pluck('cashback')->sum();

        $totalQtyCashback = $aggregationData->pluck('qty_cashback')->sum();

        return view('data-invoice.show', compact('invoice', 'totalProfit', 'totalCashback', 'totalQtyCashback', 'totalHpp'));
    }

    public function changePaymentStatus(Request $request, $id)
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {
            $validator = Validator::make($request->all(), [
                'payment_status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('toast_error', join(', ', $validator->messages()->all()))
                    ->withInput()
                    ->withErrors($validator->messages()->all());
            }

            $invoice = Invoice::find($id);


            if (!$invoice) return back()->with('toast_error', 'Invoice tidak ditemukan');

            try {
                $invoice->update([
                    'payment_status' => $request->payment_status,
                ]);

                session()->flash('success', 'Status pelunasan invoice #' . $invoice->invoice_code . ' berhasil di Perbarui!');

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

    public function changeDpStatus(Request $request, $id)
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {
            $validator = Validator::make($request->all(), [
                'dp_status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('toast_error', join(', ', $validator->messages()->all()))
                    ->withInput()
                    ->withErrors($validator->messages()->all());
            }

            $invoice = Invoice::find($id);


            if (!$invoice) return back()->with('toast_error', 'Invoice tidak ditemukan');

            try {
                $invoice->update([
                    'dp_status' => $request->dp_status,
                ]);

                session()->flash('success', 'Status Dp invoice #' . $invoice->invoice_code . ' berhasil di Perbarui!');

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
}
