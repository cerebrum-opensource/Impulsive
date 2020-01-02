<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeoWidget extends FormRequest
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
        $rules['header1'] = "required|max:200";
        $rules['header2'] = "required|max:200";
      //  $rules['page_type'] = "required|unique:home_page_widgets,page_type,null,id";
        //$rules['page_type'] = 'required|Rule::in(page_type_for_validation())|unique:home_page_widgets,page_type,'.$this->id.',id';
        $rules['paragraph_1'] = "required|max:10000";
        $rules['paragraph_2'] = "required|max:10000";
       
        $rules['page_type'] = ['required', 'unique:home_page_widgets,page_type,'.$this->id.',id',Rule::in(page_type_for_validation())];
        
	    return $rules;
    }

    public function messages(){
    	 return [
            
        ];
    }
}
