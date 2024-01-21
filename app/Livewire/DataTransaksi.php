<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\CompanyMember;
use App\Models\Company;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusProcessNotify;
use Livewire\Attributes\On;

class DataTransaksi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
 
    public $keywords;
    public $sortColumn = 't.id';
    public $sortDirection = 'desc';
    public $companyMember;
    public $cursorWait =  false;
    public $pagination = 10;
    public $processStatus = '';
    public $paymentStatus = '';
    public $columnFilter = 't.transaction_code';
    public $dpStatus = '';
    public $dateColumn = 't.created_at';
    public $startDate = '';
    public $endDate = '';

    public function sort($columnName)
    {
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function mount()
    {
        if(auth()->user()->role === 'admin_cust'){
            $this->companyMember = CompanyMember::with('company')
            ->where('user_id', auth()->user()->id)
            ->first();
        }

        $this->startDate = date("Y-m-d 00-00-00", strtotime('2015-01-01'));
        $this->endDate = date("Y-m-d 23:59:59");
    }

    public function updateStatus(Request $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin'){
        
            $validation = [
                'payment_status' => 'required|in:0,1',
                'process_status' => 'required|in:unprocessed,processing,processed,taken,cancel',
                'dp_status' => 'required|in:0,1',
            ];
    
            $messages = [
                'payment_status' => ':attribute tidak valid',
                'process_status' => ':attribute tidak valid',
                'dp_status' => ':attribute tidak valid',
            ];
    
            $validator = Validator::make($request->all(), $validation, $messages);
    
            if($validator->fails()){
    
                session()->flash('error', join(', ', $validator->messages()->all()));

                return back()
                ->with('toast_error', join(', ', $validator->messages()->all()))
                ->withInput()
                ->withErrors($validator->messages()->all());
            }
    
            $transaction = Transaction::find($request->transaction_id);

            $oldStatus = [
                "process_status" => $transaction->process_status,
            ];
    
            DB::beginTransaction();
            try {


                $updatedTransaction = $transaction->update([
                    'process_status' => $request->process_status,
                    'payment_status' => $request->payment_status,
                    'dp_status' => $request->dp_status,
                    'transaction_complete_date' => $request->process_status === 'taken' ? now() : null,
                ]);


                $company = Company::with('owner')->where('id', $transaction->company_id)->first();

                if($transaction->process_status == 'processed' && $oldStatus['process_status'] !==  $transaction->process_status){
                    try {
                        $transaction = DB::table('transactions as t')
                        ->join('transactions_detail as dt', 't.id', '=', 'dt.transaction_id')
                        ->join('company as c', 't.company_id', '=', 'c.id')
                        ->join('users as u', 'u.id', '=', 'c.owner_id')
                        ->selectRaw("t.id,t.transaction_code,c.name as companyName, DATE_FORMAT(t.created_at, '%Y-%m-%d') AS transactionDate,
                                    t.process_status as processStatus, t.amount as revenue, t.dp_value as dp_value, 
                                    t.payment_status as payment_status, t.dp_status as dp_status, t.jatuh_tempo as jatuh_tempo, t.jatuh_tempo_dp as jatuh_tempo_dp, t.dp_payment_receipt, t.full_payment_receipt, 
                                    u.name as owner_name ,u.email as owner_email, u.phone_number as owner_phone_number")
                        ->where('t.id', $transaction->id)
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
                        ->where('transaction_id', $transaction->id)
                        ->get();
            
                        $pdf = Pdf::loadView('invoice', [
                            'transaction' => $transaction, 
                            'detailsTransactions' => $detailsTransactions
                        ]);

                        $content = $pdf->download()->getOriginalContent();
                        Storage::put('public/invoices/Invoice-'.$transaction->transaction_code.'.pdf', $content);

                        $data = [
                            'name' => $company->name,
                            'transaction_code' => $transaction->transaction_code,
                            'attachment' => 'public/invoices/Invoice-'.$transaction->transaction_code.'.pdf'
                        ];


                        // Mail::to('babygentleid@gmail.com')->send(new StatusProcessNotify($data));
                        Mail::to($company->owner->email)->send(new StatusProcessNotify($data));

                    } catch (\Throwable $th) {
                        session()->flash('error', $th->getMessage());

                        return back()
                        ->with('toast_error', $th->getMessage())
                        ->withInput()
                        ->withErrors($th->getMessage());                    }
                    // dd($transaction->process_status, $oldStatus['process_status']);
                }

                DB::commit();

                Storage::delete('public/invoices/Invoice-'.$transaction->transaction_code.'.pdf');

                session()->flash('success', 'Data Transaksi di Perbarui!!');

                return back()
                ->with('toast_success', 'Data Transaksi Diperbarui!!'); 

            } catch (\Throwable $th) {
                DB::rollback();

                session()->flash('error', $th->getMessage());

                return back()
                ->with('toast_error', $th->getMessage())
                ->withInput()
                ->withErrors($th->getMessage());
            }
        
        
        }else{
            session()->flash('error', 'Akses Ditolak');

            return back()->with('toast_error', 'Akses Ditolak!!');
        }

    }

    public function viewPDF($transactionId)
    {
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


        return $pdf->stream('Invoice-'.$transaction->transaction_code.'.pdf', array("Attachment" => false));
    }

    #[On('dateRange')]
    public function dateOnChange($data)
    {
        // dd($data);
        $this->startDate = date("Y-m-d 00-00-00", strtotime($data['startDate']));
        $this->endDate = date("Y-m-d 23:59:59", strtotime($data['endDate']));
    }

    public function downloadPDF($transactionId)
    {
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
            if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin'){
                $transactions = DB::table('transactions as t')
                ->join('company as c', 't.company_id', '=', 'c.id')
                ->selectRaw("t.id as id,t.transaction_code, t.created_at, c.name,
                t.process_status, t.amount, t.transaction_complete_date, 
                t.payment_status, t.jatuh_tempo, t.dp_status")
                ->where('t.process_status', 'like', '%'.$this->processStatus.'%')
                ->where('t.payment_status', 'like', '%'.$this->paymentStatus.'%')
                ->where('t.dp_status', 'like', '%'.$this->dpStatus.'%')
                ->where($this->columnFilter, 'like', '%'.$this->keywords.'%') 
                ->whereBetween($this->dateColumn, [$this->startDate, $this->endDate]) 
                ->orderBy($this->sortColumn, $this->sortDirection)    
                ->paginate($this->pagination);

            }   else if(auth()->user()->role == 'super_admin_cust') {
                $transactions = DB::table('transactions as t')
                ->join('company as c', 't.company_id', '=', 'c.id')
                ->selectRaw("t.id as id,t.transaction_code, t.created_at, c.name,
                t.process_status, t.amount, t.transaction_complete_date, 
                t.payment_status, t.jatuh_tempo, t.dp_status")
                ->where('c.owner_id', auth()->user()->id)
                ->where('t.process_status', 'like', '%'.$this->processStatus.'%')
                ->where('t.payment_status', 'like', '%'.$this->paymentStatus.'%')
                ->where('t.dp_status', 'like', '%'.$this->dpStatus.'%')
                ->where($this->columnFilter, 'like', '%'.$this->keywords.'%')
                ->whereBetween($this->dateColumn, [$this->startDate, $this->endDate]) 
                ->orderBy($this->sortColumn, $this->sortDirection)    
                ->paginate($this->pagination);

            }else{
                $transactions = DB::table('transactions as t')
                ->join('company as c', 't.company_id', '=', 'c.id')
                ->selectRaw("t.id as id,t.transaction_code, t.created_at, c.name,
                t.process_status, t.amount, t.transaction_complete_date, 
                t.payment_status, t.jatuh_tempo, t.dp_status")
                ->where('c.id', $this->companyMember->company_id)
                ->where('t.process_status', 'like', '%'.$this->processStatus.'%')
                ->where('t.payment_status', 'like', '%'.$this->paymentStatus.'%')
                ->where('t.dp_status', 'like', '%'.$this->dpStatus.'%')
                ->where($this->columnFilter, 'like', '%'.$this->keywords.'%')
                ->whereBetween($this->dateColumn, [$this->startDate, $this->endDate]) 
                ->orderBy($this->sortColumn, $this->sortDirection)    
                ->paginate($this->pagination);
            }

        // dd($this->dateColumn ,$this->startDate, $this->endDate);

        return view('livewire.data-transaksi', compact('transactions'));
    }

}
