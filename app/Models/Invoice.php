<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'invoice';

    //this means that what column is not permitted to fill
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function detailTransactions(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'id', 'invoice_id');
    }
}
