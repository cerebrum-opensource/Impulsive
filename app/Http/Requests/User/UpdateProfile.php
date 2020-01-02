<?php

namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
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
        $rules['country'] = 'required';
        $rules['house_number'] = 'required';
        //'dob' => ['required', 'date_format:d.m.Y','before:'.date("d.m.Y",strtotime("-10 year +1 days")),'after:'.date("d.m.Y",strtotime("-100 year"))],
        $rules['dob'] = 'required|date_format:d.m.Y|before:'.date("d.m.Y",strtotime("-18 year +1 days")).'|after:'.date("d.m.Y",strtotime("-100 year")).'';
       // $rules['additional_address'] = 'required';
         if ($this->has('user_id') && $this->get('user_id')) {
             $id = encrypt_decrypt('decrypt', $this->get('user_id'));
             $rules['email'] = 'required|max:45|email|unique:users,email,'.$id.',id';
            // $rules['phone'] = 'required|regex:/[0-9]{10}/|unique:users,phone,'.$id.',id';
             $rules['phone'] = 'required|min:4|max:20';

         //   $rules['phone'] = 'required|unique:users,phone,'.$this->get('user_id').',id';
        }
        else{
            $rules['email'] = 'required|max:100|email|unique:users,email';
          //  $rules['phone'] = 'required|unique:users,phone';
            $rules['phone'] = 'required|min:4|max:20';
        }
	    return $rules;
    }

    public function messages(){
    	 return [
            'name.regex' => "Only numbers, special characters - ' . and alphabets are allowed."
        ];
    }
}
