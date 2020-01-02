<?php

namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;

use App\Models\UserReferralCode;

class ReferralCode extends FormRequest
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
       // 'affiliate_id' => 'exists:users,id,deleted_at,NULL,user_status,1,user_type,3',
    	$rules['user_id'] = "required|exists:users,id,deleted_at,NULL";
        $rules['voucher_code'] = "bail|required|exists:user_referral_codes,referral_code,user_id,NULL,status,0";
	    return $rules;
    }

    public function messages(){
    	 return [
            'name.regex' => "Only numbers, special characters - ' . and alphabets are allowed."
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function($validator)
        {
            if($this->get('user_id') && $this->get('voucher_code')) {
                
                $da = [];
                $recordDa = UserReferralCode::where('user_id',$this->get('user_id'))->get();
                foreach ($recordDa as $key => $value) {
                    # code...
                    $da[] =  $value->type;
                    
                }
              //  if($record){
                    $recordRe = UserReferralCode::where('referral_code',$this->get('voucher_code'))->first();

                    if(in_array($recordRe->type, $da)){
                        $validator->errors()->add('voucher_code',trans('message.coupon_already_used_error'));
                    }

               // }

            }
        });
    }
}
