<?php

namespace Ignite\Reception\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckinPatientRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            "destination" => "required",
            "purpose" => "required",
            "payment_mode" => 'required',
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
