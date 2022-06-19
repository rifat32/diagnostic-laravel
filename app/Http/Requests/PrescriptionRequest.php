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
            "description"=>"required",
            "note" => "required",
            "patient_id" =>"required",
            "appointment_id" =>"required",
            "next_appointment"=>"required",
            "fees"=> "required|numeric",
            "prescription"=>"array",
            
            "cc"=>"array",
            "tests"=>"array",
        ];
    }
}
