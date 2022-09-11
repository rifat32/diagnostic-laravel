<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "description"=>"nullable",
            "past_medical_history" => "nullable",
            "drug_history" => 'nullable',
            "patient_id" =>"required",
            "appointment_id" =>"required",
            "next_appointment"=>"nullable",
            "fees"=> "numeric|nullable",
            "prescription"=>"array",

            "cc"=>"array",
            "oe"=>"array",
            "tests"=>"array",
            "medical_history"=>"nullable|string"
        ];
    }
}
