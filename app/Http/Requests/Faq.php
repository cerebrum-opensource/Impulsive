<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class Faq extends FormRequest
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
       // $rules['first_name'] = "required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
        $rules['question'] = "required|max:10000";
        $rules['answer'] = "required|max:10000";
        $rules['type'] = 'required|exists:faq_types,id';
	    return $rules;
    }

    public function messages(){
    	 return [
            
        ];
    }
}
