<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $table = 'transactions_detail';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'hpp',
        'price',
        'process_status',
        'invoice_status',
        'qty',
        'is_cashback',
        'cashback_value',
        'qty_cashback_item',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
