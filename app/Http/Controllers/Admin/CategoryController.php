<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Users;
use App\User;
use Auth;
use App\Http\Helper\Helper;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAmountCategory;
use App\Models\Attribute;
use DataTables;
use App\Http\Requests\Category as CategoryRequest;
use App\Http\Requests\ProductAmount as ProductAmountCategoryReq; 

class CategoryController extends BaseController{
    

    public function __construct(){
        $this->middleware(function ($request, $next) {
            if(Auth::user()->status != User::ACTIVE)
            {
                Auth::logout();
                return redirect(route('admin-login'));
            }
            return $next($request);
        });
    }
    
   /**
    * Display a view for category.
    *
    * @return \Illuminate\Http\Response
    */

    public function productCategory(Request $request){
        return view('admin.categories.category')->with('active', 'products')->with('sub_active', 'category');
    }
    /**
    * Display a view for product amount category.
    *
    * @return \Illuminate\Http\Response
    */

    public function productAmountCategory(Request $request){
        return view('admin.product_amount_categories.product_amount_category')->with('active', 'products')->with('sub_active', 'product_amount_categories');
    }


    /**
    * Display a listing of the resource using datatable
    *
    * @return \Illuminate\Http\Response
    */

    public function categoryList(){

        $categories = Category::query()->where('type',Category::PRODUCT)->orderBy('id', 'DESC');
      
        return DataTables::of($categories)
                 ->addIndexColumn()
                 ->addColumn('action', function ($category) {
                            return '<a href="'.route("admin_category_add", encrypt_decrypt('encrypt',$category->id)) . '"><button data-toggle="tooltip" title="'.trans('label.edit').'" class="pd-setting-ed"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a><button title="'.trans('label.delete').'" class="pd-setting-ed" del_token="'.encrypt_decrypt('encrypt',$category->id).'" onclick="category_delete('.$category->id.')"><i class="fa fa-remove " aria-hidden="true" ></i></button>';
                        })
                 ->orderColumn('id', 'id -$1')
                 ->rawColumns(['type', 'action','id'])
            ->make(true);
        return view('admin.categories.category');
    }
    /**
    * Display a listing of the resource using datatable
    *
    * @return \Illuminate\Http\Response
    */

    public function amountCategoryList(){

        $amount_category = ProductAmountCategory::get();

      
        return DataTables::of($amount_category)
                 ->addIndexColumn()
                 ->addColumn('action', function ($amount_category) {
                            return '<a href="'.route("admin_product_amount_category_add", encrypt_decrypt('encrypt',$amount_category->id)) . '"><button data-toggle="tooltip" title="'.trans('label.edit').'" class="pd-setting-ed"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a><button title="'.trans('label.delete').'" class="pd-setting-ed delete-btn" del_token="'.encrypt_decrypt('encrypt',$amount_category->id).'"><i class="fa fa-remove " aria-hidden="true"></i></button>';
                        })
               //  ->orderColumn('id', 'id -$1')
            ->make(true);
        return view('admin.product_amount_categories.product_amount_category');
    }


    /**
    * Function to delete the category.
    */
    public function categoryDelete(Request $request){
      if($request->has('id')){

        $category_id = $request->id;
        $category_in_product = Product::where('category_id',$category_id)->get();
        if($category_in_product->isEmpty()){

          Category::find($category_id)->delete();
          //return response()->json(['message'=>trans('message.product_deleted_successfully'),'product_id'=>$product_id],200);
          return response()->json(['message'=>trans('message.category_deleted_successfully')],200);
        }
        else{
          //echo "Product exists in an Auction. Cannot be deleted.";
          return response()->json(['message'=>trans('message.category_exists_product')],200);
        }
      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_category')],200);
      }
    }


    /**
     * Add and edit page for category
     *
     * @param  id
     * @return View
    */
    public function addCategory($id=''){
        if ($id && $id !='') {
            $id = encrypt_decrypt('decrypt',$id);
            $category = Category::find($id);
        }
        else {
            $category = new Category;
        }

        $attributes  = Attribute::all();
        return view('admin.categories.addcategory',compact('category','attributes'))->with('active', 'products')->with('sub_active', 'category');   
        //return view('admin.products.addcategory');
    }
    /**
     * Add and edit page for product amount category
     *
     * @param  id
     * @return View
    */
    public function addProductAmount($id=''){
        if ($id && $id !='') {
            $id = encrypt_decrypt('decrypt',$id);
            $product_amount = ProductAmountCategory::find($id);
        }
        else {
            $product_amount = new ProductAmountCategory;
        }

        // $attributes  = Attribute::all();
        return view('admin.product_amount_categories.add_product_amount',compact('product_amount'))->with('active', 'product_amount_categories');   
        //return view('admin.products.addcategory');
    }

    /**
     * Used to save/update the category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function saveCategory(CategoryRequest $request){
        
        $data = $request->except('_token','category_id');
        if ($request->has('category_id') && $request->get('category_id')) {
            $category_record = Category::find($request->get('category_id'));
        }
        else {
            $category_record = new Category();
            $data['type'] = Category::PRODUCT;
            $data['status'] = Category::ACTIVE;
        }
        
        $category_record->fill($data);       
       
        if($category_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('category_id') && $request->get('category_id') !='') {
                $request->session()->flash('message.content',trans('message.category_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.category_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.category_created_successfully'),'category_id'=>$category_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_category'));
            return response()->json(['message'=>trans('message.error_created_category')],200);            
        }
         

    }
     /**
     * Used to save/update the product amount category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function saveProductAmount(ProductAmountCategoryReq $request){
        
        $data = $request->except('_token','category_id');
        if ($request->has('category_id') && $request->get('category_id')) {
            $category_record = ProductAmountCategory::find($request->get('category_id'));
        }
        else {
            $category_record = new ProductAmountCategory();
            
            $data['status'] = ProductAmountCategory::ACTIVE;
        }
        
        $category_record->fill($data);       
       
        if($category_record->save())
        {   
            $request->session()->flash('message.level','success');
            if ($request->has('category_id') && $request->get('category_id') !='') {
                $request->session()->flash('message.content',trans('message.category_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.category_created_successfully'));
                
            }
            return response()->json(['message'=>trans('message.category_created_successfully'),'category_id'=>$category_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_category'));
            return response()->json(['message'=>trans('message.error_created_category')],200);            
        }
         

    }


    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function deleteCategory(Request $request){
        /*
        if ($request->has('token') && $request->get('token') !='') {
            $record = Category::find(encrypt_decrypt('decrypt',$request->get('token')));
            if($record->delete()){   
                $request->session()->flash('message.level','success');
                $request->session()->flash('message.content',trans('message.category_deleted_successfully'));
                return response()->json(['message'=>trans('message.category_deleted_successfully'),'category_id'=>$record->id,'code'=>200]);
            }else{
                $request->session()->flash('message.level','danger');
                $request->session()->flash('message.content',trans('message.error_deleting_category'));
                return response()->json(['message'=>trans('message.error_deleting_category'),'code'=>504]);            
            }
        }
       */
        
         

    }

}
