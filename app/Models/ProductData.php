<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ProductData extends Model
{

	protected $guarded = ['id','created_at','deleted_at','updated_at'];

    //
    public static function insertData($data){
    	//$count = 1;
       $article_number= DB::table('product_data')->where('article_number', $data['article_number'])->get();
       if($article_number->count() == 0){
       	 ProductData::create($data);
        // DB::table('product_data')->insert($data);
      }elseif($article_number->count() > 0){

   //   	print_r($data);

      	//die();
      	ProductData::where("article_number", $data['article_number'])->update($data);
      	//die();
      }
   }

}
