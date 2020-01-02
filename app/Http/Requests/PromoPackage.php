<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class PromoPackage extends FormRequest
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
        $timezone = user_time_zone();
        $todayDate =  \Carbon\Carbon::now()->timezone($timezone)->format('Y-m-d H:i');
    	$rules['name'] = "bail|required|max:100|regex:/^[\pL\s\-\. '0-9]+$/u";
        $rules['description'] = 'required';
        $rules['bid'] = 'bail|required|integer|min:1';
        $rules['bonus'] = 'bail|nullable|integer|min:1';
        $rules['price'] = 'bail|required|numeric|min:1';

        if ($this->has('package_id') && $this->get('package_id')) {    
            $rules['start_date'] = "bail|required|date_format:Y-m-d H:i|after:".$todayDate;  
        }
        else{
            $rules['image'] = 'required';
            $rules['start_date'] = "bail|required|date_format:Y-m-d H:i|after:".$todayDate;
        }

        if($this->get('start_date')) {
            $startDate =  \Carbon\Carbon::createFromFormat('Y-m-d H:i',$this->get('start_date'))->format('Y-m-d H:i');
            $rules['end_date'] = "bail|required|date_format:Y-m-d H:i|after:".$startDate;
        }
	    return $rules;
    }

    public function messages(){
    	 return [
            'name.regex' => "Only numbers, special characters - ' . and alphabets are allowed."
        ];
    }
}
