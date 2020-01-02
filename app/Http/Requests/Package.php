<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class Package extends FormRequest
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
     
    	$rules['name'] = "bail|required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
        $rules['description'] = 'required';
        $rules['bid'] = 'bail|required|numeric|min:1';
        $rules['price'] = 'bail|required|numeric|min:1';
        if ($this->has('package_id') && $this->get('package_id')) {         
        }
        else{
            $rules['image'] = 'required';
        }


	    return $rules;
    }

    public function messages(){
    	 return [
            'name.regex' => "Only numbers, special characters - ' . and alphabets are allowed."
        ];
    }
}
