<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Users;
use App\User;
use Auth;
use App\Models\Product;
use DataTables;
use App\Http\Requests\Product as ProductRequest; 
use App\Models\Category;
use App\Models\Auction;
use App\Models\Attribute;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\Package;
use App\Models\ProductAmountCategory;
use App\Models\ProductAfterplayCategory;
use App\Models\ProductData;
use App\Models\CSVFileUpdateTime;
//use Carbon;
use Storage;
use View;
use Excel;
use App\Imports\ProductsImport;
use Log;

class ProductController extends BaseController{



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
     * Render view for listing of the product.
     * Parameter : Request
     * @param Request $request
     * @return \Illuminate\Http\View
     * @throws \Throwable
     */

    public function productList(Request $request,Product $product){
         $active = '';
         
          $categoryList  = Category::all()->pluck('name','id')->toArray();
        if ($request->ajax()) {
            $product = $product->newQuery();
            if ($request->has('status') && $request->input('status') !='') {
                    $product->where('status', $request->input('status'));
            }

            if ($request->has('category_id') && $request->input('category_id') !='') {
                    $product->where('category_id', $request->input('category_id'));
            }

            if ($request->has('product_title') && $request->input('product_title') !='') {
                    $product->where(function($q) use($request){ 
                        $q->orWhere('product_title', 'like', $request->input('product_title') . '%')
                        ->orWhere('product_brand', 'like', '%'.$request->input('product_title').'%')
                        ->orWhere('products_short_title', 'like', '%'.$request->input('product_title').'%')
                        ->orWhere('product_short_desc', 'like', '%'.$request->input('product_title').'%');
                    });
            }
            $products = $product->orderBy('id', 'desc')->paginate(PAGINATION_COUNT_10);
           
            $request->flashOnly(['status','filter_string','category_id']);
            return view('admin.products.table', ['products' => $products])->render();  
        }
        else {
            $filter_status = "";
            $filter_string = "";
            $category_id = "";
            $product = $product->newQuery();
            if ($request->old('status') !='') {
                $filter_status = $request->old('status');
                $product->where('status', $request->old('status'));
            }
            if ($request->old('category_id') && $request->old('category_id') !='') {
                    $category_id = $request->old('status');
                    $product->where('category_id', $request->old('category_id'));
            }

            if ($request->old('product_title') && $request->old('product_title') !='') {
                    $filter_string = $request->old('product_title');
                    $product->where(function($q) use($request){ 
                        $q->orWhere('product_title', 'like', $request->old('product_title') . '%')
                        ->orWhere('product_brand', 'like', '%'.$request->old('product_title').'%')
                        ->orWhere('products_short_title', 'like', '%'.$request->old('product_title').'%')
                        ->orWhere('product_short_desc', 'like', '%'.$request->old('product_title').'%');
                    });
            }
            
           
            $request->flashOnly(['status','filter_string','category_id']);
            $products = $product->with('product_amount_categories')->orderBy('id', 'desc')->paginate(PAGINATION_COUNT_10);

            return view('admin.products.productlist',compact('active','filter_status','filter_string','categoryList'))->with('products', $products)->with('active', 'products')->with('sub_active', 'manage');
        } 

    }

     /**
     * Render view for add/edit product.
     * Parameter : id
     * @param $id
     * @return \Illuminate\Http\View
     * @throws \Throwable
     */

    public function productAdd($id=''){
        $fields = [];
        $fieldValues = [];
        $editFields = [];

        $sellers = User::query()->whereHas("roles", function($q){ $q->where("name", '=', "SELLER"); })->with("roles")->get()->pluck('full_name','id')->prepend(trans('label.please_select'), '')->toArray();
        // $sellers[0] = 'winimi';
        // ksort($sellers);
        $number_of_bids = Package::where('status',1)->where('type','default')->pluck('name','id');
        $categoryList  = Category::all()->pluck('name','id')->toArray();
        $afterplayList = ProductAmountCategory::all()->pluck('name','id')->toArray();
        if ($id && $id !='') {
            $id = encrypt_decrypt('decrypt',$id);
            $product = Product::with('category_name','product_attributes')->find($id);
            if(count($product->product_attributes)){
                $attribute_value = json_decode($product->product_attributes[0]->product_attributes,true);
                $attribute_ids = array_keys($attribute_value);
                $fieldValues = $attribute_value;
                $fields = Attribute::whereIn('id',$attribute_ids)->get();
                $editFields = Attribute::whereNotIn('id',$attribute_ids)->get();
            }

            
        }
        else {
            $product = new Product;
        }
        return view('admin.products.productadd',compact('product','categoryList','afterplayList','fields','fieldValues','editFields','sellers','number_of_bids'))->with('active', 'products')->with('sub_active', 'manage');
    }

    public function imageDragDrop(Request $request){
       $source = $request->source;
       $destination = $request->destination;
       $product = Product::find($request->prod_id);
       if($destination){
         $data[$request->source] = $product[$destination];
       }
        if($source){
         $data[$request->destination] =  $product[$source];
       }

      // print_r($data);
     //  $data[$request->destination] = $source;
      Product::where('id',$request->prod_id)->update($data);
      //print_r($request->all());
      //print_r($product);
     
    }
    /**
     * Save product
     * @param ProductRequest $request
     * @return Json response with success and failure message
     */

    public function saveProduct(ProductRequest $request){

      // print_r( $request->all());

      // die();
      // dd($request->afterplay_id);

        $fileArray = [];
        $imageName = [];

        if($request->show_seller == 'on'){
            $request->request->add(['show_seller'=>1]);
        }
        else {
            $request->request->add(['show_seller'=>0]);
        }
        if($request->is_package == 'on'){
            $request->request->add(['is_package'=>1]);
        }
        else {
            $request->request->add(['is_package'=>0]);
        }
        //Storage::disk('s3')->put(config('filesystems.s3_patient_images_partial_path'), file_get_contents($request->file('image')));
        $customData = $request->except('_token','product_id','product_title','product_price','shipping_price','category_id','products_short_title','product_brand','tax','product_short_desc','product_long_desc','prdctimg1','prdctimg2','prdctimg3','prdctimg4','seller_id','stock','article_number','show_seller','is_package','package_id');
        $customKey = array_keys($customData);
        $customKey[]='_token';
        $customKey[]='product_id';
        $customKey[]='select';
        $customKey[]='prdctimg1';
        $customKey[]='prdctimg2';
        $customKey[]='prdctimg3';
        $customKey[]='prdctimg4';
        $data = $request->except($customKey);
        $data['user_id'] =  Auth::id(); 
       // $data['seller_id'] =  Auth::id(); 
        if ($request->has('product_id') && $request->get('product_id')) {
            $product_record = Product::find($request->get('product_id'));
        }
        else {
            $product_record = new Product();

            $data['status'] = Product::ACTIVE;
        }
        
       // print_r($data);
        $product_record->fill($data);       
       
        if($product_record->save())
        {   
            if(count($customData)){
               $product_afterplay = new ProductAfterplayCategory();
               $loop_afterplay = $request->get('afterplay_id'); 
               $mainArray = [];
               if($loop_afterplay){
                  foreach ($loop_afterplay as $value){

                    $dataArray['product_id'] = $product_record->id;
                    $dataArray['afterplay_category_id'] = $value;
                    $dataArray['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
                    $dataArray['updated_at'] = \Carbon\Carbon::now()->toDateTimeString();

                     $mainArray[] = $dataArray;

                  }
                  // print_r($mainArray);
                  ProductAfterplayCategory::where('product_id',$product_record->id)->delete();
                  ProductAfterplayCategory::insert($mainArray);
              }
               ProductAttribute::where('product_id',$product_record->id)->delete();
               $customSave['product_id'] = $product_record->id;
               $customSave['product_attributes'] = json_encode($customData); 
               $customSave['user_id'] = Auth::id(); 
               $ProductAttribute = new ProductAttribute();
               $ProductAttribute->fill($customSave);  
               $ProductAttribute->save();
            }
            $filePath = public_path('/images/admin/products/').$product_record->id; 
            if($request->prdctimg1 != null)
            {   
                $imageName1 = time().'1.'.request()->prdctimg1->getClientOriginalExtension();
                $imageName['feature_image_1'] = $imageName1;
                $uploadedDP = $request->file('prdctimg1');
                $uploadedDP->move($filePath, $imageName1);
            }
            if($request->prdctimg2 != null)
            {   
                $imageName2 = time().'2.'.request()->prdctimg2->getClientOriginalExtension();
                $imageName['feature_image_2'] = $imageName2;
                $uploadedDP = $request->file('prdctimg2');
                $uploadedDP->move($filePath, $imageName2);
            }
            if($request->prdctimg3 != null)
            {
                $imageName3 = time().'3.'.request()->prdctimg3->getClientOriginalExtension();
                $imageName['feature_image_3'] = $imageName3;
                $uploadedDP = $request->file('prdctimg3');
                $uploadedDP->move($filePath, $imageName3);
            }
            if($request->prdctimg4 != null)
            {
                $imageName4 = time().'4.'.request()->prdctimg4->getClientOriginalExtension();
                $imageName['feature_image_4'] = $imageName4;
                $uploadedDP = $request->file('prdctimg4');
                $uploadedDP->move($filePath, $imageName4);
            }
            // print_r($imageName);
            // die();
            if(count($imageName)){
                Product::where('id',$product_record->id)->update($imageName);
            }
            $request->session()->flash('message.level','success');
            if ($request->has('product_id') && $request->get('product_id')) {
                $request->session()->flash('message.content',trans('message.product_updated_successfully'));
            }else{
                $request->session()->flash('message.content',trans('message.product_created_successfully'));                
            }
            return response()->json(['message'=>trans('message.product_created_successfully'),'product_id'=>$product_record->id],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_created_product'));
            return response()->json(['message'=>trans('message.error_created_product')],200);            
        }
         

    }

    /**
    * Perform search on the basis of article number
    * @param $request
    * */

    public function productSearch(Request $request){
       if($request->has('article_number'))
       {
        $article_number = $request->get('article_number');
        $article_data = ProductData::where('article_number',$article_number)->get();
        if(!empty($article_data)){
            return response()->json(['data' => $article_data],200);
        }
        else{
            return response()->json(['message'=>'error in fetching article data'],200);
        }
       }
       else{
            return response()->json(['message'=>'error in fetching article number'],200);
       }
        
    }
    


    /**
     * Get Customfield data by id in add or edit product
     * @param $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function getCustomField(Request $request){
        $fields = [];
        $unassignedFields = [];

        // custom field in case of add product
        if ($request->has('id') && $request->get('id')) {
            $category = Category::findOrFail($request->get('id')); 
            if($category)
                $fields = Attribute::whereIn('id',$category->attribute_id)->get(); 
                $unassignedFields = Attribute::whereNotIn('id',$category->attribute_id)->get();  
                $dataView['fields'] = View::make('admin.products.fields')->with('fields', $fields)->with('unassignedFields', $unassignedFields)->render();      
        }

        // custom field in case of edit product
        if (($request->has('id') && $request->get('id')) && ($request->has('is_same') && $request->get('is_same')) ) {
            $id = $request->get('product_id');
            $product = Product::with('category_name','product_attributes')->find($id);
            if(count($product->product_attributes)){
                $attribute_value = json_decode($product->product_attributes[0]->product_attributes,true);
                $attribute_ids = array_keys($attribute_value);
                $fieldValues = $attribute_value;
                $fields = Attribute::whereIn('id',$attribute_ids)->get();
                $editFields = Attribute::whereNotIn('id',$attribute_ids)->get();

                $dataView['fields'] = View::make('admin.products.attributes')->with('fields', $fields)->with('fieldValues', $fieldValues)->with('editFields', $editFields)->render(); 
            }      
        }
        
        return response()->json(['html'=>$dataView],200);
    }   

    public function getProductListData(Request $request){

         $dataList = Product::where(function($q) use($request){ 
            $q->orWhere('product_title', 'like', $request->input('keyword') . '%')
                ->orWhere('products_short_title', 'like', '%'.$request->input('keyword').'%')
                ->orWhere('product_brand', 'like', '%'.$request->input('keyword').'%')
                ->orWhere('product_long_desc', 'like', '%'.$request->input('keyword').'%')
                ->orWhere('article_number', 'like', '%'.$request->input('keyword').'%');
            })->limit(100)->pluck('product_title','id');   
        echo "<ul id='pro-list'>";
        foreach ($dataList as $key => $data) {
           echo "<li onClick='selectCountry(`$data`)'>$data</li>";
        }
        echo "</ul>";

    }


    public function postProductListData(Request $request)
    {  
        $icd_codes = [];

        if ($request->has('data') && $request->input('data') !='') {
            $icd_codes = Product::where(function($q) use($request){ 
            $q->orWhere('product_title', 'like', $request->input('data') . '%')
                ->orWhere('products_short_title', 'like', '%'.$request->input('data').'%')
                ->orWhere('product_brand', 'like', '%'.$request->input('data').'%')
                ->orWhere('product_long_desc', 'like', '%'.$request->input('data').'%')
                ->orWhere('article_number', 'like', '%'.$request->input('data').'%');
            })->where('status',Product::ACTIVE)->get();
            return response()->json(['html' => $icd_codes], 200);
        }
    
    }




     /**
     * Render view for add/edit product.
     * Parameter : id
     * @param $id
     * @return \Illuminate\Http\View
     * @throws \Throwable
     */

    public function productImportPage(){
        return view('admin.products.product_import');
    }

   
    /**
     * Save product
     * @param ProductRequest $request
     * @return Json response with success and failure message
     */

    public function importProduct(Request $request){
        $request->validate([
            'import_file' => 'required'
        ]);
 
       

        Excel::import(new ProductsImport, $request->file('import_file'));
        return back()->with('success', 'Product imported successfully.');
       
    }
    
    public function insertCSVUpdate(){
      //error_reporting(0);
      // $file_n = public_path('/csv_file/product_details.csv');
      // $file_size = md5(filesize($file_n));
      // $insertData = array('file_size'=>$file_size);

      // $last_filesize = CSVFileUpdateTime::fetchData();
      // // echo $file_size;
      // // echo "<br>";
      // // echo $last_filesize->file_size;
      // // die();
      // if($file_size != $last_filesize->file_size){
      //   CSVFileUpdateTime::insertData($insertData);
      //   $this->importProductCSV();
      // }else{
      //   echo "same file/error";
      // }
      $file_n = "https://get.cpexp.de/pYD80muLq5m_1b0KTG5-1Tk1AkHNo1gLl_Z2acwsy5GJTs9kKdc8-_D1j0goHGft/cyberport-feedsmitmengen_emnamediavertriebde.csv";
      date_default_timezone_set('Europe/Berlin');
      $file_details = get_headers($file_n, 1);
      
      $updated_time =$file_details['Last-Modified'];
      
      //$updated_time = date ("F d Y H:i:s.", filemtime($file_n));
      $file_size = md5($updated_time);
      //$file_size = md5(filesize($file_n));
      $insertData = array('file_size'=>$file_size);

      $last_filesize = CSVFileUpdateTime::fetchData();
      // echo $file_size;
      // echo "<br>";
      // echo $last_filesize->file_size;
      // die();
      if($file_size != $last_filesize->file_size){
        CSVFileUpdateTime::insertData($insertData);
        $this->importProductCSV();
      }else{
        echo "same file/error";
      }
    }


    /**
    * Function to delete the product.
    */
    public function productDelete(Request $request){
      if($request->has('id')){

        $product_id = $request->id;
        $product_in_auction = Auction::where('product_id',$product_id)->get();
        if($product_in_auction->isEmpty()){

          Product::find($product_id)->delete();
          //return response()->json(['message'=>trans('message.product_deleted_successfully'),'product_id'=>$product_id],200);
          return response()->json(['message'=>trans('message.product_deleted_successfully')],200);
        }
        else{
          
          return response()->json(['message'=>trans('message.product_exists_auction')],200);
        }
      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_product')],200);
      }
    }

    /**
     * Function to import CSV file of products in database 
    */

    public function importProductCSV(){
    //   error_reporting(0);
    //   $file_n = public_path('/csv_file/product_details.csv');
      
    //   //$file_n = public_path('/csv_file/test.txt');
    //   $infoPath = pathinfo($file_n);
    //   if($infoPath['extension'] == 'csv'){
    //       $file = fopen($file_n, "r");
    //       // date_default_timezone_set('Europe/Berlin');
    //       // echo date ("F d Y H:i:s.", filemtime($file_n));
    //       $i = 0;
    //       $all_data = array();
    //       $last_filesize = CSVFileUpdateTime::fetchData();
    //       $file_size = md5(filesize($file_n));
          
    //           while ( ($filedata = fgetcsv($file, 200, ",")) !==FALSE) {
    //             $num = count($filedata );
    //             for ($c=0; $c < $num; $c++) {
    //               $all_data[$i][] = $filedata [$c];
    //             }
    //             $i++;
    //           }
    //           fclose($file);
    //           foreach($all_data as $importData){
    //             $insertData = array(
    //                    "article_number"=>$importData[0],
    //                    "article_name"=>$importData[1],
    //                    "article_description"=>$importData[2],
    //                    "article_price"=>$importData[3],
    //                    "article_manufacturer"=>$importData[6],
    //                    "article_productgroupkey"=>$importData[7],
    //                    "article_productgroup"=>$importData[8],
    //                    "article_ean"=>$importData[9],
    //                    "article_hbnr"=>$importData[10],
    //                    "article_shippingcosttext"=>$importData[11],
    //                    "article_amount"=>$importData[12],
    //                    "article_paymentinadvance"=>$importData[13],
    //                    "article_maxdeliveryamount"=>$importData[14],
    //                    "article_energyefficiencyclass"=>$importData[15]
    //             );
           
    //           ProductData::insertData($insertData);
    //           }
        
    //   }else{
    //     echo "Invalid file extension.";
    //   }
    // }
      error_reporting(0);
      //$file_n = public_path('/csv_file/product_details.csv');
      
      $file_n = "https://get.cpexp.de/pYD80muLq5m_1b0KTG5-1Tk1AkHNo1gLl_Z2acwsy5GJTs9kKdc8-_D1j0goHGft/cyberport-feedsmitmengen_emnamediavertriebde.csv";

      $infoPath = pathinfo($file_n);
      if($infoPath['extension'] == 'csv'){
          $file = fopen($file_n, "r");
          date_default_timezone_set('Europe/Berlin');
          $file_details = get_headers($file_n, 1);
          $updated_time =$file_details['Last-Modified'];
          //  date_default_timezone_set('Europe/Berlin');
          // $updated_time = date ("F d Y H:i:s.", filemtime($file_n));
          $i = 0;
          $all_data = array();
          $last_filesize = CSVFileUpdateTime::fetchData();
          //$file_size = md5(filesize($file_n));
          $file_size = md5($updated_time);
          
              while ( ($filedata = fgetcsv($file, null, "|")) !==FALSE) {
                $num = count($filedata );
                for ($c=0; $c < $num; $c++) {
                  $all_data[$i][] = $filedata [$c];
                }
                $i++;
              }
              fclose($file);
              foreach($all_data as $importData){
                $insertData = array(
                       "article_number"=>$importData[0],
                       "article_name"=>$importData[1],
                       "article_description"=>$importData[2],
                       "article_price"=>$importData[3],
                       "article_manufacturer"=>$importData[6],
                       "article_productgroupkey"=>$importData[7],
                       "article_productgroup"=>$importData[8],
                       "article_ean"=>$importData[9],
                       "article_hbnr"=>$importData[10],
                       "article_shippingcosttext"=>$importData[11],
                       "article_amount"=>$importData[12],
                       "article_paymentinadvance"=>$importData[13],
                       "article_maxdeliveryamount"=>$importData[14],
                       "article_energyefficiencyclass"=>$importData[15]
                );
           
              ProductData::insertData($insertData);
              }
        
      }else{
        echo "Invalid file extension.";
      }

}
}
