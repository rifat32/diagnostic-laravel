<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionCC extends Model
{
    use HasFactory;
    protected $fillable = [
        "prescription_id",
        "name",
        "value",
    ];
    protected $casts = [
        'prescription_id' => 'integer',
    ];
}
