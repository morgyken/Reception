<?php

namespace Ignite\Reception\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePatientRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected $rules = [];

    public function rules() {
        $this->rules = [
            //basics
            "first_name" => "required",
            "last_name" => "required",
           //"dob" => "required|date",
            "id_number" => "numeric|digits_between:7,8",
            "sex" => "required",
            //contacts
            "mobile" => "required",
            "email" => "email",
            "post_code" => "numeric",
            "town" => "required_with:address",
            "insured" => "required",
            "image" => "image"
        ];
        if ($this->has('first_name_nok')) {
            $this->rules["first_name_nok"] = "required";
            $this->rules["last_name_nok"] = "required";
            $this->rules["nok_relationship"] = "required";
        }
        return $this->rules;
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
