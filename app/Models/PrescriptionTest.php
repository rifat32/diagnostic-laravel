<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionTest extends Model
{
    use HasFactory;
    protected $fillable = [
        "prescription_id",
        "name",
        "type"
    ];
    protected $casts = [
        'prescription_id' => 'integer',
    ];
}
