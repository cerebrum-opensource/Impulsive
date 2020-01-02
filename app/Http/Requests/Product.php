<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Product extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    { 
        
        $regex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";
    	$rules = [];
    	$rules['product_title'] = "required|max:100";
        $rules['product_price'] = "required|numeric|min:1|max:99999.99";
        $rules['shipping_price'] = 'nullable|numeric|min:1|max:99999.99';
        $rules['tax'] = 'nullable|numeric|min:1|max:99.99';
        $rules['products_short_title'] = "required|max:100";
        $rules['product_brand'] = "required|max:100";
        $rules['product_short_desc'] = "required|max:100";
        $rules['category_id'] = "required|exists:categories,id,deleted_at,NULL";
        $rules['seller_id'] = "exists:users,id,deleted_at,NULL";  
       
         if ($this->has('product_id') && $this->get('product_id')) {
           // $rules['email'] = 'required|max:45|email|unique:users,email,'.$this->get('user_id').',id';
         //   $rules['phone'] = 'required|unique:users,phone,'.$this->get('user_id').',id';

             $rules['article_number'] = 'required|unique:products,article_number,'.$this->get('product_id').',id';
        }
        else{
            $rules['article_number'] = 'required|unique:products,article_number';
            $rules['prdctimg1'] = 'required_without_all:prdctimg2,prdctimg3,prdctimg4';
            $rules['prdctimg2'] = 'required_without_all:prdctimg1,prdctimg3,prdctimg4';
            $rules['prdctimg3'] = 'required_without_all:prdctimg2,prdctimg1,prdctimg4';
            $rules['prdctimg4'] = 'required_without_all:prdctimg2,prdctimg3,prdctimg1';
        }

        $rules['stock'] = 'required';

        //$rules['other_value'] = "required_if:type,==,1|required_if:type,==,3|required_if:type,==,4|max:100";
		return $rules;
		
        
    }

    public function messages(){
    	return [
    		'other_value.required_if' => 'Please enter value',
            'prdctimg1.required_without_all' => 'Please upload atleast one image for product',
            'prdctimg2.required_without_all' => 'Please upload atleast one image for product',
            'prdctimg3.required_without_all' => 'Please upload atleast one image for product',
            'prdctimg4.required_without_all' => 'Please upload atleast one image for product',
		];
    }
}
