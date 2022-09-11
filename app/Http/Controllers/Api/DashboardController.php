<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Prescribtion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboardReport(Request $request) {
     $data["today_income"] = Prescribtion::whereDate("created_at",Carbon::today())
     ->sum("fees");


     $data["total_appointment"] = Appointment::get()->count();


     $data["total_patient"] = Patient::get()->count();

     $data["total_prescription"] = Prescribtion::get()->count();

     $data["total_patient_history"] = Prescribtion::where("drug_history","!=",NULL)->get()->count();

     $data["total_prescription_due"] = 0;
     foreach(Prescribtion::select(
        "id",
       "fees",
       "payment_status",
        DB::raw("SUM( (SELECT SUM(amount) FROM prescription_payments WHERE prescription_payments.prescription_id=prescribtions.id)) as paid"),

    )->get() as $prescription) {

        $data["total_prescription_due"] += ($prescription->fees - $prescription->paid);
    }
    $data["today_prescription_due"] = 0;
    foreach(Prescribtion::whereDate("created_at",Carbon::today()) ->select(
       "id",
      "fees",
      "payment_status",
       DB::raw("SUM( (SELECT SUM(amount) FROM prescription_payments WHERE prescription_payments.prescription_id=prescribtions.id)) as paid"),

   )->get() as $prescription) {

       $data["today_prescription_due"] += ($prescription->fees - $prescription->paid);
   }
   $data["this_month_prescription_due"] = 0;
   foreach(Prescribtion::where(
    'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
) ->select(
      "id",
     "fees",
     "payment_status",
      DB::raw("SUM( (SELECT SUM(amount) FROM prescription_payments WHERE prescription_payments.prescription_id=prescribtions.id)) as paid"),

  )->get() as $prescription) {

      $data["this_month_prescription_due"] += ($prescription->fees - $prescription->paid);
  }






     return response()->json($data,200);
    }
}
