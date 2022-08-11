<?php

namespace App\Http\Services;


use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalePayment;
use Illuminate\Support\Facades\DB;

trait SaleService
{
    public function createSalesService($request)
    {
        $insetrableData = $request->validated();
       return DB::transaction(function ()use($insetrableData) {
            $sale=   Sale::create($insetrableData);
            foreach ($insetrableData["services"] as $service) {
                $sale->saleDetails()->create([
                    "sale_id"=> $sale->id,
                    "product_id"=> $service["product_id"],
                    "amount"=> Product::where("id",$service["product_id"])->first()->price,
                    "line_discount"=> $service["line_discount"],
                ]);
            }
            return response()->json(["ok"=>true], 201);
        });

    }
    public function updateSalesService($request)
    {
        $data['data'] = tap(Sale::where(["id" =>  $request["id"]]))->update(
        $request->only(
            "sale_date",
            "status",
            "patient_id",
            "doctor_id",
            "discount",

            )
        )->first();
        return response()->json($data, 200);
    }
    public function deleteSalesService($request)
    {
        Sale::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getSalesService($request)
    {
        $data['data'] =   Sale::with("patient","doctor","saleDetails","payments")
        ->paginate(10);
        return response()->json($data, 200);
    }
    public function searchSalesByDateService($from,$to,$request)
    {
        $data['data'] =   Sale::with("patient","doctor")
        ->where(
        "status", "!=",   "Treated"
        )
        ->whereBetween('created_at', [$from, $to])
        ->paginate(10);
        return response()->json($data, 200);
    }

    public function getSalesByIdService($id,$request)
    {
        $data['data'] = Sale::with("patient","doctor")
        ->where([
            "id" => $id
        ])
        ->first();

        return response()->json($data, 200);
    }
    public function addSalePaymentService($request)
    {
        $data["data"] =  SalePayment::create([
            "sale_id" => $request->sale_id,
            "paid_amount" =>  $request->paid_amount,
           ]);
           return response()->json($data, 201);
    }


}
