<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{

    use SoftDeletes;
    protected $attributes = [
        'tax' => self::TAX
    ];

    protected $guarded = [];

    const DRAFT = '0';
    const ACTIVE = '1';
    const DEACTIVE = '2';
    const TAX = TAX_PERCENT;
    

    public function product_attributes()
    {
        return $this->hasMany('App\Models\ProductAttribute');
    }

     public function product_images()
    {
        return $this->hasMany('App\Models\ProductImage');
    }
    public function product_afterplay_categories()
    {
        return $this->hasMany('App\Models\ProductAfterplayCategory','product_id','id');
    }
    public function product_amount_categories()
    {
        return $this->belongsToMany('App\Models\ProductAmountCategory', 'product_afterplay_categories', 'product_id', 'afterplay_category_id');
    }
    public function category_name()
    {
        return $this->hasOne('App\Models\Category','id', 'category_id');
    }

    public function seller()
    {
        return $this->hasOne('App\User','id', 'seller_id');
    }

    public function getProductStatusBadgeAttribute()
    { 
       $status='';
        switch ($this->attributes['status']) {
        case Product::DRAFT:
            $status = '<span class="ps-setting">Draft</span>';
            break;
        case Product::ACTIVE:
            $status = '<span class="pd-setting">Active</span>';
            break;
        case Product::DEACTIVE:
            $status = '<span class="ds-setting">Deactive</span>';
            break;
        default:
            $status = '<span class="pd-setting">Active</span>';
            break;
        }
        return $status;
    } 

    public function getProductPriceLabelAttribute()
    {   
        // $reflect_price = $this->attributes['product_price'];
        // $reflect_price = number_format($reflect_price, 2, ',','');
        // return $reflect_price;

    } 
}
