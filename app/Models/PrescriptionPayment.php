<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        "prescription_id",
        "amount" ,
    ];
    protected $casts = [
        'prescription_id' => 'integer',
    ];

}
