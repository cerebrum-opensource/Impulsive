<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class Auction extends FormRequest
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
        $nowDate = \Carbon\Carbon::now()->timezone($timezone)->format('Y-m-d H:i:s');        

        $rules['product_id'] = 'required|exists:products,id,deleted_at,NULL,status,1';
        $rules['duration'] = 'nullable';
        if($this->has('live_auction') && $this->get('live_auction') == 'on'){

        }
        else {
            $rules['start_time'] = "nullable|date_format:Y-m-d H:i:s|after:".$nowDate; 
        }
	    return $rules;
    }

    public function messages(){
    	 return [
            'name.regex' => "Only numbers, special characters - ' . and alphabets are allowed."
        ];
    }
}
