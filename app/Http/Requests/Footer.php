<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class Footer extends FormRequest
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
       // $rules['address'] = "required|max:1000";
        $rules['timing'] = "required|max:500";
        $rules['email'] = "required|email|max:200";
        $rules['fb_link'] = ['nullable', 'max:1000','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/u'];
        $rules['insta_link'] = ['nullable', 'max:1000','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/u'];
        $rules['twitter_link'] = ['nullable', 'max:1000','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/u'];
        $rules['linkedin_link'] = ['nullable', 'max:1000','regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/u'];
        if ($this->has('footer_id') && $this->get('footer_id')) {
            $rules['logo'] = 'bail|nullable|image|mimes:jpeg,png,jpg|min:5|max:5120|dimensions:min_width=10,min_height=10';
        }
        else {
            $rules['logo'] = 'bail|required|image|mimes:jpeg,png,jpg|min:5|max:5120|dimensions:min_width=10,min_height=10';
        }
        
	    return $rules;
    }

    public function messages(){
    	 return [
            
        ];
    }
}
