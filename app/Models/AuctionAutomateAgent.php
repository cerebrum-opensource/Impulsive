<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionAutomateAgent extends Model
{
    //
     protected $guarded = ['id','created_at','deleted_at','updated_at'];


    const NOT_ACTIVE = '0';
    const ACTIVE = '1';

    public function user()
    {
        return $this->belongsTo('App\User','user_id')->where('deleted_at', NULL);
    }
}
