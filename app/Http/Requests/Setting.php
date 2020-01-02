<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Setting extends FormRequest
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
        $rules['free_bid_amount'] = "required|numeric|min:1|max:100";
        $rules['invite_bid_amount'] = "required|numeric|min:1|max:100";
        $rules['maximum_live_auction'] = "required|numeric|min:1|max:100";
        $rules['per_bid_price_raise'] = "required|numeric|min:0.01|max:100";
        $rules['per_bid_count_raise'] = "required|numeric|min:1|max:100";
      //  $rules['per_bid_count_descrease'] = "required|min:1|max:100";
        $rules['discount_per_bid_price'] = "required|numeric|min:0.1|max:1";
        $rules['bonus_bid_day_count'] = "required|numeric|min:1|max:30";
        $rules['instant_purchase_expire_day'] = "required|numeric|min:1|max:30";
        
        $startTime = $this->get('break_start_time');

        $rules['break_start_time'] = "required|date_format:H:i:s";
        $rules['break_end_time'] = "required|date_format:H:i:s|after:".$startTime;
    
        return $rules;
    }
}
