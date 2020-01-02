<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLossAuction extends Model
{   
    const ACTIVE = '0';
    const EXPIRE = '1';

    //
    protected $guarded = ['id','created_at','deleted_at','updated_at'];


    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id')->where('deleted_at', NULL);
    }

    public function auction()
    {
        return $this->belongsTo('App\Models\Auction','auction_id')->where('deleted_at', NULL);
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function auction_queue()
    {
        return $this->belongsTo('App\Models\AuctionQueue','auction_queue_id');
    }
}
