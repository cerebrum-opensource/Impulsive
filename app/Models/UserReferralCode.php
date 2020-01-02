<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReferralCode extends Model
{
    //

    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const REFERRAL = '0';
    const REGISTRATION = '1';

    const NOT_USED = '0';
    const USED = '1';

}
