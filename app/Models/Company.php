<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'company';

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone_number',
        'owner_id',
    ];

    public function owner(){
        return $this->belongsTo(User::class);
    }
}
