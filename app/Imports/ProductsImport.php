<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use AUTH;

class ProductsImport implements ToModel, WithValidation, WithHeadingRow
{
     use Importable;

    public function model(array $row)
    {   
       
       

        return new Product([
            'product_title' => $row['product_title'],
            'products_short_title'    => $row['product_short_title'],
            'product_price' => $row['market_price'],
            'product_brand' => $row['product_brand'],
            'article_number' => $row['article_number'],
            'stock' => $row['stock'],
            'product_short_desc' => $row['short_description'],
            'category_id' => 1,
            'user_id' =>  AUTH::id(),
            'tax' =>  TAX_PERCENT,
            'seller_id' =>  6,
        ]);

        /* 
            $url = "http://www.google.co.in/intl/en_com/images/srpr/logo1w.png";
            $contents = file_get_contents($url);    
            $name = substr($url, strrpos($url, '/') + 1);
            $imageName = time().'.'.$name;
            $filePath = public_path('/images/admin/products/').$data->id; 
            $url = 'https://pay.google.com/about/static/images/social/og_image.jpg';
            $info = pathinfo($url);
            $contents = file_get_contents($url);
            $file = $filePath.'/' . $info['basename'];
            file_put_contents($file, $contents);
            $uploaded_file = new UploadedFile($file, $info['basename']);
            dd($uploaded_file);
            die();
        */

    }

     public function rules(): array
    {
        return [
            'product_title' => 'required',
            'product_short_title' => 'required',
            'market_price' => 'required',
            'product_brand' => 'required',
            'article_number' => 'required',
            'stock' => 'required',
            'short_description' => 'required',
          //  'image' => 'required',
        ];
    }
}
