<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const DRAFT = '0';
    const ACTIVE = '1';
}
