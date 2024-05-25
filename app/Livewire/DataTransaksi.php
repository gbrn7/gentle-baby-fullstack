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
use App\Traits\WablasTrait;
use Carbon\Carbon;

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
    public $columnFilter = 't.transaction_code';
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
        if (auth()->user()->role === 'admin_cust') {
            $this->companyMember = CompanyMember::with('company')
                ->where('user_id', auth()->user()->id)
                ->first();
        }

        $this->startDate = Carbon::now()->startOfCentury();
        $this->endDate = Carbon::now()->endOfDay();
    }

    public function sendWablasNotif($data)
    {
        $data['attachment'] = 'public/Storage/invoices/Invoice-' . $data['transaction_code'] . '.pdf';

        $custMessage = "Kami ingin memberitahu Anda bahwa pesanan pada Baby Gentle dengan kode #" . $data['transaction_code'] . " oleh " . $data['name'] . " sudah dapat di ambil. Silahkan cek email anda atau website Baby Gentle untuk melihat rincian pesanan. Terima Kasih.";

        $data['message'] = $custMessage;

        // send message
        $result = WablasTrait::sendMessage($data);
    }

    #[On('dateRange')]
    public function dateOnChange($data)
    {
        $this->startDate = date("Y-m-d 00-00-00", strtotime($data['startDate']));
        $this->endDate = date("Y-m-d 23:59:59", strtotime($data['endDate']));
    }

    public function render()
    {
        if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin') {
            $transactions = DB::table('transactions as t')
                ->join('company as c', 't.company_id', '=', 'c.id')
                ->selectRaw("t.id as id,t.transaction_code, t.created_at, c.name")
                ->where($this->columnFilter, 'like', '%' . $this->keywords . '%')
                ->whereBetween($this->dateColumn, [$this->startDate, $this->endDate])
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate($this->pagination);
        } else if (auth()->user()->role == 'super_admin_cust') {
            $transactions = DB::table('transactions as t')
                ->join('company as c', 't.company_id', '=', 'c.id')
                ->selectRaw("t.id as id,t.transaction_code, t.created_at, c.name")
                ->where('c.owner_id', auth()->user()->id)
                ->where($this->columnFilter, 'like', '%' . $this->keywords . '%')
                ->whereBetween($this->dateColumn, [$this->startDate, $this->endDate])
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate($this->pagination);
        } else {
            $transactions = DB::table('transactions as t')
                ->join('company as c', 't.company_id', '=', 'c.id')
                ->selectRaw("t.id as id,t.transaction_code, t.created_at, c.name")
                ->where('c.id', $this->companyMember->company_id)
                ->where($this->columnFilter, 'like', '%' . $this->keywords . '%')
                ->whereBetween($this->dateColumn, [$this->startDate, $this->endDate])
                ->orderBy($this->sortColumn, $this->sortDirection)
                ->paginate($this->pagination);
        }

        return view('livewire.data-transaksi', compact('transactions'));
    }
}
