<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    //
    use SoftDeletes;
    protected $guarded = ['id','created_at','deleted_at','updated_at'];
    use DateFormat;

    const DRAFT = '0';
    const ACTIVE = '1';
    const DEFAULT = 'default';
    const PROMO = 'promotional';

     protected $dates_need_to_be_changed = [
        'updated_at',
        'created_at',
        'start_date',
        'end_date'
    ];


    public function scopeDefault($query)
    { 
         return $query->where('type', Package::DEFAULT);
    }    

    public function scopePromo($query)
    { 
        return $query->where('type', Package::PROMO);
    }
}
