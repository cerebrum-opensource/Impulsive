<?php

namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;

class ContactUs extends FormRequest
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
    	$rules = [];
        $rules['name'] = "required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
        $rules['email'] = "required|max:100|email";
        $rules['username'] = "nullable";
        $rules['subject'] = "required";
        $rules['is_checked'] = "required";
        $rules['message'] = "nullable|max:10000";
	    return $rules;
    }

    public function messages(){
    	 return [
            
        ];
    }
}
