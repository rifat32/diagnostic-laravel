<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescribtion extends Model
{
    use HasFactory;
    protected $fillable = [
        "patient_id",
        "description",
        "note",
        "patient_history",
        "next_appointment",
        "fees",
        'medical_history',
        "appointment_id"
    ];
    protected $casts = [
        'patient_id' => 'integer',
        'appointment_id' => 'integer',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
    public function medicines()
    {
        return $this->hasMany(PrescriptionMedicine::class, 'prescription_id', 'id');
    }
    public function tests()
    {
        return $this->hasMany(PrescriptionTest::class, 'prescription_id', 'id');
    }
    public function cc()
    {
        return $this->hasMany(PrescriptionCC::class, 'prescription_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(PrescriptionPayment::class, 'prescription_id', 'id');
    }
}
