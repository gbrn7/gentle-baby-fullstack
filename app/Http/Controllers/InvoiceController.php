<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

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
}
