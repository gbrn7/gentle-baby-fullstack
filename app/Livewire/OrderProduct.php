<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Company;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\CompanyMember;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\TransactionMail;
use Illuminate\Support\Facades\Storage;

class OrderProduct extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $allProduct ;
    public $allcompanies ;
    public $companyColFil = 'c.name';
    public $companyFilVal = '';
    public $productColFil = 'name';
    public $productFilVal = '';
    public $page = 1;
    public $limit = 10;
    public $productsCart ;
    public $companyCart ;


    public function productPagination($page)
    {
        $this->page = $page;
    }

    public function addCompanyCart($company)
    {
        $this->companyCart = [
            "companyId" => $company['companyId'],
            "companyName" => $company['companyName'],
            "ownerName" => $company['ownerName'],
            "companyAddress" => $company['companyAddress'],
        ];
    }

    public function removeCompanyCart()
    {
        $this->companyCart = null;
    }

    public function incrementProductCart($index)
    {
        $carts = $this->productsCart->toArray();
        $carts[$index]['qty']++;
        $this->productsCart = collect($carts);

    }

    public function createTransaction()
    {
        if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin' ){
            $this->adminTransaction();
            
        }else{
            $this->custTransaction();
        }
    }

    public function resetCart()
    {
        $this->productsCart = collect([]); 
        $this->companyCart = []; 
    }

    public function adminTransaction()
    {
        $validation = [
            'productsCart' => 'required',
            'companyCart' => 'required',
        ];

        $messages = [
            'productsCart.required' => 'Minimal pilih satu produk untuk checkout',
            'companyCart.required' => 'Pilih perusahaan terlebih dahulu',
        ];

        $variable = [
            "productsCart" => $this->productsCart,
            "companyCart" => $this->companyCart,
        ];

        $validator = Validator::make($variable, $validation, $messages);

        if($validator->fails()){
            $this->dispatch('endLoad');
            return $this->dispatch('warning', message: join(', ', $validator->messages()->all()));            
        }

        $amount = 0;
        foreach ($this->productsCart as $product)  {
            $amount += ($product['qty'] * $product['price']);
        };

        $payment = collect($this->checkPaymentDeadline($amount));

        //ensure the data is corrent
        $products = Product::WhereIn('id', $this->productsCart->pluck('id'))
                    ->orderBy('id', 'asc')
                    ->get();

        //sync the index products with products cart
        $productsCart = $this->productsCart->sortBy('id')->values();

        DB::beginTransaction();
        try {            
            $newTransaction = Transaction::create([
                'transaction_code' => Str::random(10),
                'company_id' =>  $this->companyCart['companyId'],
                'amount' => $amount,
                'jatuh_tempo' => $payment['jatuh_tempo'],
                'jatuh_tempo_dp' => $payment->has('jatuh_tempo_dp') ? $payment['jatuh_tempo_dp'] : null,
                'dp_value' => $payment->has('dp_value') ? $payment['dp_value'] : 0,
                'process_status' => 'unprocessed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            //Make transaction detail
            $transactionDetail = [];
            foreach ($products as $key => $product) {
                $arr = [
                    "transaction_id" => $newTransaction->id,
                    "product_id" => $product->id,
                    "hpp" => $product->hpp,
                    "price" => $product->price,
                    "qty" => $productsCart[$key]['qty'],
                    "is_cashback" => $product->is_cashback,
                    "cashback_value" => $product->cashback_value,
                    "qty_cashback_item" =>  ($productsCart[$key]['qty'] > 300 ?  $this->productsCart[$key]['qty'] - 300 : 0),
                    'created_at' => now(),
                    'updated_at' => now(),   
                ];

                array_push($transactionDetail, $arr);
            }

            //create transaction detail
            $newTransactionDetail = TransactionDetail::insert($transactionDetail);

            $this->sendMailNotif($newTransaction);

            DB::commit();

            $this->dispatch('endLoad');
            $this->resetCart();
            return $this->dispatch('success', message: 'Transaksi berhasil dibuat');

        } catch (\Throwable $th) {
            DB::rollback();

            return $this->dispatch('warning', message: $th->getMessage());
        }
    }

    public function custTransaction()
    {
        $validation = 
        [
            'productsCart' => 'required',
        ];

        $messages = 
        [
            'productsCart.required' => 'Minimal pilih satu produk untuk checkout'        
        ];

        $variable = 
        [
            "productsCart" => $this->productsCart,
        ];

        $validator = Validator::make($variable, $validation, $messages);

        if($validator->fails())
        {
            $this->dispatch('endLoad');
            return $this->dispatch('warning', message: join(', ', $validator->messages()->all()));            
        }

        $amount = 0;
        foreach ($this->productsCart as $product)  {
            $amount += ($product['qty'] * $product['price']);
        };

        $payment = collect($this->checkPaymentDeadline($amount));

        //ensure the data is corrent
        $products = Product::WhereIn('id', $this->productsCart->pluck('id'))
                    ->orderBy('id', 'asc')
                    ->get();

        //sync the index products with products cart
        $productsCart = $this->productsCart->sortBy('id')->values();

        //get company Id
        $companyId = CompanyMember::where('user_id', auth()->user()->id)->first();

        DB::beginTransaction();
        try {            
            $newTransaction = Transaction::create([
                'transaction_code' => Str::random(10),
                'company_id' =>  $companyId->company_id,
                'amount' => $amount,
                'jatuh_tempo' => $payment['jatuh_tempo'],
                'jatuh_tempo_dp' => $payment->has('jatuh_tempo_dp') ? $payment['jatuh_tempo_dp'] : null,
                'dp_value' => $payment->has('dp_value') ? $payment['dp_value'] : 0,
                'process_status' => 'unprocessed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            //Make transaction detail
            $transactionDetail = [];
            foreach ($products as $key => $product) {
                $arr = [
                    "transaction_id" => $newTransaction->id,
                    "product_id" => $product->id,
                    "hpp" => $product->hpp,
                    "price" => $product->price,
                    "qty" => $productsCart[$key]['qty'],
                    "is_cashback" => $product->is_cashback,
                    "cashback_value" => $product->cashback_value,
                    "qty_cashback_item" =>  ($productsCart[$key]['qty'] > 300 ?  $this->productsCart[$key]['qty'] - 300 : 0),
                    'created_at' => now(),
                    'updated_at' => now(),   
                ];

                array_push($transactionDetail, $arr);
            }

            //create transaction detail
            $newTransactionDetail = TransactionDetail::insert($transactionDetail);

            $this->sendMailNotif($newTransaction);

            DB::commit();

            $this->dispatch('endLoad');

            $this->resetCart();

            return $this->dispatch('success', message: 'Transaksi berhasil dibuat');

        } catch (\Throwable $th) {
            DB::rollback();

            return $this->dispatch('warning', message: $th->getMessage());
        }
    }

    public function checkPaymentDeadline($amount)
    {
        if($amount > 100000000){
            return [
                'jatuh_tempo' => (Carbon::now())->addWeeks(6),
                'jatuh_tempo_dp' => (Carbon::now())->addDay(),
                'dp_value' => ((35/100) * $amount),
            ];
        }else if($amount > 70000000 && $amount <= 100000000){
            return [
                'jatuh_tempo' => (Carbon::now())->addWeeks(4),
            ];
        }else if ($amount > 5000000 && $amount <= 70000000){
            return [
                'jatuh_tempo' => (Carbon::now())->addWeeks(2),
            ];
        }
        return [
            'jatuh_tempo' => (Carbon::now())->addDays(2),
        ]; 
    }

    public function decrementProductCart($index)
    {
        $carts = $this->productsCart->toArray();
        $carts[$index]['qty']--;
        if($carts[$index]['qty'] == 0){
            $this->removeProductCart($index);
        }else{
            $this->productsCart = collect($carts);
        }
    }

    public function removeProductCart($index)
    {
        unset($this->productsCart[$index]);
        $this->productsCart->values();
    }

    public function addProductCart($product)
    {
       if($this->productsCart->doesntContain('id', $product['id'])){
        $this->productsCart->push([
            'id' => $product['id'],
            'name' => $product['name'],
            'thumbnail' => $product['thumbnail'],
            'price' => $product['price'],
            'qty' => 1
        ]);

       }else{
        $this->productsCart->transform(function($item, $key) use ($product) {
            if($item['id'] == $product['id']){
                $item['qty']++;
            }
            return $item;
        });
       };

    }

    public function mount()
    {
        $this->productsCart = collect($this->productsCart);
    }

    public function render()
    {
        if($this->companyFilVal){
        $companies = DB::table('company as c')
                            ->join('users as u', 'c.owner_id', 'u.id')
                            ->selectRaw('c.id as companyId, c.name as companyName, u.name as ownerName, c.address as companyAddress')
                            ->where('u.role', '<>', 'super_admin')
                            ->where('u.role', '<>', 'admin')
                            ->where($this->companyColFil, 'like', '%'.$this->companyFilVal.'%')
                            ->orderBy('c.id', 'desc')
                            ->limit($this->limit)
                            ->get();
        }else{
            $companies = DB::table('company as c')
            ->join('users as u', 'c.owner_id', 'u.id')
            ->selectRaw('c.id as companyId, c.name as companyName, u.name as ownerName, c.address as companyAddress')
            ->where('u.role', '<>', 'super_admin')
            ->where('u.role', '<>', 'admin')
            ->where($this->companyColFil, 'like', '%'.$this->companyFilVal.'%')
            ->orderBy('c.id', 'desc')
            ->paginate($this->limit);
        }
        
        $products = Product::where('status', 'active')
                            ->where($this->productColFil, 'like', '%'.$this->productFilVal.'%')
                            ->orderBy('id', 'desc')
                            ->limit($this->limit)
                            ->skip($this->limit * ($this->productFilVal ? 0 :  $this->page-1))
                            ->get();

        $productCount = Product::where('status', 'active')
                                ->where($this->productColFil, 'like', '%'.$this->productFilVal.'%')
                                ->orderBy('id', 'desc')
                                ->count();

        $productPages = ceil($productCount/$this->limit);

        return view('livewire.order-product', ['companies'=> $companies, 'products'=> $products, 'productPages' => $productPages]);
    }

    public function sendMailNotif($transaction)
    {
        $company = Company::with('owner')->where('id', $transaction->company_id)->first();

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


        // Mail::to('babygentleid@gmail.com')->send(new TransactionMail($data));
        Mail::to($company->owner->email)->send(new TransactionMail($data));

        Storage::delete('public/invoices/Invoice-'.$transaction->transaction_code.'.pdf');
    }

}
