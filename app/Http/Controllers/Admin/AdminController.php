<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Users;
use App\User;
use Spatie\Permission\Models\Role;
use Auth;
use App\Http\Helper\Helper;
use App\Http\Requests\User as UserRequest; 
use App\Http\Requests\Seller as SellerRequest; 
use Mail;
use App\Mail\PasswordCreate;

use App\Models\Attribute;
use App\Models\UserBidHistory;
use App\Models\AuctionWinner;
use App\Models\Product;
use App\Models\UserLossAuction;


class AdminController extends ApiController{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
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
        },['except' => 'loginForm']);
    }

    /**
     * Render the login form for admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return View
    */
    public function loginForm(Request $request){
        return view('admin.login');
    }

    /**
     *  Render the dashboard for admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return View
    */
    public function dashboard(Request $request){
        $data = $request->session()->all();
        return view('admin.dashboard')->with('active', 'dashboard')->with('sub_active', 'dashboard');
    }

    /**
     * Save the Profile pic of admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function saveAdminDP(Request $request){
        $data = $request->session()->all();
        $result = $request->all();
        $return = "";
        $user = Auth::user();
        $obj = Users::where('id',$user->id)->first();      
        $filePath = public_path('/images/admin/dp/'); 
        if ($request->hasFile('admindp')) {
            $uploadedDP = $request->file('admindp');
            $uploadedDP->move($filePath, $uploadedDP->getClientOriginalName());
            $obj->profile_img = $request->file('admindp')->getClientOriginalName();
        }
        if($obj->save()) {
            return $obj->id;
        }
        return $return;
    }

    /**
    * Render the list page for supplier
    *
    * @return View
    */
    public function getSupplierList() {
        return view('admin.suppliers.index')->with('active', 'suppliers')->with('sub_active', 'supplier_list');
    }

    /**
    * Listing of the suppliers
    *
    * @return Datatables json format
    */

    public function getSuppliers()
    {
        $users = User::query()->whereHas("roles", function($q){ $q->where("name", SELLER); })->with("roles")->orderBy('id', 'DESC');
        return \ DataTables::of($users)->editColumn('status', 'admin.suppliers.datatables.status_column')->addColumn('actions', 'admin.suppliers.datatables.action_buttons ')->addColumn('setting', 'admin.suppliers.datatables.setting_buttons ')->rawColumns(['actions','status','setting'])->addIndexColumn()->addColumn('role', function ($setting) {
              return $setting->roles[0]->name;
          })->make(true);
    }


    /**
    * Function to delete the seller.
    */
    public function supplierDelete(Request $request){
      if($request->has('id')){

        $supplier_id = $request->id;
        $supplier_in_product = Product::where('seller_id',$supplier_id)->get();
        if($supplier_in_product->isEmpty()){

          User::find($supplier_id)->delete();
          //return response()->json(['message'=>trans('message.product_deleted_successfully'),'product_id'=>$product_id],200);
          return response()->json(['message'=>trans('message.seller_deleted_successfully')],200);
        }
        else{
          //echo "Product exists in an Auction. Cannot be deleted.";
          return response()->json(['message'=>trans('message.seller_exists_product')],200);
        }
      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_seller')],200);
      }
    }


    /**
     * Changes the status of supplier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */

    public function postChangeStatus(Request $request)
    {
    
        $response = User::where('id',$request->id)->update(['status'=>$request->status]);
        
        if($response)
        {
            $request->session()->flash('message.level','success');
            $request->session()->flash('message.content',trans('message.user_status_changed_successfully'));
            return response()->json(['message'=>trans('message.user_status_changed_successfully'),'status'=>1],200);
        }
        else
        {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_updated_user_status'));
            return response()->json(['message'=>trans('message.error_updated_user_status'),'status'=>0],200);            
        }
    }

    /**
     * Add/Edit form for supplier
     *
     * @param  \Illuminate\Http\Request  $request
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
        $user = User::find($id);              
      }
      else
      {
         $user = new User;   
      }

      $roles = Role::where('name', 'SELLER')->pluck('name','id')->toArray();;
      return view('admin.suppliers.add', ['roles'=>$roles,'user'=>$user])->with('active', 'suppliers')->with('sub_active', 'add_supplier');
    }

    /**
     * Add/Udpate the supplier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */

    public function postCreate(SellerRequest $request)
    {
      $message = '';
      $confirmation_code = str_random(30);  
      if ($request->has('user_id') && $request->get('user_id')) {
            $user = User::find($request->get('user_id'));
            $userData = $request->except('_token', 'user_id','email');
            $user->fill($userData);
            $user = $user->save();
            $message = trans('message.seller_updated_successfully');
      }
      else{
        $userData = $request->except('_token', 'user_id');
        $userData['confirmation_code'] = $confirmation_code;
        $userData['status'] = '1';
        $userData['password'] = bcrypt('seller01');
        $user = User::create($userData);
        $role_r = Role::where('name', '=', 'SELLER')->firstOrFail();  
        if($role_r)
            $user->assignRole($role_r); 

        $message = trans('message.seller_created_successfully');
      }
     
      if($user){
          $request->session()->flash('message.level','success');
          $request->session()->flash('message.content',$message);
          return response()->json(['message'=> $message],200);         
      }
      else
        {
          $request->session()->flash('message.level','danger');
          $request->session()->flash('message.content',trans('message.error_created_seller'));
          return response()->json(['message'=>trans('message.error_created_seller')],500);            
        }
    }


    public function BackuppostCreate(UserRequest $request)
    {
      $confirmation_code = str_random(30);  
      $userData = $request->except('_token', 'user_id');
      $userData['confirmation_code'] = $confirmation_code;
      $userData['status'] = '1';
      $userData['password'] = bcrypt('seller01');
      $user = User::create($userData);
      //$user->sendEmailVerificationNotification();

      // $reset_token = bcrypt(strtolower(str_random(64)));
      //$reset_token = Hash::make(Str::random(40));//hash_hmac('sha256', Str::random(40), $this->hashKey);
      //$reset_token = $this->hasher->make($reset_token)

    //  $reset_token = app('auth.password.broker')->createToken($user);

      /*\DB::table('password_resets')->insert([
          'email' => $user->email,
          'token' => $reset_token,
          'created_at' => Carbon::now(),
      ]);*/

    //  Mail::to($user->email)->send(new PasswordCreate($reset_token));

      //$roles = $request['roles']; //Retrieving the roles field
     // if (isset($roles)) {
         $role_r = Role::where('name', '=', 'SELLER')->firstOrFail();  
         if($role_r)
              $user->assignRole($role_r); 
    //  }     

      // $data = ['confirmation_code' => $confirmation_code,'email'=>$userData['email']];
      // Mail::send('emails.verify', $data, function($message) use ($data){
      //   $message->to($data['email'])->subject('Verify your email address');
      // });  

      
      return redirect()->route('users')
            ->with('flash_message',
             'User successfully added.');
    }


    /**
    * Render the user listing page.
    *
    * @return \Illuminate\Http\Response
    */
    public function getUserList() {
      $filter_status ="";
        return view('admin.users.index')->with('active', 'users')->with('sub_active', 'user_list');
    }

    /**
    * Return the datatable for use listing.
    *
    * @return \Illuminate\Http\Response
    */

    public function getUsers(Request $request)
    {
        // print_r($request->get('filter_status'));
        // die();
        $users = User::query();
        $profile_status = $request->get('filter_status');
        // print_r($profile_status);
        // die();
        if($profile_status == 0 || $profile_status == 1){
         // $profile_status = $request->get('filter_status');
          $users = $users->where("is_complete",$profile_status);
        }
        elseif($profile_status == '')
        {
          $users = $users->where("is_complete",'<=',1);
        }
        // $filter_status ="";
        $users = $users->whereHas("roles", function($q){  
          $q->where("name", USER)
          ->orWhere("name", ADMIN);  
          })->with("roles")->orderBy('id', 'DESC');
        return \ DataTables::of($users)->editColumn('status', 'admin.users.datatables.status_column')->addColumn('actions', 'admin.users.datatables.action_buttons ')->addColumn('setting', 'admin.users.datatables.setting_buttons')->addColumn('profile_status', 'admin.users.datatables.profile_status')->addColumn('resend', 'admin.users.datatables.resend_buttons')->rawColumns(['actions','status','setting','resend','profile_status'])->addIndexColumn()->addColumn('role', function ($setting) {
              return $setting->roles[0]->name;
          })->make(true);
    }

    /**
     * Add/Edit form page for user
     *
     * @param  \Illuminate\Http\Request  $id
     * @return \Illuminate\Http\Response
    */

    public function getUserProfile($id=null)
    {
      if($id)
      {
        try{
            $id = encrypt_decrypt('decrypt',$id);
        } catch (DecryptException $e) {
            abort(404);
            exit;
        }
        $user = User::find($id);              
      }
      else
      {
         $user = new User;   
      }

      $roles = Role::where('name', SELLER)->pluck('name','id')->toArray();;
      return view('admin.users.edit_profile', ['roles'=>$roles,'user'=>$user, 'active' => 'users','sub_active'=>'user_list']);
    }

    /**
     * Add/Update the user and send the verification email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */

    public function postUpdateProfile(UserRequest $request)
    {
      $message = '';  
      if ($request->has('user_id') && $request->get('user_id')) {
            $user = User::find($request->get('user_id'));
            $userData = $request->except('_token', 'user_id','email');
            $userData['dob']  = change_date_format_new($userData['dob']);
            $user->fill($userData);
            $user = $user->save();
            $message = trans('message.user_updated_successfully');
      }
      
      if($user){
          $request->session()->flash('message.level','success');
          $request->session()->flash('message.content',$message);
          return response()->json(['message'=> $message],200);         
      }
      else
        {
          $request->session()->flash('message.level','danger');
          $request->session()->flash('message.content',trans('message.error_updated_user'));
          return response()->json(['message'=>trans('message.error_updated_user')],500);            
        }
    }


    /**
    * Display a Profile Detail of user
    * Paramter Id 
    * @return \Illuminate\Http\Response
    */
    public function getUserProfileDetail($id=null,Request $request)
    {
  
      try{
          $id = encrypt_decrypt('decrypt',$id);
        }catch (DecryptException $e) {
          abort(404);
          exit;
      }
      //$user = User::findOrfail($id);
      $user = User::with('userBid')->findOrfail($id);

      $data =  $user->getUserDetail();
      if ($request->ajax()) {
        return view('admin.users.user_package_table', ['data' => $data])->render();  
      }
    //  print_r($data);
      return view('admin.users.profile_detail', ['user'=>$user, 'active' => 'users','data'=>$data,'sub_active'=>'user_list']);
    }


    /* Delete Method to soft delete the data */
      /* Delete Method to soft delete the data */
    public function deleteById(Request $request)
     {

        // emergency and specialst pending
        if ($request->has('id') && $request->has('model'))
          {

            try{
                $id = encrypt_decrypt('decrypt',$request->get('id'));
            } catch (DecryptException $e) {
                 $request->session()->flash('message.level','danger');
                 $request->session()->flash('message.content','Some error while delete record.');
                 exit;
            }

            $model='';
            $value=$request->input('model');
            $type=$request->input('type');
            switch ($value) {
              case 'Attribute':
                  $model = \App\Models\Attribute::find($id)->delete();
                  break;
              case 'UserPlan':
                  if(\App\Models\UserPlan::where('id', $id)->exists()){
                    $model = 2;
                    $request->session()->flash('message.level','danger');
                    $request->session()->flash('message.content',trans('message.record_deleted_successfully'));
                  }
                  else {
                     $model = \App\Models\UserPlan::find($id)->delete();
                     $request->session()->flash('message.level','success');
                     $request->session()->flash('message.content',trans('message.record_deleted_successfully'));
                  }
                  break;
              case 'User':
                 // $model = \App\Models\ManageableField::find($id)->delete();
                  break;
              default:
                  $model = 0;
                  break;
              }
              if($model){
                  return response()->json(['message'=>trans('message.record_deleted_successfully'),'status'=>1],200);
              }
              else{
                  $request->session()->flash('message.level','danger');
                  $request->session()->flash('message.content',trans('message.error_while_delete_record'));
                  return response()->json(['message'=>trans('message.error_while_delete_record'),'status'=>0],200);
              }

        }
        else {
            $request->session()->flash('message.level','danger');
            $request->session()->flash('message.content',trans('message.error_while_delete_record'));
            return response()->json(['message'=>'','status'=>0],200);
        }
     }


      /**
    * Render the user listing page.
    *
    * @return \Illuminate\Http\Response
    */

      /*
    public function getUserRankingList() {
        return view('admin.user_rank_list.index')->with('active', 'users')->with('sub_active', 'user_rank_list');
    }*/


     /**
     * Render view for listing of the product.
     * Parameter : Request
     * @param Request $request
     * @return \Illuminate\Http\View
     * @throws \Throwable
     */

    public function getUserRankingList(Request $request,User $user){
        $active = '';
        $bidData = UserBidHistory::get()->groupBy('user_id');
        $topBidderData = [];
        $totalAuction= [];
        $check_auction = [];
        foreach ($bidData as $key => $value) {
         $topBidderData[$key]= count($value);
         foreach ($value as $val) {
          $totalAuction[$key][]= $val->auction_id;
         }
        }
        foreach ($totalAuction as $key=>$val) {
          $totalAuctionData[$key]= count(array_unique($val));
        }
        
        arsort($topBidderData);
        //echo "<pre> topBidderData = "; print_r($topBidderData);
        $auctionWinner = [];
        $auctionWinnerData = AuctionWinner::get()->groupBy('user_id');
        foreach ($auctionWinnerData as $key => $value) {
         $auctionWinner[$key]= count($value);

        }
        arsort($auctionWinner);
        //echo "<pre> auctionWinner = "; print_r($auctionWinner);

        
        $userLossAuctionData = [];
        $userLossAuction = UserLossAuction::get()->groupBy('user_id');
        foreach ($userLossAuction as $key => $value) {
         $userLossAuctionData[$key]= count($value);

        }
        arsort($userLossAuctionData);
        //echo "<pre> userLossAuctionData = "; print_r($userLossAuctionData);
        
        // echo $userLossAuctionData[7];
        // echo $auctionWinner[7];
        // echo $topBidderData[7];
      //  die;
       
        
            $user = $user->newQuery()->whereHas("roles", function($q){  
                    $q->where("name", USER);
                    })->with("roles");


           $users = $user->orderBy('login_count', 'desc')->get();
            return view('admin.user_rank_list.userlist',compact('active','topBidderData','userLossAuctionData','auctionWinner','totalAuctionData'))->with('users', $users)->with('active', 'users')->with('sub_active', 'user_rank_list');
        

    }

    /**
    * Return the datatable for use listing.
    *
    * @return \Illuminate\Http\Response
    */

    public function getUserRankings()
    {
        $users = User::query()->whereHas("roles", function($q){  
          $q->where("name", USER)
          ->orWhere("name", ADMIN);  
          })->with("roles")->orderBy('login_count', 'DESC');
        return \ DataTables::of($users)->addColumn('setting', 'admin.user_rank_list.datatables.setting_buttons')->rawColumns(['setting'])->addIndexColumn()->addColumn('role', function ($setting) {
              return $setting->roles[0]->name;
          })->make(true);
    }


    /**
    * Function to delete the user.
    */
    public function userDelete(Request $request){
    
      if($request->has('id')){

        $user_id = $request->id;
        $user_detail = User::findOrFail($user_id);
        
        if(empty($user_detail->email_verified_at)){
          if($user_detail->status == '0')
          {
            User::find($user_id)->delete();
            return response()->json(['message'=>trans('message.user_deleted_successfully')],200);
          }
          else
          {
            return response()->json(['message'=>trans('message.user_active_error')],200);
          }
        }
        else{
          return response()->json(['message'=>trans('message.user_verified_error')],200);
        }
      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_package')],200);
      }
    }


}
