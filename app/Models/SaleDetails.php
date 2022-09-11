<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        "sale_id",
        "product_id",
        "amount",
        "line_discount",
    ];
    protected $casts = [
        'sale_id' => 'integer',
        'product_id' => 'integer'
    ];
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
