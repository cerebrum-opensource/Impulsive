<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Category extends FormRequest
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
    	$rules['name'] = "required|max:100";
        $rules['attribute_id.*'] = "required";
       // $rules['name'] = "required|max:100|regex:/^[\pL\s\-\. ']+$/u";

		return $rules;
		
        
    }

    public function messages(){
    	return [
            //'other_value.required_if' => 'Please enter value',
            'attribute_id.*.*' => 'Please select one attribute.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function($validator)
        {
            if($this->get('attribute_id')) {

            }
            else {
                $validator->errors()->add('attribute_id','Please add a attibute to category.');
            }
        });
    }
}
