<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{

    use SoftDeletes;

    protected $guarded = [];

    const DRAFT = '0';
    const ACTIVE = '1';
    const DEACTIVE = '2';

    protected $casts = [
        'other_value' => 'array'
    ];
    
    public function category_name()
    {
        return $this->hasOne('App\Models\Category','id', 'category_id');
    }
    
    
    public function getTypenameAttribute()
    { 
       $status='';
        switch ($this->attributes['type']) {
        case INPUT:
            $status = 'Input';
            break;
        case DATE:
            $status = 'Date';
            break;
        case DROPDOWN:
            $status = 'Dropdown';
            break;
        case CHECKBOX:
            $status = 'Checkbox';
            break;
        case RADIO:
            $status = 'Radio';
            break;
        default:
            $status = 'Input';
            break;
        }
        return $status;
    }

}
