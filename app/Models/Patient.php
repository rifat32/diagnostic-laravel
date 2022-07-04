<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "email",
        "address",
        "phone",
        "sex",
        "birth_date",
        "blood_group"
    ];
    public function prescriptions()
    {
        return $this->hasMany(Prescribtion::class, 'patient_id', 'id')->latest();
    }

}
