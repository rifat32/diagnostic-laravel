<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $fillable = [
        "name",
        "brand",
        "category",
        "sku",
        "price",
        "type"
    ];
    protected $casts = [
        'price' => 'integer'
    ];











    public function wing()
    {
        return $this->hasOne(Wing::class, 'id', 'wing_id')->withTrashed();
    }
}
