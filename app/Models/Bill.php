<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $table = "bills";
    protected $fillable = [
        "vendor",
        "bill_date",
        "due_date",
        "category",
        "order_number",
        "discount_apply",
    ];



}
