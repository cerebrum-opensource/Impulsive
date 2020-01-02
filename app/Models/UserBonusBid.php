<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class UserBonusBid extends Model
{
    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const DRAFT = '0';
    const ACTIVE = '1';
    const FAILED = '2';
    const EXPIRE = '3';


    public function updateBonusBid($userId,$per_bid_count_descrease)
    {   	
    	$nowData = \Carbon\Carbon::now()->format('Y-m-d');
    	$bid = UserBonusBid::where('user_id',$userId)->where('expire_date','>=',$nowData)->where('status',UserBonusBid::ACTIVE)->where('bid_count','>',0)->orderBy('expire_date','ASC')->first();
    	if($bid){
    		if($bid->bid_count == 1){
    			UserBonusBid::find($bid->id)->update(['status' =>UserBonusBid::EXPIRE]);
    		}
    		UserBonusBid::find($bid->id)->decrement('bid_count', $per_bid_count_descrease);
            UserBid::where('user_id',$userId)->decrement('free_bid', $per_bid_count_descrease);
    	}else{
            $free_bid = UserBid::where('user_id',$userId)->first();
            if($free_bid && $free_bid->free_bid){
                UserBid::where('user_id',$userId)->decrement('free_bid', $per_bid_count_descrease);
            }

        }
    	return true;
    }

    public function updateBonusBidOnAgent($userId,$count)
    {   	
    	$nowData = \Carbon\Carbon::now()->format('Y-m-d');
    	$bonusBids = UserBonusBid::where('user_id',$userId)->where('expire_date','>=',$nowData)->where('status',UserBonusBid::ACTIVE)->where('bid_count','>',0)->orderBy('expire_date','ASC')->get();
        Log::info("Checking bids********");
    	Log::info($bonusBids);
    	foreach($bonusBids as $bonusBid){
    		if($count > $bonusBid->bid_count){
                Log::info($bonusBid->bid_count);
    			UserBonusBid::find($bonusBid->id)->decrement('bid_count', $bonusBid->bid_count);
    			UserBonusBid::find($bonusBid->id)->update(['status' =>UserBonusBid::EXPIRE]);
    			$count = $count - $bonusBid->bid_count;
                $userBidData = UserBid::where('user_id',$userId)->first();
                if($userBidData->free_bid < $bonusBid->bid_count){
                    UserBid::where('user_id',$userId)->update(['free_bid' => 0]);
                }
                else {
                    UserBid::where('user_id',$userId)->decrement('free_bid', $bonusBid->bid_count);
                }
                
                UserBid::where('user_id',$userId)->decrement('bid_count', $bonusBid->bid_count);
                Log::info();
               // UserBonusBid::find($bonusBid->id)->decrement('bid_count', $count);
    		}
    		else {
    			UserBonusBid::find($bonusBid->id)->decrement('bid_count', $count);
              //  UserBid::where('user_id',$userId)->decrement('free_bid', $count);
                
                $userBidData = UserBid::where('user_id',$userId)->first();
                if($userBidData->free_bid < $count){
                    UserBid::where('user_id',$userId)->update(['free_bid' => 0]);
                }
                else {
                    UserBid::where('user_id',$userId)->decrement('free_bid', $count);
                }
                UserBid::where('user_id',$userId)->decrement('bid_count', $count);
    			$count = 0;
    		}
    		
    	}
    	/*if($bid){
    		if($bid->bid_count == 1){
    			UserBonusBid::find($bid->id)->update(['status' =>UserBonusBid::EXPIRE]);
    		}
    		UserBonusBid::find($bid->id)->decrement('bid_count', PER_BID_COUNT_DECREASE);
    	}*/
    	return $count;
    }
}
