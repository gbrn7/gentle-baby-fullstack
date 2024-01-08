<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    protected $fillable = [
        'company_id',
        'jatuh_tempo_dp',
        'jatuh_tempo',
        'process_status',
        'payment_status',
        'dp_value',
        'dp_status',
        'transaction_complete_date',
        'payment_receipt',
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }

}