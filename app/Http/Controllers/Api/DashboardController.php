<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Prescribtion;
use App\Models\PrescriptionPayment;
use App\Models\Sale;
use App\Models\SalePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboardReport(Request $request) {
        // prescription income
     $data["today_prescription_income"] = PrescriptionPayment::whereDate("created_at",Carbon::today())
     ->get()->sum("amount");
     $data["this_month_prescription_income"] = PrescriptionPayment::where(
        'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
    )
    ->get() ->sum("amount");
     $data["total_prescription_income"] = PrescriptionPayment::get()->sum("amount");
        // end prescription income
         // sale income
     $data["today_sale_income"] = SalePayment::whereDate("created_at",Carbon::today())
     ->get()->sum("paid_amount");

     $data["this_month_sale_income"] = SalePayment::where(
        'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
    )
    ->get()->sum("paid_amount");

     $data["total_sale_income"] = SalePayment::get()->sum("paid_amount");
        // end sale income

         // appointment
     $data["today_appointment"] = Appointment::whereDate("created_at",Carbon::today())
     ->get()->count();
     $data["this_month_appointment"] = Appointment::where(
        'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
    )
    ->get()->count();
     $data["total_appointment"] = Appointment::get()->count();
        // end appointment
// patient
$data["today_patient"] = Patient::whereDate("patients.created_at",Carbon::today())
->leftJoin('prescribtions', 'patients.id', '=', 'prescribtions.patient_id')
->leftJoin('sales', 'patients.id', '=', 'sales.patient_id')
->where(function ($query) {
    return $query
    ->where("sales.id","!=",NULL)
    ->orWhere("prescribtions.id","!=",NULL);
})->groupBy('patients.id')->get()->count();
$data["this_month_patient"] = Patient::where(
   'patients.created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
)
->leftJoin('prescribtions', 'patients.id', '=', 'prescribtions.patient_id')
->leftJoin('sales', 'patients.id', '=', 'sales.patient_id')
->where(function ($query) {
    return $query

    ->where("sales.id","!=",NULL)
    ->orWhere("prescribtions.id","!=",NULL);
})->groupBy('patients.id')->get()->count();
$data["total_patient"] = Patient::leftJoin('prescribtions', 'patients.id', '=', 'prescribtions.patient_id')
->leftJoin('sales', 'patients.id', '=', 'sales.patient_id')
->where(function ($query) {
    return $query

    ->where("sales.id","!=",NULL)
    ->orWhere("prescribtions.id","!=",NULL);
})->groupBy('patients.id')->get()->count();
   // end patient


 // prescription
 $data["today_prescription"] = Prescribtion::whereDate("created_at",Carbon::today())
 ->get()->count();
 $data["this_month_prescription"] = Prescribtion::where(
    'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
)
->get()->count();
 $data["total_prescription"] = Prescribtion::get()->count();
    // end prescription

 // medical_history
 $data["today_patient_history"] = Prescribtion::whereDate("created_at",Carbon::today())
 ->where("medical_history","!=",NULL)->get()->count();
 $data["this_month_patient_history"] = Prescribtion::where(
    'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
)
->where("medical_history","!=",NULL)->get()->count();
 $data["total_patient_history"] = Prescribtion::where("medical_history","!=",NULL)->get()->count();
    // end medical_history
// treatment plan
$data["today_sale"] = Sale::whereDate("created_at",Carbon::today())
->get()->count();
$data["this_month_sale"] = Sale::where(
   'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
)
->get()->count();
$data["total_sale"] = Sale::get()->count();
   // end treatment plan



    //  prescription due
     $data["total_prescription_due"] = 0;
     foreach(Prescribtion::get() as $prescription) {

        $data["total_prescription_due"] += ($prescription->fees - $prescription->payments->sum("amount"));
    }
    $data["today_prescription_due"] = 0;
    foreach(Prescribtion::whereDate("created_at",Carbon::today())->get() as $prescription) {

       $data["today_prescription_due"] += ($prescription->fees - $prescription->payments->sum("amount"));
   }
   $data["this_month_prescription_due"] = 0;
   foreach(Prescribtion::where(
    'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
)->get() as $prescription) {

      $data["this_month_prescription_due"] += ($prescription->fees - $prescription->payments->sum("amount"));
  }
// end prescription due
// sale due
$data["total_sale_due"] = 0;
     foreach(Sale::where([
        "payment_status" => "due",
        "status" => "Confirmed"
     ])->get() as $sale) {
        $let_sub_total = $sale->saleDetails->sum('amount');
        $let_discount = $sale->discount;
        $let_line_discount =  $sale->saleDetails->sum('line_discount');
        $let_paid_amount =  $sale->payments->sum('paid_amount');

        $data["total_sale_due"] += ($let_sub_total - (($let_discount?$let_discount:0) + ($let_line_discount?$let_line_discount:0))) - ($let_paid_amount?$let_paid_amount:0);

    }
    $data["today_sale_due"] = 0;
    foreach(Sale::where([
        "payment_status" => "due",
        "status" => "Confirmed"
     ])->whereDate("created_at",Carbon::today())->get() as $sale) {

        $let_sub_total = $sale->saleDetails->sum('amount');
        $let_discount = $sale->discount;
        $let_line_discount =  $sale->saleDetails->sum('line_discount');
        $let_paid_amount =  $sale->payments->sum('paid_amount');

        $data["today_sale_due"] += ($let_sub_total - (($let_discount?$let_discount:0) + ($let_line_discount?$let_line_discount:0))) - ($let_paid_amount?$let_paid_amount:0);
   }
   $data["this_month_sale_due"] = 0;
   foreach(Sale::where(
    'created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString()
)->where([
    "payment_status" => "due",
    "status" => "Confirmed"
 ])->get() as $sale) {


    $let_sub_total = $sale->saleDetails->sum('amount');
    $let_discount = $sale->discount;
    $let_line_discount =  $sale->saleDetails->sum('line_discount');
    $let_paid_amount =  $sale->payments->sum('paid_amount');

    $data["this_month_sale_due"] += ($let_sub_total - (($let_discount?$let_discount:0) + ($let_line_discount?$let_line_discount:0))) - ($let_paid_amount?$let_paid_amount:0);

  }


// end sale due



     return response()->json($data,200);
    }
}
