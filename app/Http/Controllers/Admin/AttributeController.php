<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Users;
use App\User;
use Auth;
use App\Models\Attribute;
use App\Models\Category;
use DataTables;
use App\Http\Requests\Attribute as AttributeRequest; 

class AttributeController extends BaseController{
    

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct(){

       // to check if the status changes so auto logout the user
        $this->middleware(function ($request, $next) {
            if(Auth::user()->status != User::ACTIVE)
            {
                Auth::logout();
                return redirect(route('admin-login'));
            }
            return $next($request);
        });
    }

    public function list(Request $request){
        return view('admin.attributes.attribute')->with('active', 'products')->with('sub_active', 'attribute');
    }

     /**
    * Display a listing of the resource with datatable
    *
    * @return \Illuminate\Http\Response
    */

    public function attributeList(){

        $attributes = Attribute::query()->orderBy('id', 'DESC');
        return DataTables::of($attributes)
                 ->addIndexColumn()
                 ->addColumn('action', function ($attribute) {
                    return '<a href="' . route("attribute_add", encrypt_decrypt('encrypt',$attribute->id)) . '"><button data-toggle="tooltip" title="'.trans('label.edit').'" class="pd-setting-ed"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a><a href="#"  style="color:red" data-id="'.\encrypt_decrypt('encrypt',$attribute->id).'"  data-model="Attribute" title="'.trans('label.delete').'" class="delete_model_by_id"><button title="'.trans('label.delete').'" class="pd-setting-ed" onclick="attribute_delete('.$attribute->id.')"><i class="fa fa-remove " aria-hidden="true"></i></button></a>';
                    })
                 ->addColumn('category', function ($category) {
                    return $category->category_id ? $category->category_name->name : trans('label.pre_defined');
                    })
                 ->orderColumn('id', 'id -$1')
                 ->rawColumns(['type', 'action','id'])
            ->make(true);
       // return view('admin.attributes.attribute');
    }



    /**
    * Function to delete the attribute.
    */
    public function attributeDelete(Request $request){
      if($request->has('id')){

        $attribute_id = $request->id;
        // $category_in_product = Product::where('category_id',$category_id)->get();
        Attribute::find($attribute_id)->delete();
        //return response()->json(['message'=>trans('message.product_deleted_successfully'),'product_id'=>$product_id],200);
        return response()->json(['message'=>trans('message.attribute_deleted_successfully')],200);
      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_attribute')],200);
      }
    }




    /**
     * Add/Edit page for attribute
     *
     * @param  \Illuminate\Http\Request  $request
     * @return View
    */
    public function getAdd($id=''){

        $categoryList  = Category::all()->pluck('name','id')->toArray();
        if ($id && $id !='') {
            $id = encrypt_decrypt('decrypt',$id);
            $attribute = Attribute::find($id);
        }
        else {
            $attribute = new Attribute;
        }
        
        return view('admin.attributes.add_attribute',compact('attribute','categoryList'))->with('active', 'products')->with('sub_active', 'attribute');  
        //return view('admin.products.addcategory');
    }

     /**
     * Save/Update the attribute
     *
     * @param  \Illuminate\Http\AttributeRequest  $request
     * @return \Illuminate\Http\Response
    */
    public function postSave(AttributeRequest $request){
        $data = $request->except('_token','attribute_id');
        if ($request->has('attribute_id') && $request->get('attribute_id')) {
            $attribute_record = Attribute::find($request->get('attribute_id'));
        }
        else {
            $attribute_record = new Attribute();
            $data['status'] = Attribute::ACTIVE;
            $data['value']  = str_slug($data['name'], '-');
        }
        
        /*if(count($data['other_value'])){
            $data['other_value'] = json_encode($data['other_value']);
        }*/
        $attribute_record->fill($data);
        if($attribute_record->save())
        {   
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.attribute_created_successfully'));
            return response()->json(['message'=>trans('message.attribute_created_successfully'),'attribute_id'=>$attribute_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_attribute'));
            return response()->json(['message'=>trans('message.error_created_attribute')],200);            
        }
         

    }

}
