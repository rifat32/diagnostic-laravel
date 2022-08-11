<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrescriptionRequest;
use App\Http\Requests\PrescriptionUpdateRequest;
use App\Http\Services\PrescriptionService;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    use PrescriptionService;
    public function createPrescription(PrescriptionRequest $request)

    {
        return $this->createPrescriptionService($request);
    }
    public function updatePrescription(PrescriptionUpdateRequest $request) {
        return $this->updatePrescriptionService($request);
    }

    public function getPrescription(Request $request)
    {
        return $this->getPrescriptionService($request);
    }

    public function searchPrescriptionByDate($from,$to,Request $request)
    {
        return $this->searchPrescriptionByDateService($from,$to,$request);
    }

    public function getSinglePrescription($id,Request $request)
    {
        return $this->getSinglePrescriptionService($id,$request);
    }



    public function addPayment(Request $request)
    {
        return $this->addPaymentService($request);
    }

    public function getPrescriptionInvoice(Request $request,$id)
    {
        return $this->getPrescriptionInvoiceService($request,$id);
    }




}
