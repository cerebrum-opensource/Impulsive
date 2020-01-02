<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    const DRAFT = '0';
    const ACTIVE = '1';
    const DEACTIVE = '2';
    const PRODUCT = '0';
    const AUCTION = '1';
    const OTHER = '2';

    protected $casts = [
   		'attribute_id' => 'array'
    ];
    
}
