<?php

namespace App\Http\Services;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Prescribtion;
use App\Models\PrescriptionPayment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;

trait PrescriptionService
{
    public function createPrescriptionService($request)
    {
        $insetrableData = $request->validated();


     return   DB::transaction(function () use (&$insetrableData) {


        $appointment =     Appointment::where([
                "id" => $insetrableData["appointment_id"]
            ])
->first();
if($appointment->status == "treated"){
return response()->json([
    "message" => "Already treated"
],409);

} else {
    $appointment->status = "treated";
    $appointment->save();
}



            $prescription =   Prescribtion::create($insetrableData);


            foreach ($insetrableData["prescription"] as $medicine) {
                $prescription->medicines()->create([
                    "product_id" => $medicine["product_id"],
                    "product_name" => $medicine["product_name"],
                    "morning" => $medicine['times']["morning"],
                    "afternoon" => $medicine['times']['afternoon'],
                    "night" => $medicine['times']["night"],
                    "end_time" => $medicine["end_time"],
                ]);
            }

            foreach ($insetrableData["tests"] as $test) {
                $prescription->tests()->create([
                    "name" => $test["name"],
                ]);
            }
            foreach ($insetrableData["cc"] as $cc) {
                $prescription->cc()->create([
                    "name" => $cc["name"],
                    "value" => $cc["value"],
                ]);
            }
            foreach ($insetrableData["oe"] as $oe) {
                $prescription->oe()->create([
                    "name" => $oe["name"],
                    "value" => $oe["value"],
                ]);
            }

            $data["invoice"] = view("prescription.invoice", [
                "prescription" => $prescription
            ])->render();
            return response()->json([
                "prescription" => $prescription,
                "invoice" => $data["invoice"]
        ], 201);
        });
    }
    public function updatePrescriptionService($request)
    {
        $updatableData = $request->validated();
        $updated_prescription =  tap(Prescribtion::where(["id" => $updatableData["id"]]))->update(
            collect($updatableData)->only([
                "patient_id",
                "description",
                "past_medical_history",
                "drug_history",
                "next_appointment",
                "fees",
                'medical_history',
                "appointment_id"
            ])
                ->toArray()

        )
        ->first();
        $updated_prescription->medicines()->delete();
        $updated_prescription->tests()->delete();
        $updated_prescription->cc()->delete();
        $updated_prescription->oe()->delete();
        foreach ($updatableData["prescription"] as $medicine) {
            $updated_prescription->medicines()->create([
                "product_id" => $medicine["product_id"],
                "product_name" => $medicine["product_name"],
                "morning" => $medicine['times']["morning"],
                "afternoon" => $medicine['times']['afternoon'],
                "night" => $medicine['times']["night"],
                "end_time" => $medicine["end_time"],
            ]);
        }

        foreach ($updatableData["tests"] as $test) {
            $updated_prescription->tests()->create([
                "name" => $test["name"],
            ]);
        }
        foreach ($updatableData["cc"] as $cc) {
            $updated_prescription->cc()->create([
                "name" => $cc["name"],
                "value" => $cc["value"],
            ]);
        }
        foreach ($updatableData["oe"] as $oe) {
            $updated_prescription->oe()->create([
                "name" => $oe["name"],
                "value" => $oe["value"],
            ]);
        }

        return response()->json(["data" => $updated_prescription], 200);
    }


     public function addPaymentService($request)
    {
return DB::transaction(function ()use($request) {
    $prescription = Prescribtion::where([
        "id" => $request->prescription_id
    ])
    ->select(
        "id",
       "fees",
       "payment_status",
        DB::raw("SUM( (SELECT SUM(amount) FROM prescription_payments WHERE prescription_payments.prescription_id=prescribtions.id)) as paid"),

    )
    ->first();

    if($prescription->fees > $prescription->paid) {
        if(($prescription->fees - $prescription->paid) >= $request->amount) {
            $data["data"] =  PrescriptionPayment::create([
                "prescription_id" => $request->prescription_id,
                "amount" =>  $request->amount,
               ]);
               if(($prescription->fees - $prescription->paid) == $request->amount) {
                $prescription->payment_status = "paid";
                $prescription->save();
               }
        return response()->json($data, 201);

        }else {
            return response()->json([
                "message" => "paying more than due " . ($prescription->fees - $prescription->paid)
            ], 409);
        }

    }
    return response()->json([
        "message" => "Already paid"
    ], 409);
});




    }
    public function updatePatientService($request)
    {
        $data['data'] = tap(Patient::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "name",
                "email",
                "address",
                "phone",
                "sex",
                "age",
                "blood_group"
            )
        )->first();
        return response()->json($data, 200);
    }
    public function deletePrescriptionService($request)
    {
        Prescribtion::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getPrescriptionService($request)
    {
        $prescriptions =   Prescribtion::with("patient","payments")->paginate(10);
        return response()->json([
            "data" => $prescriptions
        ], 200);
    }
    public function getPrescriptionByPatientService($request,$id)
    {
        $prescriptions =   Prescribtion::with("patient","payments")
        ->where([
            "patient_id" => $id
        ])
        ->paginate(10);
        return response()->json([
            "data" => $prescriptions
        ], 200);
    }


    public function getDuePrescriptionService($request)
    {
        $prescriptions =   Prescribtion::with("patient","payments")
        ->where([
            "payment_status" => "due"
        ])
        ->paginate(10);
        return response()->json([
            "data" => $prescriptions
        ], 200);
    }


    public function searchPrescriptionByDateService($from,$to,$request)
    {
        $prescriptions =   Prescribtion::with("patient","payments")

        ->whereBetween('created_at', [$from, $to])
        ->paginate(10);

        return response()->json([
            "data" => $prescriptions
        ], 200);
    }

    public function getSinglePrescriptionService($id, $request)
    {
        $prescriptions =   Prescribtion::with("patient","medicines","tests","cc","oe","payments")->where([
            "id" => $id
        ])
            ->first();
        return response()->json([
            "data" => $prescriptions
        ], 200);
    }
    public function getPrescriptionInvoiceService($request,$id) {
        $prescription = Prescribtion::where([
            "id"=>$id
        ])
        ->first();
        $data["invoice"] = view("prescription.invoice", [
            "prescription" => $prescription
        ])->render();
        return response()->json([
            "prescription" => $prescription,
            "invoice" => $data["invoice"]
    ], 200);
    }


    public function addPatientPrescriptionPaymentService($request)
    {
        $request_amount = $request->paid_amount;
        $due_prescriptions = Prescribtion::where([
            "patient_id" => $request->patient_id,
            "payment_status"=>"due",

        ])
        ->select(
            "id",
            "payment_status",
            "fees",
            DB::raw("
            (
                SUM(fees)


            )
            as sum_fees

            "),
            DB::raw("
            (
                SUM(prescribtions.fees) -

            SUM( (SELECT SUM(amount) FROM prescription_payments WHERE prescription_payments.prescription_id=prescribtions.id))
            )
            as total_due

            "),

    )
->groupBy("id")
->orderBy("created_at")
        ->get();

$let_total_due = 0;
        foreach($due_prescriptions as $due_prescription){
            $let_total_due += ($due_prescription->total_due?$due_prescription->total_due:($due_prescription->sum_fees));

        }

        if($let_total_due < $request_amount){
            return response()->json([
              "message" =>  "greather than due" . $let_total_due
            ], 409);
        }







        foreach($due_prescriptions as $due_prescription){
         

          $total = $due_prescription->fees;


          $paid = $due_prescription->payments->sum("amount");

          $due = $total - $paid;
          if(($due - $request_amount) > 0) {

            $due_prescription->payments()->create([
                "amount" => $request_amount
            ]);
            break;
          }
          elseif(($due - $request_amount) == 0){

            $due_prescription->payments()->create([
                "amount" => $request_amount
            ]);
            $due_prescription->payment_status = "paid";
            $due_prescription->save();
            break;
          }
          else {


            $due_prescription->payments()->create([
                "amount" => $due
            ]);
            $due_prescription->payment_status = "paid";
            $due_prescription->save();
            $request_amount -= $due;
          }


        }
        // $data["data"] =  SalePayment::create([
        //     "sale_id" => $request->sale_id,
        //     "paid_amount" =>  $request->paid_amount,
        //    ]);
        return response()->json(["ok" => true], 200);
           return response()->json($due_prescription, 404);
    }

}
