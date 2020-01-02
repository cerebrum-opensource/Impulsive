<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CSVFileUpdateTime extends Model
{
    //
    protected $guarded = ['id','created_at','updated_at'];

    public static function insertData($data){
    	$update_record = DB::table('csv_file_update_times')->orderBy('id', 'DESC')->first();

    	if($update_record == null){
    		DB::table('csv_file_update_times')->insert($data);
    	}
    	 elseif($update_record->file_size != $data){
    	 	DB::table('csv_file_update_times')->insert($data);
    	 }
    }

    public static function fetchData(){
    	$update_record = DB::table('csv_file_update_times')->orderBy('id', 'DESC')->first();
    	return $update_record;
    }
}
