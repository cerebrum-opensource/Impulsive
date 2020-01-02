<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DateFormat;


class UserBidHistory extends Model
{
    //
    use DateFormat;

    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const MANUAL = '1';
    const AUTOMATE = '0';

    protected $dates_need_to_be_changed = [
        'created_at_format'
    ];

    protected $appends = ['created_at_format'];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function getCreatedAtFormatAttribute()
    {
        return $this->attributes['created_at'];
    }
}
