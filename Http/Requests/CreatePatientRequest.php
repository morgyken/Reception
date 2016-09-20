<?php

namespace Ignite\Reception\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePatientRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            //basics
            "first_name" => "required",
            "midle_name" => "",
            "last_name" => "required",
            "dob" => "required",
            "id_no" => "numeric",
            "sex" => "required",
            //contacts
            "telephone" => "",
            "mobile" => "required",
            "email" => "email",
            "alt_number" => "",
            "address" => "",
            "post_code" => "numeric",
            "town" => "required_with:address",
            /* "first_name_nok" => "required_with_all:last_name_nok,mobile_nok,nok_relationship",
              "middle_name_nok" => "",
              "last_name_nok" => "",
              "mobile_nok" => "",
              "nok_relationship" => "", */
            "insured" => "required",
            "image" => "image"
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

}
