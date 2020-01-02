<?php

namespace App\Traits;

use Carbon;
use Config;

trait DateFormat
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
    	//check if it is date field then change its format
         if(isset($this->dates_need_to_be_changed) && in_array($key, $this->dates_need_to_be_changed) && !empty($value)){
            

                if(array_key_exists("client_timezone",$_COOKIE)){
                    $timezone = $_COOKIE['client_timezone'];
                }
                else {
                    $timezone = Config::get('app.timezone');
                }
                if($key == 'start_time'){
                    $value = \Carbon\Carbon::parse($value)->timezone($timezone)->format('Y-m-d, H');
                }
                elseif($key == 'created_at_format'){
                    $value = \Carbon\Carbon::parse($value)->timezone($timezone)->format('Y-m-d H:i:s');
                }
                elseif($key == 'start_date' || $key == 'end_date'){
                    $value = \Carbon\Carbon::parse($value)->timezone($timezone)->format('Y-m-d H:i');
                }
                elseif($key == 'end_time' || $key == 'final_countdown_start_time'){
                    $value = \Carbon\Carbon::parse($value)->timezone($timezone)->format('Y-m-d H:i:s');
                }
                elseif($key == 'dob'){
                    $value = \Carbon\Carbon::parse($value)->format('d.m.Y');
                }
                else {
                    $value = \Carbon\Carbon::parse($value)->timezone($timezone)->format('m-d-Y');
                }
             
         }else{
           $value = $value;
         }
        return $value;
    }
}



?>
