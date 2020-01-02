<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class SupportWidget extends FormRequest
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
        $rules['header2'] = "nullable|max:200";
       // $rules['image'] = "required";
        if ($this->has('widget_id') && $this->get('widget_id')) {
            $rules['logo'] = 'bail|nullable|image|mimes:jpeg,png,jpg|max:5120|dimensions:min_width=10,min_height=10';
        }
        else {
            $rules['logo'] = 'bail|required|image|mimes:jpeg,png,jpg|max:5120|dimensions:min_width=10,min_height=10';
        }
     //   $rules['link_url'] = ['required', 'max:1000','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/u'];
        
	    return $rules;
    }

    public function messages(){
    	 return [
            
        ];
    }
}
