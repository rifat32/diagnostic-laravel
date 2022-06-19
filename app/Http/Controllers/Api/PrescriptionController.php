<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrescriptionRequest;
use App\Http\Services\PrescriptionService;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    use PrescriptionService;
    public function createPrescription(PrescriptionRequest $request)
    {
        return $this->createPrescriptionService($request);
    }

    public function getPrescription(Request $request)
    {

        return $this->getPrescriptionService($request);
    }
    public function getSinglePrescription($id,Request $request)
    {
        return $this->getSinglePrescriptionService($id,$request);
    }

}
