<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {

            $invoices = Invoice::with('company')->orderBy('id', 'desc')->get();

            return view('data-invoice.index', compact('invoices'));
        } else {
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function destroy(string $id)
    {
        try {
            $invoice = Invoice::find($id);

            if (!$invoice) return redirect()->route('data-invoice.index')->with('toast_error', 'Invoice Not Found');

            $invoice->delete();

            return redirect()->route('data-invoice.index')->with('toast_success', 'Invoice deleted');
        } catch (\Throwable $th) {
            return back()
                ->with('toast_error', 'Failed delete');
        }
    }
}
