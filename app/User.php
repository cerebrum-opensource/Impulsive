<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Traits\DateFormat;
use App\Models\UserBidHistory;
use App\Models\UserBid;
use App\Models\UserPlan;
use App\Models\UserBonusBid;
use App\Models\AuctionWinner;
use App\Models\AuctionQueue;
use App\Models\AuctionAutomateAgent;
use App\Models\UserBuyList;
use App\Models\Setting;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles, Notifiable, DateFormat;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    protected $appends = ['full_name','full_address'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates_need_to_be_changed = [
        'dob',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    const PENDING = '0';
    const ACTIVE = '1';
    const NOT_ACTIVE = '2';
    const DELETED = '3';



    public function getFullAddressAttribute()
    {   
        $html = '';
        $html .= "<p class='tr_span'><span >$this->street";
        if($this->house_number)
            $html.=", $this->house_number </span> ";
        else
            $html.="</span> ";

         if($this->postal_code)
            $html.="<span> $this->postal_code, </span> ";

 
        $html .= "<span> $this->country   </span></p>";
        

        return $html;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new Notifications\ResendEmail);
    }

    public function sendNewEmailVerificationNotification()
    {  
        $this->notify(new Notifications\NewEmailVerification);
    }

    /*public function primaryEmail()
    {
        return $this->hasOne('App\Models\UserEmail','id','user_id');
    }*/

  public function primaryEmail()
    {
        return $this->hasMany('App\Models\UserEmail')->where('deleted_at', NULL);
    }

    public function userBid()
    {
        return $this->hasMany('App\Models\UserBid');
    }

    public function userPlan()
    {
        return $this->hasMany('App\Models\UserPlan')->where('status', 1);
    }

    public function getUserActivity()
    {   
       /* $activity = UserBidHistory::where('user_id',$this->id)->get();
        $todayDate =  \Carbon\Carbon::now()->format('Y-m-d');
        $todayActivity = UserBidHistory::where('user_id',$this->id)->where('bid_date',$todayDate)->get();
        $counts = $activity->groupBy('auction_id')->map(function ($bid) {
                return $bid->count();
        });

        $todayActivityCount = $todayActivity->groupBy('bid_date')->map(function ($bid) {
                return $bid->count();
        });


        $data['total_auctions'] = count($counts);
        $data['today_bid'] = (count($todayActivityCount) && $todayActivityCount[$todayDate]) ? $todayActivityCount[$todayDate] : 0;*/
        $bonus_bid = UserBonusBid::where('user_id',$this->id)->where('status',UserBonusBid::ACTIVE)->sum('bid_count');
   
        $free_bid = UserPlan::where('user_id',$this->id)->where('status',UserBonusBid::ACTIVE)
                        ->where(function($q){ 
                        $q->orWhere('type',UserPlan::FREE_BID)
                        ->orWhere('type',UserPlan::INVITE_BID);
                    })->sum('bid_count');
        //->where('type',UserPlan::FREE_BID)->Orwhere('type',UserPlan::INVITE_BID)->sum('bid_count');
        $purchased_bid = UserPlan::where('user_id',$this->id)->where('type',UserPlan::PLAN)->where('status',UserBonusBid::ACTIVE)->sum('bid_count');
        $bid_count = UserBid::where('user_id',$this->id)->first();
        $data['bid_count'] =  $bid_count ? $bid_count['bid_count']:"0";
        $data['purchased_bid'] = $purchased_bid;
        $data['free_bid'] = $bonus_bid+$free_bid;
        $data['total_bid'] = $bonus_bid+$free_bid+$purchased_bid;

        //********************changes for the reflection of prices in topbar

        $freeBidPendig = $bid_count['free_bid'];
        // dd($freeBidPendig);
        $data['purchased_user_bid'] = $bid_count['bid_count'] - $bid_count['free_bid'];
        $data['free_pending_bid'] = $freeBidPendig;
        $data['reflect_bid'] = $bid_count['bid_count'];
        // if($freeBidPendig <= 0){
        //     $data['purchased_bid'] = $bid_count['bid_count'];
        //     $data['free_pending_bid'] = 0;
        // }
        return $data;
    }

    public function getLossBids($instant_purchase_expire_day)
    {   

      /*  $setting = Setting::first();
        if($setting){
            $instant_purchase_expire_day = $setting->instant_purchase_expire_day;
        }
        else {
            $instant_purchase_expire_day = INSTANT_PURCHASE_EXPIRE_DAY;
        }*/
        $todayDatePast =  \Carbon\Carbon::now()->subDays($instant_purchase_expire_day)->format('Y-m-d H:i:s');



        $winnerAuctions = AuctionWinner::where('user_id',$this->id)->groupBy('auction_queue_id')->pluck('auction_queue_id');
        $ids = UserBidHistory::where('user_id',$this->id)->whereNotIn('auction_id', $winnerAuctions)->groupBy('auction_id')->pluck('auction_id');
        $auctions = AuctionQueue::whereIn('id', $ids)->where('status',AuctionQueue::NOT_ACTIVE)->where('end_time','>', $todayDatePast)->get();

        $activity = UserBidHistory::where('user_id',$this->id)->whereNotIn('auction_id', $winnerAuctions)->get();
        $counts = $activity->groupBy('auction_id')->map(function ($bid) {
                return $bid->count();
        });
        $data['auction_count'] =  $counts->toArray();
        $data['html'] =  $auctions;
        //$auctions = AuctionQueue::whereIn('id', $ids)->where('status',AuctionQueue::NOT_ACTIVE)->paginate(1);
        return $data;
    }

    public static function getUserLossBidCount($user_id,$auctionId)
    {   
        $count = UserBidHistory::where('user_id',$user_id)->where('auction_id', $auctionId)->count();
        return $count;
    }

    public function getUserDetail()
    {   
        $data['userBid'] = $this->userBid;
        $data['userPlan'] = UserPlan::where('user_id',$this->id)->where('status', UserPlan::ACTIVE)->orderBy('created_at', 'desc')->paginate(PAGINATION_COUNT_10);
        $data['bidAgentCount'] = AuctionAutomateAgent::where('user_id',$this->id)->count();
        $data['winAuctionCount'] = AuctionWinner::where('user_id',$this->id)->count();

        $winnerAuctions = AuctionWinner::where('user_id',$this->id)->groupBy('auction_queue_id')->pluck('auction_queue_id');
        $data['lossAuctionCount'] = UserBidHistory::where('user_id',$this->id)->whereNotIn('auction_id', $winnerAuctions)->groupBy('auction_id')->count();
        $data['total_bid_placed'] = UserBidHistory::where('user_id',$this->id)->count();
        return $data;
    }

     public function getUserBuyList()
    {   
  
        $data = UserBuyList::with('product')->where('user_id',$this->id)->where('type', UserBuyList::WINNER)->where('status', UserBuyList::ACTIVE)->orderBy('created_at', 'desc')->paginate(PAGINATION_COUNT_10);
        return $data;
    }

    public function getStatusnameAttribute()
    { 

        $status='';
        switch ($this->attributes['status']) {
        case User::PENDING:
            $status = trans('label.email_pending');
            break;
        case User::ACTIVE:
            $status = trans('label.active');
            break;
        case User::NOT_ACTIVE:
            $status = trans('label.de_active');
            break;
        case User::DELETED:
            $status = trans('label.deleted');
            break;
        default:
            $status = trans('label.email_pending');
            break;
        }
        return $status;
    }
}
