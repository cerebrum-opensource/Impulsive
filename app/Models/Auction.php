<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\SoftDeletes;


class Auction extends Model
{
    //
    use DateFormat;
    use SoftDeletes;
    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    protected $attributes = [
        'final_countdown' => self::FINAL_COUNTDOWN
    ];

    protected $dates_need_to_be_changed = [
        'updated_at',
        'created_at',
        'start_time',
        'start_date'
    ];

    protected $appends = ['start_date'];

    const NOT_ACTIVE = '0';
    const ACTIVE = '1';
    const CURRENTLY_RUNNING = '2';
    const ENDED = '3';
    const LIVE = '0';
    const QUEUE = '1';
    const PLANNED = '2';
    const LIVE_AUCTION_YES = '1';
    const LIVE_AUCTION_NO = '0';
    const FINAL_COUNTDOWN = '15'; 

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id')->where('deleted_at', NULL);
    }

    public function getStartDateAttribute()
    {
        return $this->attributes['start_time'];
    }

    public function auctionQueue()
    {
        return $this->hasMany('App\Models\AuctionQueue','auction_id')->where('deleted_at', NULL);
    }


    public function getStatusBadgeAttribute()
    {

        $status='';
        switch ($this->attributes['status']) {
        case Auction::NOT_ACTIVE :
            $status = '<span class="badge badge-warning align_right"></span>';
            break;
        case Auction::ACTIVE:
            $status = '<span class="badge badge-info align_right">'.trans('label.not_running').'</span>';
            break;
        case Auction::CURRENTLY_RUNNING:
            $status = '<span class="badge badge-info align_right">'.trans('label.running').'</span>';
            break;
        case Auction::ENDED:
            $status = '<span class="badge badge-info align_right ">'.trans('label.expired').'</span>';
            break;
        default:
            $status = '<span class="badge badge-info align_right"></span>';
            break;
        }
        return $status;
    }

    public function getStatusNameAttribute()
    {

        $status='';
        switch ($this->attributes['status']) {
        case Auction::NOT_ACTIVE :
            $status = '';
            break;
        case Auction::ACTIVE:
            $status = trans('label.not_running');
            break;
        case Auction::CURRENTLY_RUNNING:
            $status = trans('label.running');
            break;
        case Auction::ENDED:
            $status = trans('label.expired');
            break;
        default:
            $status = '';
            break;
        }
        return $status;
    }

    public function wishListUser()
    {
        return $this->hasMany('App\Models\UserWishlist','auction_id')->where('send_email',0)->with('user');
    }
}
