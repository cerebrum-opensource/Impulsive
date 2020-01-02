<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class Widget extends FormRequest
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
        $rules['header3'] = "max:200";
        $rules['header4'] = ['nullable','max:1000','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/u'];
     //   $rules['link_url'] = ['required', 'max:1000','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/u'];
        
	    return $rules;
    }

    public function messages(){
    	 return [
            
        ];
    }
}
