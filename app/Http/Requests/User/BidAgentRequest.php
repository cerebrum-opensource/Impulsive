<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AuctionQueue;
use App\User;
use Auth;


class BidAgentRequest extends FormRequest
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
        return [
            'id' => 'required',
            'user_id' => 'required',
            'bidCount' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function($validator)
        {   
            if($this->has('id') &&  $this->get('id') != 0) {
               $ids = AuctionQueue::where([
                    'id' => encrypt_decrypt('decrypt', $this->get('id'))
                    ])->count();
                 if(!$ids){
                    $validator->errors()->add('count','Auction id not valid.');
                }
            }         


            if($this->get('user_id')) {
                $user = User::with('userBid')->find(Auth::id());
                if(count($user->userBid)){
                    $count = $user->userBid[0]->bid_count;
                    
                }
                else {
                    $count = 0;
                    $validator->errors()->add('count',trans('message.not_enough_bid'));
                }
                if($this->has('bidCount') && $this->get('bidCount')){
                    $remaningBid = $count-$this->get('bidCount');
                    if($remaningBid<0){
                         $count = 0; 
                         $validator->errors()->add('count',trans('message.bid_number_greater_than_bid_had'));
                    }
                }
            } 
            
        });
    }


}
