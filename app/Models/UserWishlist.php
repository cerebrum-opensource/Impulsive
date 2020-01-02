<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWishlist extends Model
{
    //

    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const WISH_LIST = '1';
    const RECALL = '0';

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function auction()
    {
        return $this->belongsTo('App\Models\Auction','auction_id')->where('deleted_at', NULL);
    }

    public function active_auction()
    {
        return $this->belongsTo('App\Models\Auction','auction_id')->where('deleted_at', NULL)->where('status', 1);
    }
}
