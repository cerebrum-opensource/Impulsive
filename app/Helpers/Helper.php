<?php
// helper functions that needer througout the project


if (!function_exists('gender_array')) {
     
    function gender_array()
    {
        return array("Male"=>"Male", "Female"=>"Female","Others"=>"Others");
    }
}


if (!function_exists('encrypt_decrypt')) {
    
    function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = env('SECRET_KEY', 'wiw3g716qXYY29HUzzdOtvSfNkb7n5PN');
        $secret_iv = env('SECRET_IV', 'kIksnotLbVZ71hW4mtnL4RFSyar3l6a8');
        // hash
        $key = hash('sha256', $secret_key);     
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

}

if (!function_exists('attribute_type')) {
     
    function attribute_type()
    {
        return array("0"=>"Input", "1"=>"Dropdown","2"=>"Date","3"=>"Checkbox","4"=>"Radio");
    }
}

if (!function_exists('salutation_title')){
    function salutation_title()
    {
        return array("Herr" => trans('label.sir'), "Frau" => trans('label.woman'));
    }
}

if (!function_exists('phone_number_code')){
    function phone_number_code()
    {
        return array("+49" => "+49", "+43" => "+43");
    }
}

if (!function_exists('attribute_type_for_validation')) {
     
    function attribute_type_for_validation()
    {
        $common_array = array("0"=>"0", "1"=>"1", "2"=>"2", "3"=>"3", "4"=>"4");
        return $common_array;
    }
}


if (!function_exists('change_date_format')) {
     
    function change_date_format($date)
    {
        if($date) {
            $formatDate = explode('-',$date);
            $formatDate = $formatDate[2].'-'.$formatDate[0].'-'.$formatDate[1];
            return $formatDate;
        }

    }
}

if (!function_exists('change_date_format_new')) {
  //   mm-dd-yy
  //  'dd.mm.yy',
    function change_date_format_new($date)
    {
        if($date) {
            $formatDate = explode('.',$date);
            $formatDate = $formatDate[2].'-'.$formatDate[1].'-'.$formatDate[0];
            return $formatDate;
        }

    }
}

if (!function_exists('seconds_to_h_m_s')) {
     
    function seconds_to_h_m_s($seconds)
    {
        if($seconds) {
             $t = round($seconds);
             return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
        }

    }
}



if (!function_exists('user_time_zone')) {
     
    function user_time_zone()
    {
        if(array_key_exists("client_timezone",$_COOKIE)){
            $timezone = $_COOKIE['client_timezone'];
        }
        else {
            $timezone = Config::get('app.timezone');
        }
         
         return $timezone;
    }
}

if (!function_exists('bid_agent_start_type')) {
     
    function bid_agent_start_type()
    {
        return array(
		"1"=>trans('label.immediately'), 
		//"2"=>trans('label.4hours'),
//		"3"=>trans('label.5minutes'),
		//"4"=>trans('label.final_countdown')
);
    }
}

if (!function_exists('calculate_tax')) {
     
    function calculate_tax($amount,$tax)
    {   
        if($tax){
           $tax = ($amount*$tax)/100; 
          // $amount = $amount +(($amount*$tax)/100); 
        }
        return round($tax,2);
    }
}


if (!function_exists('klarna_tax')) {
     
    function klarna_tax($amount,$tax)
    {   
        if($tax){
           $tax = ($amount*100) - ($amount*100) * 10000 / (10000 + $tax*100);
        }
        return round($tax,2);
    }
}


if (!function_exists('faq_type')) {
     
    function faq_type()
    {
        return array("1"=> trans('label.login_question'),"2"=>trans('label.about_the_bid'));
    }
}


if (!function_exists('get_tax_amount')) {
     
    function get_tax_amount($amount,$tax)
    {   
        $tax = $tax+100;
        $amount = ($amount*100)/$tax;
        
        return round($amount,2);
    }
}


if (!function_exists('order_type')) {
     
    function order_type()
    {   
        return array("1"=> trans('label.winner'),"2"=>trans('label.instant_purchase'));
    }
}


if (!function_exists('page_type')) {
     
    function page_type()
    {
        return array("0"=>"Home Page", "1"=>"Running Auction","2"=>"Comming Auction","3"=>"Auction Overview","4"=>"Win List","5"=>"Recall List","6"=>"Wish List","7"=>"DashBoard");
    }
}

if (!function_exists('page_type_for_validation')) {
     
    function page_type_for_validation()
    {
        $common_array = array("0"=>"0", "1"=>"1", "2"=>"2", "3"=>"3", "4"=>"4","5"=>"5", "6"=>"6", "7"=>"7");
        return $common_array;
    }
}

if (!function_exists('payment_method_type')) {
     
    function payment_method_type()
    {   
        return array("0"=> trans('label.paypal'), "1"=> trans('label.klarna'),"2"=>trans('label.sofort'));
    }
}


if (!function_exists('slider_text_position')) {
     
    function slider_text_position()
    {
        return array("slider_text_left"=>"Left", "slider_text_right"=>"Right","slider_text_center"=>"Center");

        // return array("slider_text_left"=>"Left", "slider_text_right"=>"Right","slider_text_center"=>"Center","slider_text_center_left"=>"Center-Left","slider_text_center_right"=>"Center-Right","slider_text_left_top"=>"Left-Top","slider_text_left_bottom"=>"Left-Bottom","slider_text_right_top"=>"Right-Top","slider_text_right_bottom"=>"Right-Bottom");
    }   
}

if(!function_exists('price_reflect_format')){
    function price_reflect_format($price){
         $reflect_price = $price;
        // $reflect_price = number_format($reflect_price, 2, ',', ',');
        // return $reflect_price;
      //  $reflect_price = $this->attributes['product_price'];
        $reflect_price = number_format($reflect_price, 2, ',','');
        return $reflect_price;

    }
}


if (!function_exists('h_m_s_to_seconds')) {
     
    function h_m_s_to_seconds($hms)
    {
        if($hms) {
            $str_time = $hms;

            $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

            sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

            $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
            return $time_seconds;
        }

    }
}



if (!function_exists('auction_time_helper')) {
     
    function auction_time_helper($startTime,$endTime)
    {
        $nowDateGermany = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s'); 
        if (($nowDateGermany >= $startTime) && ($nowDateGermany <= $endTime)){
            return false;
        }else{
            return true;
        }

    }
}
