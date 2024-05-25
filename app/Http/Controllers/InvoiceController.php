<?php

namespace App\Http\Controllers;

use App\Models\CompanyMember;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {

            $invoices = Invoice::with('company')
                ->when($request->search_value, function ($query) use ($request) {
                    return $query->where('invoice_code', 'like', '%' . $request->search_value . '%');
                })
                ->when(isset($request->payment_status), function ($query) use ($request) {
                    return $query->where('payment_status', (bool) $request->payment_status);
                })
                ->when(isset($request->dp_status), function ($query) use ($request) {
                    return $query->where('dp_status',  (bool) $request->dp_status);
                })
                ->orderBy('id', 'desc')
                ->paginate($request->pagination ? $request->pagination : 10);
        } elseif (auth()->user()->role == 'super_admin_cust') {

            $invoices = Invoice::with('company')
                ->when($request->search_filter_by && $request->search_value, function ($query) use ($request) {
                    return $query->where($request->search_filter_by, $request->search_value);
                })
                ->when($request->payment_status, function ($query) use ($request) {
                    return $query->where('payment_status', (bool) $request->payment_status);
                })
                ->when($request->dp_status, function ($query) use ($request) {
                    return $query->where('dp_status', (bool) $request->dp_status);
                })
                ->orderBy('id', 'desc')->whereRelation('company', 'owner_id', auth()->user()->id)
                ->paginate($request->pagination ? $request->pagination : 10);
        } else {

            $company = CompanyMember::with('company')
                ->where('user_id', auth()->user()->id)
                ->first();

            $invoices = Invoice::with('company')
                ->when($request->search_filter_by && $request->search_value, function ($query) use ($request) {
                    return $query->where($request->search_filter_by, $request->search_value);
                })
                ->when($request->payment_status, function ($query) use ($request) {
                    return $query->where('payment_status', (bool) $request->payment_status);
                })
                ->when($request->dp_status, function ($query) use ($request) {
                    return $query->where('dp_status', (bool) $request->dp_status);
                })
                ->where('company_id', $company->id)
                ->orderBy('id', 'desc')
                ->paginate($request->pagination ? $request->pagination : 10);
        }

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

    public function downloadPDF($invoiceCode)
    {
        $invoice = Invoice::with('detailTransactions.transaction')
            ->with('detailTransactions.product')
            ->with('company.owner')
            ->where('invoice_code', $invoiceCode)
            ->first();

        if (!$invoice) return back()->with('toast_error', 'Invoice tidak ditemukan');

        $pdf = Pdf::loadView('invoice', [
            'invoice' => $invoice,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Invoice-' . $invoice->invoice_code . '.pdf');
    }

    public function viewPDF($invoiceCode)
    {
        $invoice = Invoice::with('detailTransactions.transaction')
            ->with('detailTransactions.product')
            ->with('company.owner')
            ->where('invoice_code', $invoiceCode)
            ->first();


        if (!$invoice) return back()->with('toast_error', 'Invoice tidak ditemukan');

        $pdf = Pdf::loadView('invoice', [
            'invoice' => $invoice,
        ]);

        return $pdf->stream('invoice-' . $invoice->invoice_code);
    }
}
