<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\Invoice;
use App\Models\TransactionDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\WablasTrait;

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

        $companies = Company::where('name', '<>', 'Gentle Baby')->get();

        return view('data-invoice.index', compact('invoices', 'companies'));
    }

    public function create(Request $request)
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {
            $allProcessedProduct = DB::table('transactions_detail as td')
                ->join('transactions as t', 'td.transaction_id', 't.id')
                ->join('company as c', 'c.id', 't.company_id')
                ->join('products as p', 'p.id', 'td.product_id')
                ->where('td.process_status', 'processed')
                ->where('td.invoice_id', null)
                ->selectRaw('td.id as td_id, t.transaction_code as t_code, td.qty as td_qty, p.name as p_name, td.price as td_price, t.created_at as t_created_at, c.name as company_name, c.id as company_id')
                ->get();

            $companies = $allProcessedProduct->unique('company_id');

            if ($request->company_id) {
                $transactionDetails = $allProcessedProduct->where('company_id', $request->company_id);
            }

            return view('data-invoice.create', ['companies' => $companies, 'transactionDetails' => isset($transactionDetails) ? $transactionDetails : []]);
        } else {
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
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

    public function store(Request $request)
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'transaction_detail_id' => 'required|array'
            ], [
                'transaction_detail_id.required' => 'Minimal pilih satu item',
            ]);

            if ($validator->fails()) {
                return back()
                    ->with('toast_error', join(', ', $validator->messages()->all()))
                    ->withInput()
                    ->withErrors($validator->messages()->all());
            }
            $transactionsDetailId = $request->transaction_detail_id;

            DB::beginTransaction();
            try {
                $transactionDetails = TransactionDetail::whereIn('id', $transactionsDetailId)
                    ->where('invoice_id', null)->get();

                $amount = $transactionDetails->sum(function ($item) {
                    return $item->price * $item->qty;
                });

                $payment_tempo = collect($this->checkPaymentDeadline($amount));

                $invoice = Invoice::create([
                    'invoice_code' => Str::random(10),
                    'company_id' => $request->company_id,
                    'amount' => $amount,
                    'payment_due_date' => $payment_tempo['payment_due_date'],
                    'payment_status' => false,
                    'dp_value' => $payment_tempo->has('dp_value') ? $payment_tempo['dp_value'] : 0,
                    'dp_due_date' => $payment_tempo->has('dp_due_date') ? $payment_tempo['dp_due_date'] : null,
                    'dp_status' => 0,
                ]);

                $transactionDetails = TransactionDetail::whereIn('id', $transactionsDetailId)
                    ->where('invoice_id', null)->update(['invoice_id' => $invoice->id]);

                try {
                    $invoice = $this->sendNotif($invoice->id);
                } catch (\Throwable $th) {
                    $errorMassage = $th->getMessage();
                }

                DB::commit();

                return redirect()->route('data-invoice.index')->with('toast_success', 'Berhasil Menambahkan Invoice');
            } catch (\Throwable $th) {
                DB::rollback();
                return back()
                    ->with('toast_error', $th->getMessage())
                    ->withInput()
                    ->withErrors($th->getMessage());
            }
        } else {
            return back()->with('toast_error', 'Akses Ditolak!!');
        }
    }

    public function sendNotif($invoiceId)
    {

        $invoice = Invoice::with('detailTransactions.transaction')
            ->with('detailTransactions.product')
            ->with('company.owner')
            ->find($invoiceId);

        $pdf = Pdf::loadView('invoice', [
            'invoice' => $invoice,
        ]);

        $content = $pdf->download()->getOriginalContent();
        Storage::put('public/invoices/Invoice-' . $invoice->invoice_code . '.pdf', $content);

        $company = Company::with('owner')->where('id', $invoice->company_id)->first();

        $data = [
            'name' => $company->name,
            'role_user' => $company->owner->role,
            'phone_number' => $company->owner->phone_number,
            'file_name' => "Invoice-" . $invoice->invoice_code . '.pdf',
            'invoice_code' => $invoice->invoice_code,
            'attachment' => 'public/invoices/Invoice-' . $invoice->invoice_code . '.pdf'
        ];

        Mail::to($company->owner->email)->send(new InvoiceMail($data));

        //send Wablas to customer
        $this->sendWablasNotif($data);

        $superAdmin = User::where('role', 'super_admin')->first();

        if ($superAdmin) {
            $data['role_user'] = $superAdmin->role;
            $data['phone_number'] = $superAdmin->phone_number;
            $data['super_admin_name'] = $superAdmin->name;

            //send notif to super admin
            Mail::to($superAdmin->email)->send(new InvoiceMail($data));

            // send Wablas notif to superadmin
            $this->sendWablasNotif($data);
        }

        Storage::delete('public/invoices/Invoice-' . $invoice->invoice_code . '.pdf');
    }

    public function checkPaymentDeadline($amount)
    {
        if ($amount > 100000000) {
            return  [
                'payment_due_date' => Carbon::now()->addWeeks(6),
                'dp_value' => ((35 / 100) * $amount),
                'dp_due_date' => Carbon::now()->addDay(),
            ];
        } else if ($amount > 70000000 && $amount <= 100000000) {
            return   [
                'payment_due_date' => Carbon::now()->addWeeks(4),
            ];
        } else if ($amount > 5000000 && $amount <= 70000000) {
            return  [
                'payment_due_date' => Carbon::now()->addWeeks(2),
            ];
        }
        return  [
            'payment_due_date' => Carbon::now()->addDays(2),
        ];
    }

    public function sendWablasNotif($data)
    {
        $data['attachment'] = 'public/Storage/invoices/Invoice-' . $data['invoice_code'] . '.pdf';
        if ($data['role_user'] !== 'super_admin') {
            $custMessage = "Kami ingin memberitahu Anda bahwa invoice dengan kode #" . $data['invoice_code'] . " untuk " . $data['name'] . " sudah dibuat. Silahkan cek email anda atau website Gentle Baby untuk melihat rincian pesanan anda yang telah tersedia. Terima Kasih.";

            $data['message'] = $custMessage;

            // send message
            WablasTrait::sendMessage($data);
        } else {
            $superAdminMessage = "Kami ingin memberitahu Anda bahwa invoice dengan kode #" . $data['invoice_code'] . " untuk " . $data['name'] . " sudah dibuat. Silahkan cek email anda atau website Gentle Baby untuk melihat rincian invoice. Terima Kasih.";

            $data['message'] = $superAdminMessage;

            // send message
            WablasTrait::sendMessage($data);
        }
    }
}
