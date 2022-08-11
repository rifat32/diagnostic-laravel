<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    protected $fillable = [
        "sale_id",
        "paid_amount",
    ];
    use HasFactory;
}
