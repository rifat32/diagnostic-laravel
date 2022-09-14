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
        $updatableData = $request->validated();
        $updated_sale =  tap(Sale::where(["id" => $updatableData["id"]]))->update(
            collect($updatableData)->only([
                "sale_date",
        "status",
        "payment_status",
        "patient_id",
        "doctor_id",
        "discount",
            ])
                ->toArray()

        )
        ->first();
        $updated_sale->saleDetails()->delete();

        foreach ($updatableData["services"] as $service) {
            $updated_sale->saleDetails()->create([
                "sale_id"=> $updated_sale->id,
                "product_id"=> $service["product_id"],
                "amount"=> Product::where("id",$service["product_id"])->first()->price,
                "line_discount"=> $service["line_discount"],
            ]);
        }



        return response()->json(["data" => $updated_sale], 200);
    }
    // public function updateSalesService($request)
    // {
    //     $data['data'] = tap(Sale::where(["id" =>  $request["id"]]))->update(
    //     $request->only(
    //         "sale_date",
    //         "status",
    //         "patient_id",
    //         "doctor_id",
    //         "discount",

    //         )
    //     )->first();
    //     return response()->json($data, 200);
    // }
    public function deleteSalesService($request)
    {
        Sale::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getSalesService($request)
    {
        $data['data'] =   Sale::with("patient","doctor","saleDetails","payments")
        ->orderByDesc("id")->paginate(10);
        return response()->json($data, 200);
    }
    public function getSalesByPatientService($request,$id)
    {
        $data['data'] =   Sale::with("patient","doctor","saleDetails","payments")
        ->where([
           "patient_id" => $id
        ])
        ->orderByDesc("id")->paginate(10);
        return response()->json($data, 200);
    }





    public function searchSalesByDateService($from,$to,$request)
    {
        $data['data'] =   Sale::with("patient","doctor")
        ->where(
        "status", "!=",   "Treated"
        )
        ->whereBetween('created_at', [$from, $to])
        ->orderByDesc("id") ->paginate(10);
        return response()->json($data, 200);
    }

    public function getSalesByIdService($id,$request)
    {
        $data['data'] = Sale::with("patient","doctor","saleDetails.product")
        ->where([
            "id" => $id
        ])
        ->first();

        return response()->json($data, 200);
    }
    public function addSalePaymentService($request)
    {
$sale = Sale::where([
    "id" => $request->sale_id,

])



->first();

if($sale->status != "Confirmed") {
    return response()->json([
        "message" =>  "sell is not confirmed" . $sale->status
      ], 409);
}
$let_sub_total = $sale->saleDetails->sum('amount');
$let_discount = $sale->discount;
$let_line_discount =  $sale->saleDetails->sum('line_discount');
$let_paid_amount =  $sale->payments->sum('paid_amount');

$let_total_due = ($let_sub_total - ($let_discount - $let_line_discount)) - $let_paid_amount;

if(($let_total_due < $request->paid_amount)){
    return response()->json([
      "message" =>  "greather than due " . $let_total_due
    ], 409);
}


// return response()->json([
//     "message" =>  $sale->total_due
//   ], 409);

        $data["data"] =  SalePayment::create([
            "sale_id" => $request->sale_id,
            "paid_amount" =>  $request->paid_amount,
           ]);
           return response()->json($data, 201);
    }
    public function addPatientSalePaymentService($request)
    {
        $request_amount = $request->paid_amount;
        $due_sales = Sale::where([
            "patient_id" => $request->patient_id,
            "payment_status"=>"due",
            "status"=>'Confirmed'
        ])

        ->orderBy("sale_date")
        ->groupBy("id")
        ->get();

$let_total_due = 0;


        foreach($due_sales as $sale){
            $let_sub_total = $sale->saleDetails->sum('amount');
            $let_discount = $sale->discount;
            $let_line_discount =  $sale->saleDetails->sum('line_discount');
            $let_paid_amount =  $sale->payments->sum('paid_amount');

            $let_total_due += ($let_sub_total - (($let_discount?$let_discount:0) + ($let_line_discount?$let_line_discount:0))) - ($let_paid_amount?$let_paid_amount:0);

        }

        if($let_total_due < $request_amount){
            return response()->json([
              "message" =>  "greather than due bb" . $let_total_due
            ], 409);
        }








        foreach($due_sales as $due_sale){
          $total = 0;
          $paid = 0;
          foreach($due_sale->saleDetails as $saledetail){
            $total += ($saledetail->amount - $saledetail->line_discount);
          }
          $total -= $due_sale->discount;
          $paid = $due_sale->payments->sum("paid_amount");
          $due = $total - $paid;
          if(($due - $request_amount) > 0) {
            $due_sale->payments()->create([
                "paid_amount" => $request_amount
            ]);
            break;
          }
          elseif(($due - $request_amount) == 0){
            $due_sale->payments()->create([
                "paid_amount" => $request_amount
            ]);
            $due_sale->payment_status = "paid";
            $due_sale->save();
            break;
          }
          else {

            $due_sale->payments()->create([
                "paid_amount" => $due
            ]);
            $due_sale->payment_status = "paid";
            $due_sale->save();
            $request_amount -= $due;
          }


        }
        // $data["data"] =  SalePayment::create([
        //     "sale_id" => $request->sale_id,
        //     "paid_amount" =>  $request->paid_amount,
        //    ]);
        return response()->json(["ok" => true], 200);
           return response()->json($due_sales, 404);
    }



}
