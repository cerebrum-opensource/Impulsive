<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use SoftDeletes;
    protected $guarded = ['id','created_at','updated_at'];

    const DRAFT = '0';
    const ACTIVE = '1';
    const LOGIN = '1';
    const OTHER = '2';
}
