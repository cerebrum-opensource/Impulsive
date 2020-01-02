<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Package;
use App\Models\UserPlan;
use Auth;
use App\Http\Requests\Package as PackageRequest; 
use App\Http\Requests\PromoPackage as PromoPackageRequest; 
use Carbon\Carbon;
use Config;

class PackageController extends ApiController{
   


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
      $this->middleware(function ($request, $next) {
            if(Auth::user()->status != '1')
            {
                Auth::logout();
                return redirect(route('admin-login'));
            }
            return $next($request);
        });
      $this->timezone = user_time_zone();
        
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getPackageList() {
        return view('admin.packages.index')->with('active', 'packages')->with('sub_active', 'package_list');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getPackages()
    {
        $packages = Package::query()->default()->orderBy('id', 'DESC');
        return \ DataTables::of($packages)->editColumn('status', 'admin.packages.datatables.status_column')->addColumn('actions', 'admin.packages.datatables.action_buttons ')->addColumn('setting', 'admin.packages.datatables.setting_buttons ')->rawColumns(['actions','status','setting'])->addIndexColumn()->make(true);
    }


    /**
    * Function to delete the package.
    */
    public function packageDelete(Request $request){
      if($request->has('id')){

        $package_id = $request->id;
        $package_in_userplan = UserPlan::where('package_id',$package_id)->get();
        if($package_in_userplan->isEmpty()){

          Package::find($package_id)->delete();
          //return response()->json(['message'=>trans('message.product_deleted_successfully'),'product_id'=>$product_id],200);
          return response()->json(['message'=>trans('message.package_deleted_successfully')],200);
        }
        else{
          //echo "Product exists in an Auction. Cannot be deleted.";
          return response()->json(['message'=>trans('message.package_exists_userplan')],200);
        }
      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_package')],200);
      }
    }



    /**
    * Display a add/edit page for package.
    * parameter id
    *
    * @return View
    */

    public function getCreateEdit($id=null)
    {
      if($id)
      {
        try{
            $id = encrypt_decrypt('decrypt',$id);
        } catch (DecryptException $e) {
            abort(404);
            exit;
        }
        $package = Package::find($id);              
      }
      else
      {
         $package = new Package;   
      }
      return view('admin.packages.add', ['package'=>$package])->with('active', 'packages')->with('sub_active', 'package_add');
    }

    /**
    * Save/update the package.
    * parameter PackageRequest $request
    *
    * @return \Illuminate\Http\Response
    */

    public function postCreate(PackageRequest $request)
    {
      $message = '';
      $confirmation_code = str_random(30);  

      $packageData = $request->except('_token', 'package_id','image');

      $packageData['type'] = Package::DEFAULT;
      if ($request->has('package_id') && $request->get('package_id')) {
            $package = Package::find($request->get('package_id'));
          
            $package->fill($packageData);
            $update = $package->save();
            $message = trans('message.package_updated_successfully');
      }
      else{
     //   $packageData = $request->except('_token', 'package_id','image');
        $packageData['status'] = Package::ACTIVE;
        $package = Package::create($packageData);
        
        $message = trans('message.package_created_successfully');
      }
        $filePath = public_path('/images/admin/packages/').$package->id; 
        if($request->image != null)
        {   
            $imageName1 = time().'.'.request()->image->getClientOriginalExtension();
            $uploadedDP = $request->file('image');
            $uploadedDP->move($filePath, $imageName1);
            Package::where('id',$package->id)->update(['image'=>$imageName1]);
        }
     
      if($package){
          $request->session()->flash('message.level','success');
          $request->session()->flash('message.content',$message);
          return response()->json(['message'=> $message],200);         
      }
      else
        {
          $request->session()->flash('message.level','danger');
          $request->session()->flash('message.content',trans('message.error_package_seller'));
          return response()->json(['message'=>trans('message.error_package_seller')],500);            
        }
    }


    /**
    * Active/deactive package.
    * parameter PackageRequest $request
    *
    * @return \Illuminate\Http\Response
    */

    public function postChangeStatus(Request $request)
    {
    
        $response = Package::where('id',$request->id)->update(['status'=>$request->status]);
        
        if($response)
        {
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.package_status_changed_successfully'));
            return response()->json(['message'=>trans('message.package_status_changed_successfully'),'status'=>1],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_updated_package_status'));
            return response()->json(['message'=>trans('message.error_updated_package_status'),'status'=>0],200);            
        }
    }



    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getPromoPackageList() {
        return view('admin.promotional_packages.index')->with('active', 'packages')->with('sub_active', 'list_promo');
    }


    /**
    * Display a listing of the resource using datatables
    *
    * @return \Illuminate\Http\Response
    */

    public function getPromoPackages()
    {
        $packages = Package::query()->promo()->orderBy('id', 'DESC');
        return \ DataTables::of($packages)->editColumn('status', 'admin.promotional_packages.datatables.status_column')->addColumn('actions', 'admin.promotional_packages.datatables.action_buttons ')->addColumn('setting', 'admin.promotional_packages.datatables.setting_buttons ')->rawColumns(['actions','status','setting'])->addIndexColumn()->make(true);
    }


    /**
    * Function to delete the package.
    */
    public function promoPackageDelete(Request $request){
      if($request->has('id')){

        $package_id = $request->id;
        $package_in_userplan = UserPlan::where('package_id',$package_id)->get();
        if($package_in_userplan->isEmpty()){

          Package::find($package_id)->delete();
          //return response()->json(['message'=>trans('message.product_deleted_successfully'),'product_id'=>$product_id],200);
          return response()->json(['message'=>trans('message.package_deleted_successfully')],200);
        }
        else{
          //echo "Product exists in an Auction. Cannot be deleted.";
          return response()->json(['message'=>trans('message.package_exists_userplan')],200);
        }
      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_package')],200);
      }
    }


    /**
    * Display a add/edit page for Promo Package.
    * parameter id
    *
    * @return View
    */

    public function getPromoCreateEdit($id=null)
    {
      if($id)
      {
        try{
            $id = encrypt_decrypt('decrypt',$id);
        } catch (DecryptException $e) {
            abort(404);
            exit;
        }
        $package = Package::find($id);              
      }
      else
      {
         $package = new Package;   
      }
      return view('admin.promotional_packages.add', ['package'=>$package])->with('active', 'packages')->with('sub_active', 'list_promo');
    }


    /**
    * Save/update the package.
    * parameter PackageRequest $request
    *
    * @return \Illuminate\Http\Response
    */

    public function postPromoCreate(PromoPackageRequest $request)
    {
      $message = '';

      $packageData = $request->except('_token', 'package_id','image');

      $packageData['type'] = Package::PROMO;

      $timezone = $this->timezone;
         
      if($request->has('start_date') && $request->get('start_date')){

        $timestamp = $request->get('start_date');
        $sdate = Carbon::createFromFormat('Y-m-d H:i', $timestamp, $timezone);
        $sdate->setTimezone('UTC');
        $nowTime = $sdate->format('Y-m-d H:i:s');
        $packageData['start_date'] = $nowTime;
      }
      if($request->has('end_date') && $request->get('end_date')){
        $timestamp = $request->get('end_date');
        $date = Carbon::createFromFormat('Y-m-d H:i', $timestamp, $timezone);
        $date->setTimezone('UTC');
        $nowTime = $date->format('Y-m-d H:i:s');
        $packageData['end_date'] = $nowTime;
      }

      $todayDate =  \Carbon\Carbon::now()->format('Y-m-d H:i');
      $startDate =  \Carbon\Carbon::createFromFormat('Y-m-d H:i',$sdate->format('Y-m-d H:i'));


      $diff_in_days = $startDate->diffInMinutes($todayDate);
      if($diff_in_days == 0){
        $packageData['status'] = Package::ACTIVE;
      }
      else {
        $packageData['status'] = Package::DRAFT;
      }

      if ($request->has('package_id') && $request->get('package_id')) {
            $package = Package::find($request->get('package_id'));     
            $package->fill($packageData);
            $update = $package->save();
            $message = trans('message.promo_package_updated_successfully');
      }
      else{
       // $packageData = $request->except('_token', 'package_id','image');
        $package = Package::create($packageData);
        $message = trans('message.promo_package_created_successfully');
      }
        $filePath = public_path('/images/admin/packages/').$package->id; 
        if($request->image != null)
        {   
            $imageName1 = time().'.'.request()->image->getClientOriginalExtension();
            $uploadedDP = $request->file('image');
            $uploadedDP->move($filePath, $imageName1);
            Package::where('id',$package->id)->update(['image'=>$imageName1]);
        }
     
      if($package){
          $request->session()->flash('message.level','success');
          $request->session()->flash('message.content',$message);
          return response()->json(['message'=> $message],200);         
      }
      else
        {
          $request->session()->flash('message.level','danger');
          $request->session()->flash('message.content',trans('message.error_promo_package_seller'));
          return response()->json(['message'=>trans('message.error_promo_package_seller')],500);            
        }
    }


    /**
    * Active/deactive Promo package.
    * parameter PackageRequest $request
    *
    * @return \Illuminate\Http\Response
    */


     public function postPromoChangeStatus(Request $request)
    {
      
      $data = $request->except('id');
      $message = '';
      if($request->status){
        $message = trans('message.promo_package_active_successfully');
        $data['start_date'] = \Carbon\Carbon::now()->format('Y-m-d H:i');
        $data['end_date'] = \Carbon\Carbon::now()->format('Y-m-d').' 23:59:59';
      }
      else{
        $message = trans('message.promo_package_disable_successfully');
        $data['start_date'] = \Carbon\Carbon::now()->subDay()->format('Y-m-d H:i');
        $data['end_date'] = \Carbon\Carbon::now()->subDay()->format('Y-m-d H:i');
      }

      $response = Package::where('id',$request->id)->update($data);
      
      if($response)
      {
          $request->session()->flash('message.level','success');
          $request->session()->flash('message.content',$message);
          return response()->json(['message'=>$message,'status'=>1],200);
      }
      else
      {
          $request->session()->flash('message.level','danger');
          $request->session()->flash('message.content',trans('message.error_updated_promo_package_status'));
          return response()->json(['message'=>trans('message.error_updated_promo_package_status'),'status'=>0],200);            
      }
    }

}
