<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Attribute extends FormRequest
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
        $rules['place_holder'] = "required|max:100";
        $rules['type'] = "required";
        $rules['category_id'] = "nullable|exists:categories,id,deleted_at,NULL";
        $rules['other_value.*'] = "required_if:type,==,1|max:100";
		return $rules;
		
        
    }

    public function messages(){
    	return [
    		//'other_value.required_if' => 'Please enter value',
            'other_value.*.*' => 'Please enter value.',
		];
    }
}
