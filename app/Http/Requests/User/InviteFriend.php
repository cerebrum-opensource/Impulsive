<?php

namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;

class InviteFriend extends FormRequest
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
    	$rules['email'] = "required|email";
        $rules['name'] = "required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
        $rules['invite_text'] = 'required';
	    return $rules;
    }

    public function messages(){
    	 return [
            'name.regex' => "Only numbers, special characters - ' . and alphabets are allowed."
        ];
    }
}
