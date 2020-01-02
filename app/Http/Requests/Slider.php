<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class Slider extends FormRequest
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
        $rules['heading'] = "required|max:1000";
        $rules['heading_detail'] = "required|max:1000";
        $rules['button_text'] = "required|max:100";
        $rules['header_text_position'] = "required";
     //   $rules['header_detail_text_position'] = "required";
        $rules['button_link'] = ['required', 'max:1000','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/u'];
        $rules['other_text'] = "required|max:1000";
        if ($this->has('slider_id') && $this->get('slider_id')) {
            $rules['image'] = 'bail|nullable|image|mimes:jpeg,png,jpg';
            //$rules['image'] = 'bail|nullable|image|mimes:jpeg,png,jpg|min:200|max:5120|dimensions:min_width=800,min_height=500';
        }
        else {
            $rules['image'] = 'bail|required|image|mimes:jpeg,png,jpg';
          //  $rules['image'] = 'bail|required|image|mimes:jpeg,png,jpg|min:200|max:5120|dimensions:min_width=800,min_height=500';
        }
        
	    return $rules;
    }

    public function messages(){
    	 return [
            
        ];
    }
}
