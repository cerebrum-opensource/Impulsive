<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageWidget extends Model
{
    //
    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    protected $appends = ['page_type_name'];

    const DRAFT = '0';
    const ACTIVE = '1';
    const TYPE_SLIDER = 0;
    const TYPE_NEWS_LETTER = 1;
    const TYPE_SUPPORT = 2;
    const TYPE_OTHERS = 3;
    
    const HOMEPAGE = 0;
    const RUNNING_AUCTION = 1;
    const COMMING_AUCTION = 2;
    const AUCTION_OVERVIEW = 3;
    const WIN_LIST = 4;
    const RECALL_LIST = 5;
    const WISH_LIST = 6;
    const DASH_BOARD = 7;


     public function getPageTypeNameAttribute()
    { 
       $status='';
       $type = $this->attributes['page_type']; 
       $page_type_array = page_type();
       $status = @$page_type_array[$type];
       return $status;
    }
}
