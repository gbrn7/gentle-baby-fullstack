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
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\TransactionMail;
use Illuminate\Support\Facades\Storage;
use App\Traits\WablasTrait;

class OrderProduct extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $allProduct;
    public $allcompanies;
    public $companyColFil = 'c.name';
    public $companyFilVal = '';
    public $productColFil = 'name';
    public $productFilVal = '';
    public $page = 1;
    public $limit = 10;
    public $productsCart;
    public $companyCart;


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
        if (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin') {
            $this->adminTransaction();
        } else {
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

        if ($validator->fails()) {
            $this->dispatch('endLoad');
            return $this->dispatch('warning', message: join(', ', $validator->messages()->all()));
        }

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
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            //Make transaction detail
            $transactionDetail = [];
            $productDetail = collect([]);
            foreach ($products as $key => $product) {
                $arr = [
                    "transaction_id" => $newTransaction->id,
                    "product_id" => $product->id,
                    "invoice_id" => null,
                    "hpp" => $product->hpp,
                    "price" => $product->price,
                    "qty" => $productsCart[$key]['qty'],
                    "is_cashback" => $product->is_cashback,
                    "cashback_value" => $product->cashback_value,
                    "qty_cashback_item" => ($productsCart[$key]['qty'] > 300 ?  $this->productsCart[$key]['qty'] - 300 : 0),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                array_push($transactionDetail, $arr);

                $productDetail->push([
                    "transaction_id" => $newTransaction->id,
                    "product_id" => $product->id,
                    "product_name" => $product->name,
                    "qty" => $productsCart[$key]['qty'],
                    "price" => $product->price,
                ]);
            }

            //create transaction detail
            TransactionDetail::insert($transactionDetail);


            try {
                $this->sendNotif($newTransaction, $productDetail);
            } catch (\Throwable $th) {
                $error = $th->getMessage();
            }

            DB::commit();

            $this->dispatch('endLoad');
            $this->resetCart();

            return $this->dispatch('success', message: 'Transaksi berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollback();

            $this->dispatch('endLoad');

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

        if ($validator->fails()) {
            $this->dispatch('endLoad');
            return $this->dispatch('warning', message: join(', ', $validator->messages()->all()));
        }


        //ensure the data is corrent
        $products = Product::WhereIn('id', $this->productsCart->pluck('id'))
            ->orderBy('id', 'asc')
            ->get();

        //sync the index products with products cart
        $productsCart = $this->productsCart->sortBy('id')->values();

        //get company Id
        $company = CompanyMember::where('user_id', auth()->user()->id)->first();

        DB::beginTransaction();
        try {
            $newTransaction = Transaction::create([
                'transaction_code' => Str::random(10),
                'company_id' =>  $company->company_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);



            //Make transaction detail
            $transactionDetail = [];
            $productDetail = collect([]);
            foreach ($products as $key => $product) {
                $arr = [
                    "transaction_id" => $newTransaction->id,
                    "product_id" => $product->id,
                    "invoice_id" => null,
                    "hpp" => $product->hpp,
                    "price" => $product->price,
                    "qty" => $productsCart[$key]['qty'],
                    "is_cashback" => $product->is_cashback,
                    "cashback_value" => $product->cashback_value,
                    "qty_cashback_item" => ($productsCart[$key]['qty'] > 300 ?  $this->productsCart[$key]['qty'] - 300 : 0),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                array_push($transactionDetail, $arr);

                $productDetail->push([
                    "transaction_id" => $newTransaction->id,
                    "product_id" => $product->id,
                    "product_name" => $product->name,
                    "qty" => $productsCart[$key]['qty'],
                    "price" => $product->price,
                ]);
            }


            //create transaction detail
            TransactionDetail::insert($transactionDetail);

            try {
                $this->sendNotif($newTransaction, $productDetail);
            } catch (\Throwable $th) {
                $error = $th->getMessage();
            }

            DB::commit();

            $this->dispatch('endLoad');

            $this->resetCart();

            return $this->dispatch('success', message: 'Transaksi berhasil dibuat');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->dispatch('endLoad');

            return $this->dispatch('warning', message: $th->getMessage());
        }
    }


    public function decrementProductCart($index)
    {
        $carts = $this->productsCart->toArray();
        $carts[$index]['qty']--;
        if ($carts[$index]['qty'] == 0) {
            $this->removeProductCart($index);
        } else {
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
        if ($this->productsCart->doesntContain('id', $product['id'])) {
            $this->productsCart->push([
                'id' => $product['id'],
                'name' => $product['name'],
                'thumbnail' => $product['thumbnail'],
                'price' => $product['price'],
                'qty' => 1
            ]);
        } else {
            $this->productsCart->transform(function ($item, $key) use ($product) {
                if ($item['id'] == $product['id']) {
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
        if ($this->companyFilVal) {
            $companies = DB::table('company as c')
                ->join('users as u', 'c.owner_id', 'u.id')
                ->selectRaw('c.id as companyId, c.name as companyName, u.name as ownerName, c.address as companyAddress')
                ->where('u.role', '<>', 'super_admin')
                ->where('u.role', '<>', 'admin')
                ->where($this->companyColFil, 'like', '%' . $this->companyFilVal . '%')
                ->orderBy('c.id', 'desc')
                ->limit($this->limit)
                ->get();
        } else {
            $companies = DB::table('company as c')
                ->join('users as u', 'c.owner_id', 'u.id')
                ->selectRaw('c.id as companyId, c.name as companyName, u.name as ownerName, c.address as companyAddress')
                ->where('u.role', '<>', 'super_admin')
                ->where('u.role', '<>', 'admin')
                ->where($this->companyColFil, 'like', '%' . $this->companyFilVal . '%')
                ->orderBy('c.id', 'desc')
                ->paginate($this->limit);
        }

        $products = Product::where('status', 'active')
            ->where($this->productColFil, 'like', '%' . $this->productFilVal . '%')
            ->orderBy('id', 'desc')
            ->limit($this->limit)
            ->skip($this->limit * ($this->productFilVal ? 0 :  $this->page - 1))
            ->get();

        $productCount = Product::where('status', 'active')
            ->where($this->productColFil, 'like', '%' . $this->productFilVal . '%')
            ->orderBy('id', 'desc')
            ->count();

        $productPages = ceil($productCount / $this->limit);

        return view('livewire.order-product', ['companies' => $companies, 'products' => $products, 'productPages' => $productPages]);
    }

    public function sendNotif($newTransaction, $productDetail)
    {
        $company = Company::with('owner')->where('id', $newTransaction->company_id)->first();

        $transactionTotal = $productDetail->sum(function ($item) {
            return ($item['qty'] * $item['price']);
        });

        $data = [
            'company_name' => $company->name,
            'role_user' => $company->owner->role,
            'phone_number' => $company->owner->phone_number,
            'transaction_code' => $newTransaction->transaction_code,
            'productDetail' => $productDetail,
            'transaction_total' => $transactionTotal,
        ];

        Mail::to($company->owner->email)->send(new TransactionMail($data));

        //send Wablas to customer
        $this->sendWablasNotif($data);

        $superAdmin = User::where('role', 'super_admin')->first();

        if ($superAdmin) {
            $data['role_user'] = $superAdmin->role;
            $data['phone_number'] = $superAdmin->phone_number;
            $data['super_admin_name'] = $superAdmin->name;

            //send notif to super admin
            Mail::to($superAdmin->email)->send(new TransactionMail($data));

            // send Wablas notif to superadmin
            $this->sendWablasNotif($data);
        }
    }

    public function sendWablasNotif($data)
    {
        if ($data['role_user'] !== 'super_admin') {
            $custMessage = "Kami ingin memberitahu Anda bahwa pesanan pada Gentle Baby dengan kode #" . $data['transaction_code'] . " oleh " . $data['company_name'] . " sudah masuk. Silahkan cek email anda atau website Gentle Baby untuk melihat rincian pesanan. Terima Kasih.";

            $data['message'] = $custMessage;

            // send message
            $result = WablasTrait::sendMessage($data);
        } else {
            $superAdminMessage = "Kami ingin memberitahu Anda bahwa pesanan pada Gentle Baby dengan kode #" . $data['transaction_code'] . " oleh " . $data['company_name'] . " sudah masuk. Silahkan cek email anda atau website Gentle Baby untuk melihat rincian pesanan. Terima Kasih.";

            $data['message'] = $superAdminMessage;

            // send message
            $result = WablasTrait::sendMessage($data);
        }
    }
}
