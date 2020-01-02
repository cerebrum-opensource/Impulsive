<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;
class FaqType extends Model
{
    //
    protected $guarded = ['id','created_at','updated_at'];

    const DRAFT = '0';
    const ACTIVE = '1';


    public function faqs()
    {
        return $this->hasMany('App\Models\Faq','type')->where('status',Faq::ACTIVE);
    }
}
