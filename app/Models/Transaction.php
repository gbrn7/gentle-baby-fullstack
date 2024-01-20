<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    //this means that what column is permitted to fill
    protected $fillable = [
        'company_id',
        'transaction_code',
        'amount',
        'jatuh_tempo_dp',
        'jatuh_tempo',
        'process_status',
        'payment_status',
        'dp_value',
        'dp_status',
        'transaction_complete_date',
        'full_payment_receipt',
        'dp_payment_receipt',
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }

}
