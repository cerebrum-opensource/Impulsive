<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAmountCategory extends Model
{
    //
    protected $guarded = [];
    const DRAFT = '0';
    const ACTIVE = '1';


    public function product_afterplay_categories()
    {
        return $this->hasMany('App\Models\ProductAfterplayCategory');
    }
}
