<?php

namespace App\Http\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Product;


trait AppointmentService
{
    public function createAppointmentService($request)
    {
        $data['data'] =   Appointment::create($request->all());
        return response()->json($data, 201);
    }
    public function updateAppointmentService($request)
    {
        $data['data'] = tap(Appointment::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "date",
        "remarks",
        "status",
        "doctor_id",
        "patient_id",
        "problem"

            )
        )->first();
        return response()->json($data, 200);
    }
    public function deleteAppointmentService($request)
    {
        Appointment::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getAppointmentsService($request)
    {
        $data['data'] =   Appointment::with("patient","doctor")
        ->where(
        "status", "!=",   "Treated"
        )
        ->paginate(10);
        return response()->json($data, 200);
    }
    public function searchAppointmentByDateService($from,$to,$request)
    {
        $data['data'] =   Appointment::with("patient","doctor")
        ->where(
        "status", "!=",   "Treated"
        )
        ->whereBetween('created_at', [$from, $to])
        ->paginate(10);
        return response()->json($data, 200);
    }

    public function getAppointmentByIdService($id,$request)
    {
        $data['data'] = Appointment::with("patient","doctor")
        ->where([
            "id" => $id
        ])
        ->first();

        return response()->json($data, 200);
    }

}
