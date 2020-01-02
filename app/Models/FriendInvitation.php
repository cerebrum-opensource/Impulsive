<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendInvitation extends Model
{
    //

	protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const PENDING = '0';
    const ACTIVE = '1';

}
