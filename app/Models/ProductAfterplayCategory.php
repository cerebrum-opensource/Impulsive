<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ProductAmountCategory;

class ProductAfterplayCategory extends Model
{
    //
    protected $guarded = [];
    const DRAFT = '0';
    const ACTIVE = '1';

    public function product()
    {
        return $this->hasMany('App\Models\Product','id','product_id');
    }
    public function product_amount_category()
    {
        return $this->belongsTo('App\Models\Product','afterplay_id')->where('deleted_at', NULL);
    }
}
