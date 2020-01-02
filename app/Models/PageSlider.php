<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSlider extends Model
{
    protected $guarded = ['id','created_at','deleted_at','updated_at'];
    use SoftDeletes;

    const HOME = '1';
    const OTHER = '2';

    const DRAFT = '0';
    const ACTIVE = '1';
}
