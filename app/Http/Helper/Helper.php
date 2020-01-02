<?php

namespace App\Http\Helper;

use Carbon\Carbon;
use Auth;
use App\Users;

class Helper {

    public function __construct() {
        //$this->middleware('auth');
    }

    
    public static function getUser($id = "") {
        $user = "";
        if ($id == "") {
            $user = Auth::user();
        }else{
            $user = Users::find($id);
        }
        return $user;
        //{{App\Http\Helper\Helper::getUser()}}
    }


}
