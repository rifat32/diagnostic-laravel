<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Http\Requests\SaleUpdateRequest;
use App\Http\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    use SaleService;
    public function createSales(SaleRequest $request)
    {
        return $this->createSalesService($request);
    }
    public function updateSales(SaleUpdateRequest $request)
    {

        return $this->updateSalesService($request);
    }
    public function deleteSales(Request $request)
    {
        return $this->deleteSalesService($request);
    }

    public function getSales(Request $request)
    {

        return $this->getSalesService($request);
    }
    public function getSalesByPatient(Request $request,$id)
    {

        return $this->getSalesByPatientService($request,$id);
    }


    public function searchSalesByDate($from,$to,Request $request)
    {
        return $this->searchSalesByDateService($from,$to,$request);
    }

    public function getSalesById($id,Request $request)
    {

        return $this->getSalesByIdService($id,$request);
    }

    public function addSalePayment(Request $request)
    {

        return $this->addSalePaymentService($request);
    }
    public function addPatientSalePayment(Request $request)
    {

        return $this->addPatientSalePaymentService($request);
    }


}
