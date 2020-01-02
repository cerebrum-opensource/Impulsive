<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class Seller extends FormRequest
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
     /*  regex:/^[\pL\s\-]+$/u */
     /*  regex:/^[a-zA-Z0-9-_]+$/u */

        if ($this->has('user_id') && $this->get('user_id')) {
           // $rules['email'] = 'required|max:45|email|unique:users,email,'.$this->get('user_id').',id';
            $rules['phone'] = 'required|unique:users,phone,'.$this->get('user_id').',id';
        }
        else{
            $rules['email'] = 'required|max:45|email|unique:users,email';
            $rules['phone'] = 'required|unique:users,phone';
        }
    	$rules['first_name'] = "required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
        $rules['last_name'] = "required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
		
      //  $rules['roles'] = 'required|exists:roles,id';
        
        $rules['city'] = 'required';
        $rules['state'] = 'required';
        $rules['street'] = 'required';
        $rules['company'] = 'required';
        $rules['additional_address'] = 'required';
	    return $rules;
    }

    public function messages(){
    	 return [
            'name.regex' => "Only numbers, special characters - ' . and alphabets are allowed."
        ];
    }
}
