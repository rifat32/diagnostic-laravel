<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $table = "sales";
    protected $fillable = [
        "sale_date",
        "status",
        "patient_id",
        "doctor_id",
        "discount",
    ];
    protected $casts = [
        'patient_id' => 'integer',
        'doctor_id' => 'integer'
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetails::class, 'sale_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(SalePayment::class, 'sale_id', 'id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id', 'doctor_id');
    }
    public function patient()
    {
        return $this->hasOne(Patient::class, 'id', 'patient_id');
    }
}
