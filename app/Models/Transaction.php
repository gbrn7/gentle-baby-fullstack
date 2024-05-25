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
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
