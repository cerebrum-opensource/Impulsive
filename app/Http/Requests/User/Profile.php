<?php

namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;

class Profile extends FormRequest
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
    	$rules['salutation'] = "required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
        $rules['first_name'] = "required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
        $rules['last_name'] = "required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
        $rules['city'] = 'required';
        $rules['state'] = 'required';
        $rules['street'] = 'required';
        $rules['postal_code'] = 'required';
        $rules['phone'] = 'required|regex:/[0-9]{10}/';
        $rules['country'] = 'required';
        $rules['house_number'] = 'required';
        //$rules['additional_address'] = 'required';
	    return $rules;
    }

    public function messages(){
    	 return [
            'name.regex' => "Only numbers, special characters - ' . and alphabets are allowed."
        ];
    }
}
