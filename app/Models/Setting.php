<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Setting extends Model
{
    //

    protected $guarded = ['id','created_at','updated_at'];



    public function getBreakTimeLabelAttribute()
    {
        return $this->break_start_time;
    }
    
}
