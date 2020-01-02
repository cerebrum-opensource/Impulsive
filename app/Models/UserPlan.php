<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const DRAFT = '0';
    const ACTIVE = '1';
    const FAILED = '2';
    const FREE_BID = 'free_bid';
    const INVITE_BID = 'invite_bid';
    const PLAN = 'plan';
    const AUCTION = 'auction';


    public function package()
    {
        return $this->belongsTo('App\Models\Package','package_id')->where('deleted_at', NULL);
    }

    public function getTypeAttribute($value)
    {
        $status='';
        switch ($value) {
        case UserPlan::FREE_BID:
            $status = trans('label.free_bid');
            break;
        case UserPlan::INVITE_BID:
            $status = trans('label.invite_bid');
            break;
        case UserPlan::PLAN:
            $status = trans('label.plan');
            break;
        default:
            $status = trans('label.free_bid');
            break;
        }
        return $status;
    }
}
