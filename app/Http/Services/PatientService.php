<?php

namespace App\Http\Services;

use App\Models\Patient;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

trait PatientService
{
    public function createPatientService($request)
    {
        $patient =   Patient::create($request->all());
        return response()->json(["patient" => $patient], 201);
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
                "birth_date",
                "blood_group"
            )
        )->first();
        return response()->json($data, 200);
    }
    public function deletePatientService($request)
    {
        Patient::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getPatientsService($request)
    {
        $patients =   Patient::
        leftJoin('sales', 'patients.id', '=', 'sales.patient_id')
        ->select(
            "patients.id",
            "patients.name",
            "patients.phone",
            "patients.address",
            "patients.sex",
            "patients.birth_date",
            DB::raw("SUM(IF(sales.status = 'Confirmed', (SELECT SUM(paid_amount) FROM sale_payments WHERE sale_payments.sale_id=sales.id), 0)) as paid"),
            DB::raw("SUM(IF(sales.status = 'Confirmed', (SELECT SUM(line_discount) FROM sale_details WHERE sale_details.sale_id=sales.id), 0)) as line_discount"),
            DB::raw("SUM(IF(sales.status = 'Confirmed', (SELECT SUM(amount) FROM sale_details WHERE sale_details.sale_id=sales.id), 0)) as sub_total"),
            "sales.discount",
             DB::raw("

             (SUM(IF(sales.status = 'Confirmed', (SELECT SUM(amount) FROM sale_details WHERE sale_details.sale_id=sales.id), 0))


             -




             (sales.discount + SUM(IF(sales.status = 'Confirmed', (SELECT SUM(line_discount) FROM sale_details WHERE sale_details.sale_id=sales.id), 0)))


             )



             as total_discount"),
        )
        ->groupBy('patients.id')
        ->paginate(10);
        return response()->json([
            "data" => $patients
        ], 200);
    }
    public function getMainPatientsService($request)
    {
        $patients =   Patient::with("sales.saleDetails","sales.payments")
        ->leftJoin('prescribtions', 'patients.id', '=', 'prescribtions.patient_id')
        ->leftJoin('sales', 'patients.id', '=', 'sales.patient_id')
        ->where(function ($query) {
            return $query

            ->where("sales.id","!=",NULL)
            ->orWhere("prescribtions.id","!=",NULL);
        })



        ->select(
            "patients.id",
            "patients.name",
            "patients.phone",
            "patients.address",
            "patients.sex",
            "patients.birth_date",
            DB::raw("SUM(IF(sales.status = 'Confirmed', (SELECT SUM(paid_amount) FROM sale_payments WHERE sale_payments.sale_id=sales.id), 0)) as paid"),
            DB::raw("SUM(IF(sales.status = 'Confirmed', (SELECT SUM(line_discount) FROM sale_details WHERE sale_details.sale_id=sales.id), 0)) as line_discount"),
            DB::raw("SUM(IF(sales.status = 'Confirmed', (SELECT SUM(amount) FROM sale_details WHERE sale_details.sale_id=sales.id), 0)) as sub_total"),
            DB::raw("SUM(IF(sales.status = 'Confirmed', sales.discount, 0)) as discount"),

            DB::raw("(sales.discount + SUM(IF(sales.status = 'Confirmed', (SELECT SUM(line_discount) FROM sale_details WHERE sale_details.sale_id=sales.id), 0))) as total_discount"),
            // DB::raw("
            // (
            //     (SUM(IF(sales.status = 'Confirmed', (SELECT SUM(amount) FROM sale_details WHERE sale_details.sale_id=sales.id), 0))
            //     -
            //     (
            //         SUM(sales.discount)
            //     +
            //      SUM(IF(sales.status = 'Confirmed', (SELECT SUM(line_discount) FROM sale_details WHERE sale_details.sale_id=sales.id), 0))

            //      ))

            //      -

            //      SUM(IF(sales.status = 'Confirmed', (SELECT SUM(paid_amount) FROM sale_payments WHERE sale_payments.sale_id=sales.id), 0))

            // ) as total_sale_due

            // "),
            DB::raw("
                SUM(prescribtions.fees) as total_prescription_fees
            "),
            DB::raw(" SUM( (SELECT SUM(amount) FROM prescription_payments WHERE prescription_payments.prescription_id=prescribtions.id))

            as total_prescription_paid

            "),
            DB::raw("
            (
                SUM(prescribtions.fees) -

            SUM( (SELECT SUM(amount) FROM prescription_payments WHERE prescription_payments.prescription_id=prescribtions.id))
            )
            as total_prescription_due

            "),

        );

        if($request->has("search")) {
            $patients =  $patients->where(function($q){
                $q->where('patients.name', 'Like','%'.request()->search.'%');
                $q->orWhere('patients.email', 'Like','%'.request()->search.'%');
                $q->orWhere('patients.phone', 'Like','%'.request()->search.'%');

            });

        }

        $patients =  $patients->groupBy('patients.id')
        ->paginate(10);
        return response()->json([
            "data" => $patients
        ], 200);
    }


    public function searchPatientByDateService($from,$to,$request)
    {
        $patients =   Patient::
        whereBetween('created_at', [$from, $to])
        ->paginate(10);
        return response()->json([
            "data" => $patients
        ], 200);
    }

    public function getPatientByIdService($id,$request)
    {
        $patient =   Patient::
        with(
            "prescriptions.medicines",
            "prescriptions.tests",
            "prescriptions.cc",
            "prescriptions.oe",
            "prescriptions.payments",
        )
        ->where([
            "id" => $id
        ])
        ->first();
        return response()->json([
            "data" => $patient
        ], 200);
    }

    public function getAllPatientsService($request)
    {
        $patients =   Patient::all();
        return response()->json([
            "data" => $patients
        ], 200);
    }

}
