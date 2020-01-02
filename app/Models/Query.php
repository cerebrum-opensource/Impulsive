<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const RECIEVE = '0';
    const SEEN = '1';
    const RESPONSE = '2';
}
