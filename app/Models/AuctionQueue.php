<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DateFormat;
use App\Models\UserWishlist;
use App\Models\Setting;
use App\Models\UserLossAuction;
use App\User;
use Auth;
use Mail;
use App\Mail\SendEmailToAuctionLossers;

class AuctionQueue extends Model
{
    //
    use DateFormat;

    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const NOT_ACTIVE = '0';
    const ACTIVE = '1';
    const LIVE = '0';
    const QUEUE = '1';
    const PLANNED = '2';

    protected $dates_need_to_be_changed = [
       // 'updated_at',
        'created_at',
        'end_time',
        'final_countdown_start_time'
    ];


    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id')->where('deleted_at', NULL);
    }

    public function auction()
    {
        return $this->belongsTo('App\Models\Auction','auction_id')->where('deleted_at', NULL);
    }

    public function bidHistory()
    {
        return $this->hasMany('App\Models\UserBidHistory','auction_id')->orderBy('id', 'DESC');
    }

    public function bidHistoryLiveAuction()
    {
        return $this->hasMany('App\Models\UserBidHistory','auction_id')->distinct('user_id');
    }

    public function auctionWinner()
    {
        return $this->hasMany('App\Models\AuctionWinner','auction_queue_id')->orderBy('id', 'DESC');
    }

    public function getStatusnameAttribute()
    { 
       $status='';
        switch ($this->attributes['status']) {
        case AuctionQueue::NOT_ACTIVE:
            $status = trans('label.expired');
            break;
        case AuctionQueue::ACTIVE:
            $status = trans('label.active');
            break;
        default:
            $status = trans('label.active');
            break;
        }
        return $status;
    }

    public function getFinalTimeutcAttribute()
    { 
       return $this->attributes['final_countdown_start_time'];
    }

    public function getEndTimeutcAttribute()
    { 
       return $this->attributes['end_time'];
    }

    public function wishList()
    {   
        $userID = 0;
        if(Auth::user())
            $userID = Auth::id();
        return $this->belongsTo('App\Models\UserWishlist','auction_id','auction_id')->where('user_id', $userID)->where('type', UserWishlist::WISH_LIST);
    }


    // function to send email with link to user who lose the auction
    public function auctionLoseUser($user_id)
    {   
        $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if($setting){
            $per_bid_price_raise = $setting->per_bid_price_raise;
        }
        else {
            $per_bid_price_raise = PER_BID_PRICE_RAISE;
        }
        if($setting){
            $instant_purchase_expire_day = $setting->instant_purchase_expire_day;
        }
        else {
            $instant_purchase_expire_day = INSTANT_PURCHASE_EXPIRE_DAY;
        }
        $data = UserBidHistory::where('auction_id',$this->id)->where('user_id','!=',$user_id)->pluck('user_id')->toArray();

        $bidsPerUser = array_count_values($data);
        $currentDate = date( "Y-m-d H:i:s");
        $expireDate = date( "Y-m-d H:i:s", strtotime( "$currentDate +".$instant_purchase_expire_day." day" ) );

        if(!empty($bidsPerUser )){
                foreach ($bidsPerUser as $key => $value) {
                    $getUser = User::where('id',$key)->first();
                    $token =  strtolower(str_random(64));
                    $userId =  $key;
                    
                    if(!empty($getUser)){
                        Mail::to($getUser['email'])->send(new SendEmailToAuctionLossers($getUser['username'],$token));
                    }
                    $saveAuctionArray = array(
                        'auction_id'=>$this->auction_id,
                        'auction_queue_id'=>$this->id,
                        'product_id'=>$this->product_id,
                        'user_id'=>$userId,
                        'bid_count'=>$value,
                        'bid_price'=>(float)$value * (float)$per_bid_price_raise,
                        'link'=>$token,
                        'link_expire_time'=>$expireDate,
                        'status'=>0,
                    );
                    if(count($saveAuctionArray) > 0){
                        UserLossAuction::create($saveAuctionArray);
                    }
                }
            }

        return true;
    }
}
