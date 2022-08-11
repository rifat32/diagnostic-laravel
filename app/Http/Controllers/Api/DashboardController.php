<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Prescribtion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardReport(Request $request) {
     $data["today_income"] = Prescribtion::whereDate("created_at",Carbon::today())
     ->sum("fees");


     $data["total_appointment"] = Appointment::get()->count();


     $data["total_patient"] = Patient::get()->count();

     $data["total_prescription"] = Prescribtion::get()->count();

     $data["total_patient_history"] = Prescribtion::where("patient_history","!=",NULL)->get()->count();
    //  patient_history


     return response()->json($data,200);
    }
}
