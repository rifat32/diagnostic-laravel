<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicine extends Model
{
    use HasFactory;
    protected $fillable = [
        "prescription_id",
        "product_id",
        "product_name",
        "morning",
        "afternoon",
        "night",
        "end_time"
    ];
    protected $casts = [
        'prescription_id' => 'integer',
        'product_id' => 'integer',
    ];
}
