<?php

namespace App\Http\Controllers\User;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Models\{UserEmail, Package, UserPlan, UserTransaction, FriendInvitation, AuctionQueue, Auction, UserWishlist, AuctionWinner, UserBonusBid, UserBuyList, Setting, Product, UserLossAuction, UserReferralCode,UserBid};
use App\Http\Requests\User\Profile as UserRequest; 
use App\Http\Requests\User\UpdateProfile as UpdateProfileRequest; 
use App\Http\Requests\User\InviteFriend as InviteFriendRequest; 
use App\Http\Requests\User\ReferralCode as ReferralCodeRequest; 
use Validator;
use Illuminate\Support\Facades\Hash;
use View;
use App\Mail\FriendInvite;
use App\Mail\SimpleTextEmail;
/* Paypal Integration */
/** All Paypal Details class **/
use PayPal\Api\{Amount, Details, Item, ItemList, Payer, Payment, PaymentExecution, RedirectUrls, Transaction};

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Carbon\Carbon;
use Redirect;
use Session;
use URL;
use Mail; 
use Klarna;
/* Sofort Integration */
use Sofort\SofortLib\Sofortueberweisung;

use DB;
use Log;


class UserController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $_api_context;
    private $klarna_api;
    private $softor_api;
    private $softorCred;
    private $klarnaCred;

    public function __construct()
    {
       // $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->status != '1')
            {
                Auth::logout();
                return redirect('/login');
            }
            return $next($request);
        });

        /* Middle for check the user profile is complete or not */
       /* $this->middleware(function ($request, $next) {
                if(Auth::user()->is_complete != '1')
                {
                    //Auth::logout();
                    return redirect(route('user_profile'));
                }

                return $next($request);
            },['except' => ['getUncompleteProfile','postCompleteProfile']]);*/

        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
        $this->timezone = user_time_zone();

        /* Initilize the Klarna */
        $klarna_conf = \Config::get('klarna');
        $apiEndpoint = Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL;
        $this->klarna_api = Klarna\Rest\Transport\Connector::create(
            $klarna_conf['merchantId'],
            $klarna_conf['sharedSecret'],
            $apiEndpoint
        );

         
         /* Initilize the Softor */
        $softor_conf = \Config::get('softor');
        $this->softorCred = $softor_conf;
        $this->klarnaCred = $klarna_conf;
        $configkey = $softor_conf['customer_id'].':'.$softor_conf['project_id'].':'.$softor_conf['key'];
       // echo $configkey = '185467:518559:8506c3941256c9d982727fd01d6015c1';
        $this->softor_api = new Sofortueberweisung($configkey);
    }

    /**
     * Show the get uncomplete profile page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUncompleteProfile()
    {
        $userData = Auth::user();
        $user = User::find($userData->id);
        return view('user.edit_profile', compact('user'));
        //return view('home');
    }


    /**
     * parameter :  UserRequest $request
     *
     * @return json with success and error
     */

    public function postCompleteProfile(UserRequest $request)
    {   
        $userData = Auth::user();
        $user = User::findOrFail(encrypt_decrypt('decrypt', $request->get('user_id')));
        if($userData->id != $user->id){
            abort(500);
        }

        $userData = $request->except('_token', 'user_id','email');
        $userData['is_complete'] = 1;
        $user->fill($userData);
        $user = $user->save();
        $message = trans('message.profile_completed_successfully');
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


    /**
     * Show the edit profile page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditProfile()
    {
        $userData = Auth::user();
        $user = User::with('primaryEmail')->find($userData->id);
        $timezone = $this->timezone;
        $liveAuctions = AuctionQueue::with('product','auction','bidHistory')->where('status',AuctionQueue::ACTIVE)->get();
        $queueAuctions = Auction::with('product')->where('auction_type',Auction::QUEUE)->where('status',Auction::ACTIVE)->orderBy('priority', 'ASC')->get();
        return view('user.profile', compact('user','timezone','liveAuctions','queueAuctions'));
        //return view('home');
    }


    /**
     * Show the edit profile page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getProfileDetail(Request $request)
    {
       // print_r($request->all());
        $userData = Auth::user();
        $user = User::with('userBid')->find($userData->id);

        
        if ($request->ajax()) {

            if($request->has('type') && $request->get('type') == 'bid'){
              $data =  $user->getUserDetail();
              $html = view('user.user_package_table', ['data' => $data])->render();  
            }
            if($request->has('type') && $request->get('type') == 'win_list'){
              $winList =  $user->getUserBuyList();
              $html = view('user.user_buy_winis_table', ['winList' => $winList])->render();  
              
            }
          return response()->json(['message' => trans('message.listing_found'), 'html' => $html], 200);  
        }
        $data =  $user->getUserDetail();
        $winList =  $user->getUserBuyList();
        $timezone = $this->timezone;
        $liveAuctions = AuctionQueue::with('product','auction','bidHistory')->where('status',AuctionQueue::ACTIVE)->get();
        $queueAuctions = Auction::with('product')->where('auction_type',Auction::QUEUE)->where('status',Auction::ACTIVE)->orderBy('priority', 'ASC')->get();
        return view('user.profile_detail', compact('user','data','winList','timezone','liveAuctions','queueAuctions'));
        //return view('home');
    }

    /**
     * parameter :  UpdateProfileRequest $request
     *
     * @return json with success and error
    */

    public function postProfileUpdate(UpdateProfileRequest $request)
    {   
        $userData = Auth::user();
        $user = User::findOrFail(encrypt_decrypt('decrypt', $request->get('user_id')));
        if($userData->id != $user->id){
            abort(500);
        }

        
        $userData = $request->except('_token', 'user_id','email');
        $userData['dob']  = change_date_format_new($userData['dob']);
        $user->fill($userData);
        $userSave = $user->save();

        $message = trans('message.profile_updated_successfully');
        if($userSave){
            if($user->email != $request->get('email')){
              $userid = encrypt_decrypt('decrypt', $request->get('user_id'));
              $userEmail['user_id'] = $userid;
              $userEmail['email'] = $request->get('email');
              UserEmail::where('user_id',$userid)->delete();
              UserEmail::create($userEmail);
              $user->sendNewEmailVerificationNotification();
            }
          $request->session()->flash('message.level','success');
          $request->session()->flash('message.content',$message);
          return response()->json(['message'=> $message],200);         
        }
      else
        {
          $request->session()->flash('message.level','danger');
          $request->session()->flash('message.content',trans('message.error_update_user'));
          return response()->json(['message'=>trans('message.error_update_user')],500);            
        }
    }

    /**
     * parameter : Request $request
     *  function to change password for user
     * @return json with success and error
    */


    public function changePassword(Request $request)
    {  
        $validator = Validator::make($request->all(),[
            'password_confirmation' => ['required'],
            'password' => ['required', 'string', 'min:8', 'max:16', 'confirmed',
                        'regex:/[a-z]/',      // must contain at least one lowercase letter
                        'regex:/[A-Z]/',      // must contain at least one uppercase letter
                        'regex:/[0-9]/',      // must contain at least one digit
                        'regex:/[@$!%*#?&]/'  // must contain at least one special character
                        ],  
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }

        $validator = Validator::make($request->all(),[
            'password' => 'confirmed'
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        
        $user = User::find(Auth::id())->update(['password'=>Hash::make($request->password)]);
        $request->session()->flash('message.level','success');
        $request->session()->flash('message.content',trans('message.password_changed_successfully'));
        return response()->json(['message'=>trans('message.password_changed_successfully')],200);
    }  


    /**
     * parameter : Request $request
     *  function to delete the user account
     * @return json with success and error
     */

    public function deleteAccount(Request $request)
    {  
        $validator = Validator::make($request->all(),[
            'user_id' => ['required']
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()],422);
        }
        $userdetail = User::find(Auth::id());
        $user = $userdetail->update(['status'=>'3']);
        $text = trans('message.account_delete_email_message');
        $subject = trans('message.account_delete_email_subject');
        Mail::to($userdetail->email)->send(new SimpleTextEmail($text,$subject));
        Auth::logout();
        $request->session()->flash('message.level','success');
        $request->session()->flash('message.content',trans('message.account_deleted_successfully'));
        return response()->json(['message'=>trans('message.account_deleted_successfully')],200);
    } 


    /**
     * Show the edit profile page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPackagePage()
    { 
        $userData = Auth::user();
        $packages = Package::where('status',Package::ACTIVE)->get();
        $user = User::find($userData->id);
        return view('user.package', compact('user','packages'));
        //return view('home');
    }


    /**
     * Load the tab in package page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response : Json
     */

    public function getloadTab(Request $request)
    {   
      if ($request->ajax()) {
        if ($request->has('tab') && $request->input('tab') == 2) {  
          $dataView['payment_method_tab'] = View::make('user.choose_payment_method')->render();
          $dataView['plan_id'] = View::make('user.choose_payment_method')->render();
          return response()->json(['html'=>$dataView],200);
        }
        if ($request->has('tab') && $request->input('tab') == 3) {
          $dataView['payment_method_tab'] = View::make('user.complete_payment')->render();
          return response()->json(['html'=>$dataView],200);
        }
          
      }
        
    }

  

    /**
     * Used to purchase the package
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response : Json
    */

     public function postPurchasepackage(Request $request)
    {   

      $validator = Validator::make($request->all(),[
            'user_id' => ['required'],
            'package_id' => ['required']
        ]);

      if($validator->fails()){
          return response()->json(['errors'=>$validator->errors()],422);
      }

     
      $planData = $request->except('_token','step_number');
      $packageDetail = Package::find($planData['package_id']);
      $planData['amount'] =  $packageDetail->price;
      $planData['tax'] =  round($packageDetail->price-get_tax_amount($packageDetail->price,TAX_PERCENT),2);
      $planData['status'] = UserPlan::DRAFT;
      $planData['type'] = UserPlan::PLAN;
      $planData['bid_count'] = $packageDetail->bid+$packageDetail->bonus;
      $response = UserPlan::create($planData);
      $planData['plan_id'] = $response->id;

      $setting = Setting::first();
      // is there is setting record used that else value from constant file is used
      if($setting){
          $bonus_bid_day_count = $setting->bonus_bid_day_count;
      }
      else {
          $bonus_bid_day_count = BONUS_BID_DAY_COUNT;
      }

      if($packageDetail->type == 'promotional' && $packageDetail->bonus){
          $pro['bid_count'] = $packageDetail->bonus;
          $pro['plan_id'] = $response->id;
          $pro['user_id'] =$request->get('user_id');
          $pro['status'] = UserBonusBid::DRAFT;
          $pro['expire_date'] = \Carbon\Carbon::now()->addDays($bonus_bid_day_count)->format('Y-m-d');
          UserBonusBid::create($pro);
      }
      unset($planData['type']);
      unset($planData['tax']);
      $trans = UserTransaction::create($planData);
      if($response && $trans)
      {
          $request->session()->flash('message.level','success');
        //  $request->session()->flash('message.content',trans('message.patient_detail_successfully'));
          return response()->json(['message'=>'','plan_id'=>$response->id,'transaction_id'=>$trans->id],200);
      }
      else
      {
          $request->session()->flash('message.level','danger');
          $request->session()->flash('message.content',trans('message.error_in_create_plan'));
          return response()->json(['message'=>'','plan_id'=>''],200);            
      }
    } 


     /**
     * Used to process the payment 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response : Json
     */


    public function postPayment(Request $request)
    {   
      $paypal_conf = \Config::get('paypal');
      $validator = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'transaction_id' => 'required',
            'plan_id' => 'required',
            'payment_method' => 'required|in:klarna,paypal,softor',
            'terms_conditions' => 'required',
        ]);

      if($validator->fails()){
          return response()->json(['errors'=>$validator->errors()],422);
      }

      $userPlan = UserPlan::with('package')->find($request->get('plan_id'));

      if($request->has('payment_method') && $request->get('payment_method') == 'paypal'){
        return $this->paypalPayment($request,$userPlan);
      }
      if($request->has('payment_method') && $request->get('payment_method') == 'klarna'){    
          return $this->klarnaPayment($request,$userPlan);
      }
      if($request->has('payment_method') && $request->get('payment_method') == 'softor'){    
          return $this->softorPayment($request,$userPlan);
      }

    } 


    /**
     * Show the Invite friend page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\View
     */
    public function getFriendInvitepage()
    { 
        $friendInvitation = new FriendInvitation;
        $userData = Auth::user();
        $user = User::find($userData->id);
        return view('user.invite_friend', compact('user','friendInvitation'));
        //return view('home');
    }

    /**
     * Save and send the friend invite.
     *SettingSetting
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\View
     */

    public function postFriendInvite(InviteFriendRequest $request){

        $userData = Auth::user();
        $user = User::findOrFail(encrypt_decrypt('decrypt', $request->get('user_id')));
        if($userData->id != $user->id){
            abort(500);
        }
        $friendInviteData = $request->except('_token');
        $token =  strtolower(str_random(64));
        $friendInviteData['token'] = $token;
        $friendInviteData['user_id'] = encrypt_decrypt('decrypt', $request->get('user_id'));
        $friendInvite = FriendInvitation::create($friendInviteData);
        $message = trans('message.friend_invited_successfully');
        if($friendInvite){
          $request->session()->flash('message.level','success');
          $request->session()->flash('message.content',$message);
          $text = $request->get('invite_text');
          $textInvite = trans('message.invite_friend_message');
          $subject = trans('label.invite_friend_email_subject');
          $invite_bids = Setting::firstOrFail();
          Mail::to($request->get('email'))->send(new FriendInvite($token,$user,$friendInviteData,$invite_bids));
        //  Mail::to($user->email)->send(new SimpleTextEmail($textInvite,$subject)); 
          return response()->json(['message'=> $message],200);         
        }
      else
        {
          $request->session()->flash('message.level','danger');
          $request->session()->flash('message.content',trans('message.error_created_invite'));
          return response()->json(['message'=>trans('message.error_created_invite')],500);            
        }

    }

    /**
     * Show the Dashboard page wiht live auctions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
    */

   public function dashboard(Request $request){
      $timezone = $this->timezone;
      $liveAuctions = AuctionQueue::with('product','auction','bidHistory')->where('status',AuctionQueue::ACTIVE)->get();
      $queueAuctions = Auction::with('product')->where('auction_type',Auction::QUEUE)->where('status',Auction::ACTIVE)->orderBy('priority', 'ASC')->get();
      $plannedAuctions = Auction::with('product')->where('auction_type',Auction::PLANNED)->where('status',Auction::ACTIVE)->get();
      return view('dashboard', compact('liveAuctions','timezone','queueAuctions','plannedAuctions'));
    }


    /**
     * Show the Get Recall List page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function getRecallList()
    {   
      $userData = Auth::user();
      $recallList = UserWishlist::with('active_auction')->join('auctions', 'user_wishlists.auction_id', '=', 'auctions.id')->where('type',UserWishlist::RECALL)->where('auctions.status',Auction::ACTIVE)->where('user_id',$userData->id)->get();
      $user = User::find($userData->id);
      return view('user.recall_list', compact('user','recallList'));
    }

    /*  
     * function for paypal payment for package
     * Params : Request $request, $userplan data
     * @return json
    */

    public function paypalPayment($request,$userPlan){
      $paypal_conf = \Config::get('paypal');

      if($userPlan){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $price = $userPlan->amount ? $userPlan->amount : 10;
        if($userPlan->package){
          $name = $userPlan->package->name;
          $desc = $userPlan->package->description;
        }
        $user = User::find($request->get('user_id'));

        $username = $user->username ? $user->username : '';
        //$tax = calculate_tax($price,TAX_PERCENT);

        $invoiceId = trans('label.invoice_ID').' : '. $request->get('transaction_id');
        $userID = trans('label.user_ID').' : '.$request->get('user_id').' '.trans('label.username_label').' : '.$username;
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($name) /** item name **/
            ->setCurrency($paypal_conf['currency'])
            ->setQuantity(1)
            ->setPrice($price); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency($paypal_conf['currency'])
            ->setTotal($price);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($desc)
            ->setCustom($invoiceId)
            ->setCustom($userID);
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('status')) /** Specify return URL **/
            ->setCancelUrl(URL::to('status'));
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {

            if (\Config::get('app.debug')) {
                \Session::put('error', trans('message.connection_timeout'));
                UserTransaction::where('id',$request->get('transaction_id'))->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
 
                UserPlan::where('id',$request->get('plan_id'))->update(['status'=>UserPlan::FAILED]);
                $URL = url('/packages?tab=complete&status=failed');
                return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
            } else { 
                \Session::put('error', trans('message.error_in_paypal'));
                UserTransaction::where('id',$request->get('transaction_id'))->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                
                UserPlan::where('id',$request->get('plan_id'))->update(['status'=>UserPlan::FAILED]);
                $URL = url('/packages?tab=complete&status=failed');
                return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
      }


      Session::put('paypal_payment_id', $payment->getId());
      UserTransaction::where('id',$request->get('transaction_id'))->update(['transaction_id'=>$payment->getId(),'payment_date' => \Carbon\Carbon::now()]);
      if (isset($redirect_url)) {
          $request->session()->flash('message.level','success');
          return response()->json(['message'=>'','type'=>'paypal','redirct_url'=>$redirect_url],200);

      }
      
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }


    /*  
     * function for klarna payment for package
     * Params : Request $request, $userplan data
     * @return json
    */

    public function klarnaPayment($request,$userPlan){
      
      $connector = $this->klarna_api;


      if($userPlan){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $price = $userPlan->amount ? $userPlan->amount : 10;
        if($userPlan->package){
          $name = $userPlan->package->name;
          $desc = $userPlan->package->description;
        }
        $user = User::find($request->get('user_id'));

        $username = $user->username ? $user->username : '';
        $order = [
          "purchase_country" => $this->klarnaCred['purchase_country'],
          "purchase_currency" => $this->klarnaCred['currency'],
          "locale" => $this->klarnaCred['locale'],
          "order_amount" => $userPlan->package->price*100,
          "order_tax_amount" => 0,
          "order_lines" => [
          [
              "type" => "digital",
              "reference" => $userPlan->id,
              "name" => $name,
              "quantity" => 1,
              "unit_price" => $userPlan->package->price*100,
              "total_amount" => $userPlan->package->price*100,
              "total_tax_amount" => 0,
              "tax_rate" => 0,
          ]
        ],
        "merchant_reference1" =>trans('label.user_ID').' : '.$request->get('user_id').' '.trans('label.username_label').' : '.$username,
        "merchant_reference2" =>trans('label.invoice_ID').' : '. $request->get('transaction_id'),
        "merchant_urls" => [
            "terms" => route('pages_route',['terms_and_conditions']),
            "cancellation_terms" => route('pages_route',['terms_and_conditions']),
            "checkout" => route("buy_package_list"),
            "confirmation" => route("klarna_status")."?sid={checkout.order.id}",
            "push" => route("klarna_status")."?sid={checkout.order.id}",
        ]
      ];
        

        try {
            $checkout = new Klarna\Rest\Checkout\Order($connector);
            $checkout->create($order);
            $orderId = $checkout->getId();
            UserTransaction::where('id',$request->get('transaction_id'))->update(['transaction_id'=>$orderId,'payment_date' => \Carbon\Carbon::now(),'payment_method'=>UserTransaction::KLARNA]);
            return response()->json(['message'=>'','type'=>'klarna','redirct_url'=>$checkout['html_snippet']],200);
            } catch (Klarna\Rest\Transport\Exception\ConnectorException $e) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', trans('message.connection_timeout'));
                    UserTransaction::where('id',$request->get('transaction_id'))->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
     
                    UserPlan::where('id',$request->get('plan_id'))->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/packages?tab=complete&status=failed');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                } else { 
                    \Session::put('error', trans('message.error_in_paypal'));
                    UserTransaction::where('id',$request->get('transaction_id'))->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    
                    UserPlan::where('id',$request->get('plan_id'))->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/packages?tab=complete&status=failed');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                }
            }
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }



    /*  
     * function for softor payment for package
     * Params : Request $request, $userplan data
     * @return json
    */

    public function softorPayment($request,$userPlan){
      
      $Sofortueberweisung = $this->softor_api;
      if($userPlan){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $price = $userPlan->amount ? $userPlan->amount : 10;
        if($userPlan->package){
          $name = $userPlan->package->name;
          $desc = $userPlan->package->description;
        }
        $user = User::find($request->get('user_id'));
        $username = $user->username ? $user->username : '';

        $userId[] = trans('label.user_ID').' : '.$request->get('user_id').' '.trans('label.username_label').' : '.$username;
        $userId[] = trans('label.invoice_ID').' : '. $request->get('transaction_id');
      $Sofortueberweisung->setUserVariable($userId);
      $Sofortueberweisung->setAmount($price);
      $Sofortueberweisung->setCurrencyCode($this->softorCred['currency']);
      $Sofortueberweisung->setReason($name, $desc);
      $Sofortueberweisung->setSuccessUrl(route("sofort_status").'?transaction=-TRANSACTION-', true); // i.e. http://my.shop/order/success
      $Sofortueberweisung->setAbortUrl(route("sofort_status_abort").'?transaction=-TRANSACTION-');
     // $Sofortueberweisung->setEmailCustomer('johnwich@yopmail.com');

      
  // $Sofortueberweisung->setSenderSepaAccount('SFRTDE20XXX', 'DE06000000000023456789', 'Max Mustermann');
  // $Sofortueberweisung->setSenderCountryCode('DE');
  // $Sofortueberweisung->setNotificationUrl('YOUR_NOTIFICATION_URL', 'loss,pending');
  // $Sofortueberweisung->setNotificationUrl('YOUR_NOTIFICATION_URL', 'loss');
  // $Sofortueberweisung->setNotificationUrl('YOUR_NOTIFICATION_URL', 'pending');
  // $Sofortueberweisung->setNotificationUrl('YOUR_NOTIFICATION_URL', 'received');
  // $Sofortueberweisung->setNotificationUrl('YOUR_NOTIFICATION_URL', 'refunded');
     $Sofortueberweisung->setNotificationUrl(route("sofort_status_notify"));
    //$Sofortueberweisung->setCustomerprotection(true);

     // send request on the sofort
    $Sofortueberweisung->sendRequest();

    if($Sofortueberweisung->isError()) {

     Log::info($Sofortueberweisung->getError());
        // SOFORT-API didn't accept the data
     if (\Config::get('app.debug')) 
     {
        \Session::put('error', trans('message.connection_timeout'));
        UserTransaction::where('id',$request->get('transaction_id'))->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);

        UserPlan::where('id',$request->get('plan_id'))->update(['status'=>UserPlan::FAILED]);
        $URL = url('/packages?tab=complete&status=failed');
        return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
      } else { 
        \Session::put('error', trans('message.error_in_paypal'));
        UserTransaction::where('id',$request->get('transaction_id'))->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
        
        UserPlan::where('id',$request->get('plan_id'))->update(['status'=>UserPlan::FAILED]);
        $URL = url('/packages?tab=complete&status=failed');
        return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
    }
    } else {
        // get unique transaction-ID useful for check payment status
        $transactionId = $Sofortueberweisung->getTransactionId();
        // buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
        $paymentUrl = $Sofortueberweisung->getPaymentUrl();
        UserTransaction::where('id',$request->get('transaction_id'))->update(['transaction_id'=>$transactionId,'payment_date' => \Carbon\Carbon::now(),'payment_method'=>UserTransaction::SOFORT]);
        return response()->json(['message'=>'','redirct_url'=>$paymentUrl],200);
       // header('Location: '.$paymentUrl);
    } 

      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }

     /**
     * Show the Get Win/Loss List page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function getWinList(Request $request)
    {   
      $userData = Auth::user();
     // $winLists = AuctionWinner::with('auction','product','auction_queue')->where('user_id',$userData->id)->paginate(1);
      $winLists = AuctionWinner::with('auction','product','auction_queue')->where('user_id',$userData->id)->get();
      $user = User::find($userData->id);

      $setting = Setting::first();
      if($setting){
          $instant_purchase_expire_day = $setting->instant_purchase_expire_day;
      }
      else {
          $instant_purchase_expire_day = INSTANT_PURCHASE_EXPIRE_DAY;
      }

      $lossBids  = $user->getLossBids($instant_purchase_expire_day);

      
      // is there is setting record used that else value from constant file is used
      if($setting){
          $discount_per_bid_price = $setting->discount_per_bid_price;
      }
      else {
          $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
      }

      $buyWinBid = UserBuyList::where('user_id',$userData->id)->where('status',UserBuyList::ACTIVE)->where('type',UserBuyList::WINNER)->pluck('auction_winner_id')->toArray();
      $buyInstantPurchase = UserBuyList::where('user_id',$userData->id)->where('status',UserBuyList::ACTIVE)->where('type',UserBuyList::INSTANT_PURCHASE)->pluck('auction_winner_id')->toArray();

      if ($request->ajax()) {
        if($request->has('type') && $request->get('type') == 'wins'){
          $html = view('user.auctions.win_auctions',compact('winLists','buyWinBid'))->render();
        }
        if($request->has('type') && $request->get('type') == 'lose'){
          $html = view('user.auctions.loss_auctions',compact('lossBids','buyInstantPurchase','discount_per_bid_price'))->render();
          
        }
        return response()->json(['message' => trans('message.listing_found'), 'html' => $html], 200);  
      }
      return view('user.win_loss_list', compact('user','winLists','lossBids','buyWinBid','buyInstantPurchase','discount_per_bid_price'));
    }



     /**
     * Used to process the payment 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response : Json
     */


    public function postProductPayment(Request $request)
    {   
      $paypal_conf = \Config::get('paypal');
      $userData = Auth::user();
      $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'amount' => 'required',
            'win_id' => 'required',
            'payment_method' => 'required|in:klarna,paypal,softor',
        ]);

      if($validator->fails()){
          return response()->json(['errors'=>$validator->errors()],422);
      }

      $user = User::findOrFail(encrypt_decrypt('decrypt', $request->get('user_id')));
      if($userData->id != $user->id){
          return response()->json(['message'=>trans('message.not_authorize')],403);
      }
      $type = encrypt_decrypt('decrypt', $request->get('data_type'));


      if($type == 'win'){
        $winner = AuctionWinner::with('auction','product','auction_queue')->findOrFail(encrypt_decrypt('decrypt', $request->get('win_id')));
      }
      else if($type == 'lose'){
        $winner = AuctionQueue::with('auction','product')->findOrFail(encrypt_decrypt('decrypt', $request->get('win_id')));
      }
      else {
        return response()->json(['message'=>trans('message.not_authorize')],403);
      }

      $setting = Setting::first();
      // is there is setting record used that else value from constant file is used
      if($setting){
          $discount_per_bid_price = $setting->discount_per_bid_price;
      }
      else {
          $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
      }

       
      $save = false; 

      if($winner){

      $amountPaid = $winner->bid_price;

      if($type == 'lose'){
        $userBidCount = User::getUserLossBidCount(encrypt_decrypt('decrypt', $request->get('user_id')),$winner->id);
        $amountPaid = ($winner->product->product_price) - ($userBidCount * $discount_per_bid_price) ;
      }

      $tax = calculate_tax($amountPaid,$winner->product->tax);

      DB::beginTransaction();
      $planData = $request->except('_token','step_number');
      $userBuy['user_id'] = $user->id;
      $userBuy['auction_id'] = $winner->auction_id;
      $userBuy['auction_winner_id'] = $winner->id;
      $userBuy['product_id'] = $winner->product_id;
      $userBuy['amount'] = $amountPaid-$tax;
      $userBuy['shipping_price'] = $winner->product->shipping_price;
      $userBuy['tax'] = $tax;
      $userBuy['type'] = UserBuyList::WINNER;
      if($type == 'lose'){
        $userBuy['type'] = UserBuyList::INSTANT_PURCHASE;
      }
      $userBuy['status'] = UserBuyList::DRAFT;
      $response = UserBuyList::create($userBuy);
        if($response){
            $transaction['user_buy_list_id'] = $response->id;
            $transaction['user_id'] = $user->id;
            $transaction['amount'] = $amountPaid + $winner->product->shipping_price;
            $trans = UserTransaction::create($transaction);
            if($trans){
                $save = true;
                DB::commit();
            }
        }

      
        if(($request->has('payment_method') && $request->get('payment_method') == 'paypal') &&  $save){
            return $this->paypalProductPayment($request,$winner,$trans->id,$response->id,$type,$user);
        }
        else if(($request->has('payment_method') && $request->get('payment_method') == 'klarna')  && $save){    
            return $this->klarnaProductPayment($request,$winner,$trans->id,$response->id,$type,$user);
        }
        else if(($request->has('payment_method') && $request->get('payment_method') == 'softor')  && $save){    
            return $this->softorProductPayment($request,$winner,$trans->id,$response->id,$type,$user);
        }
        else {
          DB::rollBack();
          return response()->json(['message'=>trans('message.issue_in_backend')],200);
        }
      }
      else{
        return response()->json(['message'=>trans('message.issue_in_backend')],200);
      }

      
    } 


    /**
     * Used to process the payment for paypal
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response : Json
     */


    public function paypalProductPayment($request,$winner,$transaction_id,$user_buy_list_id,$type,$user){
      $paypal_conf = \Config::get('paypal');
      $setting = Setting::first();
      // is there is setting record used that else value from constant file is used
      if($setting){
          $discount_per_bid_price = $setting->discount_per_bid_price;
      }
      else {
          $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
      }

      if($winner){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $product = $winner->product;
        $price = $winner->bid_price;
        if($type == 'lose'){
          //$price = ($winner->product->product_price) - ($winner->bid_count * DISCOUNT_PER_BID_PRICE) ;

          $userBidCount = User::getUserLossBidCount(encrypt_decrypt('decrypt', $request->get('user_id')),$winner->id);
          $price = ($winner->product->product_price) - ($userBidCount * $discount_per_bid_price) ;
        }
       // $discountPrice = ($price + $winner->product->shipping_price);
        $username = $user->username ? $user->username : '';
        $invoiceId = trans('label.invoice_ID').' : '. $transaction_id;
        $userID = trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username;
        $tax = calculate_tax($price,$winner->product->tax);
        $discountPrice = $price+$winner->product->shipping_price;
        
        if($product){
          $name = $product->product_title;
          $desc = $product->product_long_desc;
        }
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($name) /** item name **/
            ->setCurrency($paypal_conf['currency'])
            ->setQuantity(1)
            ->setSku($product->article_number)
            ->setPrice($price-$tax); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $shipping_price = 0;
        if($winner->product->shipping_price){
             $shipping_price = $winner->product->shipping_price;
            
        }
       
        $details = new Details();
             $details->setShipping($shipping_price)
             ->setTax($tax)
            ->setSubtotal($price-$tax);

        $amount = new Amount();
        $amount->setCurrency($paypal_conf['currency'])
            ->setTotal($discountPrice)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($desc)
            ->setCustom($invoiceId)
            ->setCustom($userID)
            ->setCustom($request->get('win_id'));
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('product_status')) /** Specify return URL **/
            ->setCancelUrl(URL::to('product_status'));
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        // * dd($payment->create($this->_api_context));exit; *
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {

            if (\Config::get('app.debug')) {
                \Session::put('error', trans('message.connection_timeout'));
                UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
 
                UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                $URL = url('/win_list');
                return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
            } else { 
                \Session::put('error', trans('message.error_in_paypal'));
                UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                
                UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                $URL = url('/win_list');
                return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
      }
      Session::put('product_paypal_payment_id', $payment->getId());
      UserTransaction::where('id',$transaction_id)->update(['transaction_id'=>$payment->getId(),'payment_date' => \Carbon\Carbon::now()]);
      if (isset($redirect_url)) {
          $request->session()->flash('message.level','success');
          return response()->json(['message'=>'','type'=>'paypal','redirct_url'=>$redirect_url],200);
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }


    /**
     * Used to process the klarna 
     *
     * @param  \Illuminate\Http\Request  $request,$winner,$transaction_id,$user_buy_list_id,$type,$user
     * @return \Illuminate\Http\Response : Json
     */


    public function klarnaProductPayment($request,$winner,$transaction_id,$user_buy_list_id,$type,$user){
       
       $connector = $this->klarna_api;
       
       $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if($setting){
            $discount_per_bid_price = $setting->discount_per_bid_price;
        }
        else {
            $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
        }

       if($winner){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $product = $winner->product;

        $price = $winner->bid_price;
        if($type == 'lose'){
         // $price = ($winner->product->product_price) - ($winner->bid_count * DISCOUNT_PER_BID_PRICE) ;

          $userBidCount = User::getUserLossBidCount(encrypt_decrypt('decrypt', $request->get('user_id')),$winner->id);
          $price = ($winner->product->product_price) - ($userBidCount * $discount_per_bid_price) ;
        }
        $username = $user->username ? $user->username : '';

        // calculate the tax based on site as well klarna gateway

        $tax = calculate_tax($price,$winner->product->tax);
        $totalAmoutTopaid = $price+$winner->product->shipping_price;
        $klarnaTax = klarna_tax($totalAmoutTopaid,$winner->product->tax);


        if($product){
          $name = $product->product_title;
          $desc = $product->product_long_desc;
        }

       // $klarnaTax = ($discountPrice*100) - ($discountPrice*100) * 10000 / (10000 + $winner->product->tax*100);

        $order = [
          "purchase_country" => $this->klarnaCred['purchase_country'],
          "purchase_currency" => $this->klarnaCred['currency'],
          "locale" => $this->klarnaCred['locale'],
          "order_amount" => $totalAmoutTopaid*100,
          "order_tax_amount" => $klarnaTax ? $klarnaTax : 0,
          "order_lines" => [
          [
              "type" => "physical",
              "reference" => $product->article_number,
              "name" => $name,
              "quantity" => 1,
              "unit_price" => $totalAmoutTopaid*100,
              "total_amount" => $totalAmoutTopaid*100,
              "total_tax_amount" => $klarnaTax ? $klarnaTax : 0,
              "tax_rate" => $winner->product->tax*100
          ]
        ],
        "merchant_reference1" =>trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username,
        "merchant_reference2" =>trans('label.invoice_ID').' : '. $transaction_id,
        "merchant_urls" => [
            "terms" => route('pages_route',['terms_and_conditions']),
            "cancellation_terms" => route('pages_route',['terms_and_conditions']),
            "checkout" => route('win_list'),
            "confirmation" => route("product_klarna_status")."?sid={checkout.order.id}",
            "push" =>  route("product_klarna_status")."?sid={checkout.order.id}",
        ]
      ];
        

        try {
            $checkout = new Klarna\Rest\Checkout\Order($connector);
            $checkout->create($order);
            $orderId = $checkout->getId();
            UserTransaction::where('id',$transaction_id)->update(['transaction_id'=>$orderId,'payment_date' => \Carbon\Carbon::now(),'payment_method'=>UserTransaction::KLARNA]);
            return response()->json(['message'=>'','type'=>'klarna','redirct_url'=>$checkout['html_snippet']],200);

            } catch (Klarna\Rest\Transport\Exception\ConnectorException $e) {
                    Log::info($e->getMessage());
                if (\Config::get('app.debug')) {
                    \Session::put('error', trans('message.connection_timeout'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                } else { 
                    \Session::put('error', trans('message.error_in_paypal'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                }
            }
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }

     /**
     * Used to process the softor 
     *
     * @param  \Illuminate\Http\Request  $request,$winner,$transaction_id,$user_buy_list_id,$type,$user
     * @return \Illuminate\Http\Response : Json
     */


    public function softorProductPayment($request,$winner,$transaction_id,$user_buy_list_id,$type,$user){
       
       $Sofortueberweisung = $this->softor_api;
        
        $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if($setting){
            $discount_per_bid_price = $setting->discount_per_bid_price;
        }
        else {
            $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
        }


       if($winner){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $product = $winner->product;

        $price = $winner->bid_price;
        if($type == 'lose'){
          $userBidCount = User::getUserLossBidCount(encrypt_decrypt('decrypt', $request->get('user_id')),$winner->id);
          $price = ($winner->product->product_price) - ($userBidCount * $discount_per_bid_price) ;
        }
        $username = $user->username ? $user->username : '';
        $invoiceId = trans('label.invoice_ID').' : '. $transaction_id;
        $userId = trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username;
        //$userId = trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username;
        //$userId[] = trans('label.invoice_ID').' : '. $transaction_id; 

        //$tax = calculate_tax($price,$winner->product->tax);
        $totalAmoutTopaid = round($price+$winner->product->shipping_price,2);
        //$klarnaTax = klarna_tax($totalAmoutTopaid,$winner->product->tax);
        if($product){
          $name = $product->product_title;
          $desc = $product->product_long_desc;
        }
        Log::info($totalAmoutTopaid);
        $Sofortueberweisung->setUserVariable($userId);
        $Sofortueberweisung->setAmount($totalAmoutTopaid);
        $Sofortueberweisung->setCurrencyCode($this->softorCred['currency']);
        $Sofortueberweisung->setReason('product', 'purchase');
        $Sofortueberweisung->setSuccessUrl(route("product_sofort_status").'?transaction=-TRANSACTION-', true); // i.e. http://my.shop/order/success
        $Sofortueberweisung->setAbortUrl(route("product_sofort_abort").'?transaction=-TRANSACTION-');
        $Sofortueberweisung->setNotificationUrl(route("product_sofort_status_notify"));

        $Sofortueberweisung->sendRequest();

        if($Sofortueberweisung->isError()) {
          Log::info($Sofortueberweisung->getError());

           \Session::put('error', $Sofortueberweisung->getError());
            if (\Config::get('app.debug')) {
                    \Session::put('error', $Sofortueberweisung->getError().trans('message.issue_in_backend'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                } else { 
                    \Session::put('error', trans('message.error_in_paypal'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                }
        } else {
            // get unique transaction-ID useful for check payment status
            $transactionId = $Sofortueberweisung->getTransactionId();
            // buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
            $paymentUrl = $Sofortueberweisung->getPaymentUrl();
            UserTransaction::where('id',$transaction_id)->update(['transaction_id'=>$transactionId,'payment_date' => \Carbon\Carbon::now(),'payment_method'=>UserTransaction::SOFORT]);
            return response()->json(['message'=>'','redirct_url'=>$paymentUrl],200);
           // header('Location: '.$paymentUrl);
    } 
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',$Sofortueberweisung->getError() ? $Sofortueberweisung->getError() : trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }

     /**
     * Show the Get Recall List page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function getWishList()
    {   
      $userData = Auth::user();
      $wishList = UserWishlist::where('type',UserWishlist::WISH_LIST)->where('user_id',$userData->id)->pluck('auction_id')->toArray();
      $liveAuctions = AuctionQueue::with('product','auction','bidHistory','wishList')->whereIn('auction_id', $wishList)->get();

     // print_r($liveAuctions);
      $user = User::find($userData->id);
      $timezone = $this->timezone;
      return view('user.wish_list', compact('user','wishList','liveAuctions','timezone'));
    }

    public function getVoucher()
    {   
      $userData = Auth::user();
      $user = User::find($userData->id);
      return view('user.redeem_voucher', compact('user'));
    }

    public function postVoucher(ReferralCodeRequest $request)
    {   

      $user_id = $request->user_id;
      $voucher_code = $request->voucher_code;
      // $voucher['user_id'] = $user_id;
      $referral_code_data = UserReferralCode::where('referral_code',$voucher_code)->where('status',0)->first();
      
        $bid_add = $referral_code_data->bid_count;
      
      $check = UserReferralCode::where('referral_code',$voucher_code)->update(array('user_id' => $user_id,'status' => 1));
      if($check)
      {
        $data_update = UserBid::where('user_id',$user_id)->increment('bid_count',$bid_add);
        $data_update = UserBid::where('user_id',$user_id)->increment('free_bid',$bid_add);
        $request->session()->flash('message.level','success');
        $request->session()->flash('message.content',trans('message.coupon_added'));
        return response()->json(['message'=>trans('message.coupon_added')],200);
      }else
      {
        $request->session()->flash('message.level','danger');
        $request->session()->flash('message.content',trans('message.coupon_not_added'));
        return response()->json(['message'=>trans('message.coupon_not_added')],200);
      }
    }




    /**
     * Used to process the payment of loss auction purchase product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response : Json
    */

    public function postLossProductPayment(Request $request)
    {   
      $paypal_conf = \Config::get('paypal');
      $userData = Auth::user();
      $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'amount' => 'required',
            'win_id' => 'required',
            'payment_method' => 'required|in:klarna,paypal,softor',
        ]);

      if($validator->fails()){
          return response()->json(['errors'=>$validator->errors()],422);
      }

      $checkLinkExpire = UserLossAuction::where('link', $request->get('link'))->where('status',1)->first();
      if($checkLinkExpire){
        return response()->json(['message'=>trans('message.loss_link_not_valid')],403);
      }

      $checkLinkPaymentStatus = UserBuyList::where('link_token', $request->get('link'))->where('status','!=',2)->first();
      if($checkLinkPaymentStatus){
        return response()->json(['message'=>trans('message.product_already_in_queque')],403);
      }

      $user = User::findOrFail(encrypt_decrypt('decrypt', $request->get('user_id')));
      if($userData->id != $user->id){
          return response()->json(['message'=>trans('message.not_authorize')],403);
      }

      //print_r($products);
       $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if($setting){
            $discount_per_bid_price = $setting->discount_per_bid_price;
        }
        else {
            $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
        }

      $type = encrypt_decrypt('decrypt', $request->get('data_type'));
      $winner = Product::findOrFail(encrypt_decrypt('decrypt', $request->get('win_id')));
      $link = UserLossAuction::where('link', $request->get('link'))->where('user_id',$user->id)->where('status',UserLossAuction::ACTIVE)->first();

      if($winner){

      $amountPaid = $winner->product_price-((float)$link->bid_count * (float)$discount_per_bid_price);

      if($amountPaid == 0){
        $amountPaid = 1;
      }
      $tax = calculate_tax($amountPaid,$winner->tax);

      DB::beginTransaction();
      $planData = $request->except('_token','step_number');
      $userBuy['user_id'] = $user->id;
      $userBuy['product_id'] = $winner->id;
      $userBuy['amount'] = $amountPaid-$tax;
      $userBuy['shipping_price'] = $winner->shipping_price;
      $userBuy['tax'] = $tax;
      $userBuy['link_token'] = $request->get('link');
      $userBuy['type'] = UserBuyList::PRODUCT_PURCHASE;
      $userBuy['status'] = UserBuyList::DRAFT;
      $response = UserBuyList::create($userBuy);
        if($response){
            $transaction['user_buy_list_id'] = $response->id;
            $transaction['user_id'] = $user->id;
            $transaction['amount'] = $amountPaid + $winner->shipping_price;
            $trans = UserTransaction::create($transaction);
            if($trans){
                $save = true;
                DB::commit();
            }
        }

       
        if(($request->has('payment_method') && $request->get('payment_method') == 'paypal') &&  $save){
            return $this->paypalLossProductPayment($request,$winner,$trans->id,$response->id,$link,$user);
        }
        else if(($request->has('payment_method') && $request->get('payment_method') == 'klarna')  && $save){    
            return $this->klarnaLossProductPayment($request,$winner,$trans->id,$response->id,$link,$user);
        }
        else if(($request->has('payment_method') && $request->get('payment_method') == 'softor')  && $save){    
            return $this->softorLossProductPayment($request,$winner,$trans->id,$response->id,$link,$user);
        }
        else {
          DB::rollBack();
          return response()->json(['message'=>trans('message.issue_in_backend')],200);
        }
      }
      else{
        return response()->json(['message'=>trans('message.issue_in_backend')],200);
      }

      
    } 




     /**
     * Used to process the payment for paypal for product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response : Json
     */


    public function paypalLossProductPayment($request,$winner,$transaction_id,$user_buy_list_id,$link,$user){
      $paypal_conf = \Config::get('paypal');
      

      if($winner){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $product = $winner;
        $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if($setting){
            $discount_per_bid_price = $setting->discount_per_bid_price;
        }
        else {
            $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
        }


        $price = $winner->product_price-((float)$link->bid_count * (float)$discount_per_bid_price);
        if($price == 0){
          $price = 1;
        }
       // $discountPrice = ($price + $winner->product->shipping_price);
        $username = $user->username ? $user->username : '';
        $invoiceId = trans('label.invoice_ID').' : '. $transaction_id;
        $userID = trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username;
        $tax = calculate_tax($price,$winner->tax);
        $discountPrice = $price+$winner->shipping_price;
        
        if($product){
          $name = $product->product_title;
          $desc = $product->product_long_desc;
        }
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($name) /** item name **/
            ->setCurrency($paypal_conf['currency'])
            ->setQuantity(1)
            ->setSku($product->article_number)
            ->setPrice($price-$tax); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $shipping_price = 0;
        if($winner->shipping_price){
          $shipping_price = $winner->shipping_price;
        }
       
        $details = new Details();
             $details->setShipping($shipping_price)
             ->setTax($tax)
            ->setSubtotal($price-$tax);

        $amount = new Amount();
        $amount->setCurrency($paypal_conf['currency'])
            ->setTotal($discountPrice)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($desc)
            ->setCustom($invoiceId)
            ->setCustom($userID)
            ->setCustom($request->get('win_id'));
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('product_status')) /** Specify return URL **/
            ->setCancelUrl(URL::to('product_status'));
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {

            if (\Config::get('app.debug')) {
                \Session::put('error', trans('message.connection_timeout'));
                UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
 
                UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
              //  $URL = url('/win_list');
                $URL = route('frontend_loss_auction',[$request->get('link')]);

                return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
            } else { 
                \Session::put('error', trans('message.error_in_paypal'));
                UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                
                UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
              //  $URL = url('/win_list');
                $URL = route('frontend_loss_auction',[$request->get('link')]);
                return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
      }
      Session::put('product_paypal_payment_id', $payment->getId());
      UserTransaction::where('id',$transaction_id)->update(['transaction_id'=>$payment->getId(),'payment_date' => \Carbon\Carbon::now()]);
      if (isset($redirect_url)) {
          $request->session()->flash('message.level','success');
          return response()->json(['message'=>'','type'=>'paypal','redirct_url'=>$redirect_url],200);
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }




    /**
     * Used to process the klarna 
     *
     * @param  \Illuminate\Http\Request  $request,$winner,$transaction_id,$user_buy_list_id,$type,$user
     * @return \Illuminate\Http\Response : Json
     */


    public function klarnaLossProductPayment($request,$winner,$transaction_id,$user_buy_list_id,$link,$user){
       
       $connector = $this->klarna_api;
       
       

       if($winner){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $product = $winner;

         $setting = Setting::first();
        // is there is setting record used that else value from constant file is used
        if($setting){
            $discount_per_bid_price = $setting->discount_per_bid_price;
        }
        else {
            $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
        }

        $price = $winner->product_price - ((float)$link->bid_count * (float)$discount_per_bid_price);

        if($price == 0){
          $price = 1;
        }
        $username = $user->username ? $user->username : '';

        // calculate the tax based on site as well klarna gateway

        $tax = calculate_tax($price,$winner->tax);
        $totalAmoutTopaid = $price+$winner->shipping_price;
        $klarnaTax = klarna_tax($totalAmoutTopaid,$winner->tax);


        if($product){
          $name = $product->product_title;
          $desc = $product->product_long_desc;
        }

       // $klarnaTax = ($discountPrice*100) - ($discountPrice*100) * 10000 / (10000 + $winner->product->tax*100);

        $order = [
          "purchase_country" => $this->klarnaCred['purchase_country'],
          "purchase_currency" => $this->klarnaCred['currency'],
          "locale" => $this->klarnaCred['locale'],
          "order_amount" => $totalAmoutTopaid*100,
          "order_tax_amount" => $klarnaTax ? $klarnaTax : 0,
          "order_lines" => [
          [
              "type" => "physical",
              "reference" => $product->article_number,
              "name" => $name,
              "quantity" => 1,
              "unit_price" => $totalAmoutTopaid*100,
              "total_amount" => $totalAmoutTopaid*100,
              "total_tax_amount" => $klarnaTax ? $klarnaTax : 0,
              "tax_rate" => $winner->tax*100
          ]
        ],
        "merchant_reference1" =>trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username,
        "merchant_reference2" =>trans('label.invoice_ID').' : '. $transaction_id,
        "merchant_urls" => [
            "terms" => route('pages_route',['terms_and_conditions']),
            "cancellation_terms" => route('pages_route',['terms_and_conditions']),
            "checkout" => route('win_list'),
            "confirmation" => route("product_klarna_status")."?sid={checkout.order.id}",
            "push" =>  route("product_klarna_status")."?sid={checkout.order.id}",
        ]
      ];
        

        try {
            $checkout = new Klarna\Rest\Checkout\Order($connector);
            $checkout->create($order);
            $orderId = $checkout->getId();
            UserTransaction::where('id',$transaction_id)->update(['transaction_id'=>$orderId,'payment_date' => \Carbon\Carbon::now(),'payment_method'=>UserTransaction::KLARNA]);
            return response()->json(['message'=>'','type'=>'klarna','redirct_url'=>$checkout['html_snippet']],200);

            } catch (Klarna\Rest\Transport\Exception\ConnectorException $e) {
                    Log::info($e->getMessage());
                if (\Config::get('app.debug')) {
                    \Session::put('error', trans('message.connection_timeout'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                } else { 
                    \Session::put('error', trans('message.error_in_paypal'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                }
            } 
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }





     /**
     * Used to process the softor 
     *
     * @param  \Illuminate\Http\Request  $request,$winner,$transaction_id,$user_buy_list_id,$type,$user
     * @return \Illuminate\Http\Response : Json
     */


    public function softorLossProductPayment($request,$winner,$transaction_id,$user_buy_list_id,$link,$user){
       
       $Sofortueberweisung = $this->softor_api;

       if($winner){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $product = $winner->product;
          $setting = Setting::first();
        if($setting){
            $discount_per_bid_price = $setting->discount_per_bid_price;
        }
        else {
            $discount_per_bid_price = DISCOUNT_PER_BID_PRICE;
        }

        $price = $winner->product_price - ((float)$link->bid_count * (float)$discount_per_bid_price);

         if($price == 0){
          $price = 1;
        }
        $username = $user->username ? $user->username : '';

        $userId[] = trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username;
        $userId[] = trans('label.invoice_ID').' : '. $transaction_id;

        $tax = calculate_tax($price,$winner->tax);
        $totalAmoutTopaid = round($price+$winner->shipping_price,2);
        $klarnaTax = klarna_tax($totalAmoutTopaid,$winner->tax);
        if($product){
          $name = $product->product_title;
          $desc = $product->product_long_desc;
        }
        Log::info($totalAmoutTopaid);
        $Sofortueberweisung->setUserVariable($userId);
        $Sofortueberweisung->setAmount($totalAmoutTopaid);
        $Sofortueberweisung->setCurrencyCode($this->softorCred['currency']);
        $Sofortueberweisung->setReason('product', 'purchase');
        $Sofortueberweisung->setSuccessUrl(route("product_sofort_status").'?transaction=-TRANSACTION-', true); // i.e. http://my.shop/order/success
        $Sofortueberweisung->setAbortUrl(route("product_sofort_abort").'?transaction=-TRANSACTION-');
        $Sofortueberweisung->setNotificationUrl(route("product_sofort_status_notify"));

        $Sofortueberweisung->sendRequest();

        if($Sofortueberweisung->isError()) {
          Log::info($Sofortueberweisung->getError());

           \Session::put('error', $Sofortueberweisung->getError());
            if (\Config::get('app.debug')) {
                    \Session::put('error', $Sofortueberweisung->getError().trans('message.issue_in_backend'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                } else { 
                    \Session::put('error', trans('message.error_in_paypal'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                }
        } else {
            // get unique transaction-ID useful for check payment status
            $transactionId = $Sofortueberweisung->getTransactionId();
            // buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
            $paymentUrl = $Sofortueberweisung->getPaymentUrl();
            UserTransaction::where('id',$transaction_id)->update(['transaction_id'=>$transactionId,'payment_date' => \Carbon\Carbon::now(),'payment_method'=>UserTransaction::SOFORT]);
            return response()->json(['message'=>'','redirct_url'=>$paymentUrl],200);
           // header('Location: '.$paymentUrl);
    } 
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',$Sofortueberweisung->getError() ? $Sofortueberweisung->getError() : trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }






    /////////////////////////////  direct product purchse changes start from here ////////////

    /**
    * Used to process the payment for paypal for direct purchase of product
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response : Json
     */


    public function paypalDirectProductPayment($request,$product,$transaction_id,$user_buy_list_id,$user){
      $paypal_conf = \Config::get('paypal');
      

      if($product){
        $name = 'Demo';
        $desc = 'Your transaction description';
       
        $price = $product->product_price;
        if($price == 0){
          $price = 1;
        }
       // $discountPrice = ($price + $winner->product->shipping_price);
        $username = $user->username ? $user->username : '';
        $invoiceId = trans('label.invoice_ID').' : '. $transaction_id;
        $userID = trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username;
        $tax = calculate_tax($price,$product->tax);


        $discountPrice = $price+$product->shipping_price;
        
        if($product){
          $name = $product->product_title;
          $desc = $product->product_long_desc;
        }
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($name) /** item name **/
            ->setCurrency($paypal_conf['currency'])
            ->setQuantity(1)
            ->setSku($product->article_number)
            ->setPrice($price-$tax); /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $shipping_price = 0;
        if($product->shipping_price){
          $shipping_price = $product->shipping_price;
        }
       
        $details = new Details();
             $details->setShipping($shipping_price)
             ->setTax($tax)
            ->setSubtotal($price-$tax);

        $amount = new Amount();
        $amount->setCurrency($paypal_conf['currency'])
            ->setTotal($discountPrice)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($desc)
            ->setCustom($invoiceId)
            ->setCustom($userID)
            ->setCustom($request->get('product_id'));
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('product_status')) /** Specify return URL **/
            ->setCancelUrl(URL::to('product_status'));
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {

            if (\Config::get('app.debug')) {
                \Session::put('error', trans('message.connection_timeout'));
                UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
 
                UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
              //  $URL = url('/win_list');
                $URL = route('frontend_loss_auction',[$request->get('link')]);

                return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
            } else { 
                \Session::put('error', trans('message.error_in_paypal'));
                UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                
                UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
              //  $URL = url('/win_list');
                $URL = route('frontend_loss_auction',[$request->get('link')]);
                return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
      }
      Session::put('product_paypal_payment_id', $payment->getId());
      UserTransaction::where('id',$transaction_id)->update(['transaction_id'=>$payment->getId(),'payment_date' => \Carbon\Carbon::now()]);
      if (isset($redirect_url)) {
          $request->session()->flash('message.level','success');
          return response()->json(['message'=>'','type'=>'paypal','redirct_url'=>$redirect_url],200);
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }

    /**
     * Used to process the klarna for direct purchase of product.
     *
     * @param  \Illuminate\Http\Request  $request,$winner,$transaction_id,$user_buy_list_id,$type,$user
     * @return \Illuminate\Http\Response : Json
     */



public function klarnaDirectProductPayment($request,$product,$transaction_id,$user_buy_list_id,$user){
       
       $connector = $this->klarna_api;
       if($product){
        $name = 'Demo';
        $desc = 'Your transaction description';
        $price = $product->product_price;
        if($price == 0){
          $price = 1;
        }
        // is there is setting record used that else value from constant file is used
       
        $username = $user->username ? $user->username : '';

        // calculate the tax based on site as well klarna gateway
        $discountPrice = $price+$product->shipping_price;
        $price_without_tax = $price-$product->tax;
        $invoiceId = trans('label.invoice_ID').' : '. $transaction_id;
        $userID = trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username;
        //$tax = calculate_tax($price,$product->tax);
        $tax = calculate_tax($price,$product->tax);
        $totalAmoutTopaid = $price+$product->shipping_price;
        $klarnaTax = klarna_tax($totalAmoutTopaid,$product->tax);


Log::info('Text'.$tax.','.$totalAmoutTopaid.','.$klarnaTax);

        if($product){
          $name = $product->product_title;
          $desc = $product->product_long_desc;
        }

       // $klarnaTax = ($discountPrice*100) - ($discountPrice*100) * 10000 / (10000 + $winner->product->tax*100);

        $order = [
          "purchase_country" => $this->klarnaCred['purchase_country'],
          "purchase_currency" => $this->klarnaCred['currency'],
          "locale" => $this->klarnaCred['locale'],
          "order_amount" => $totalAmoutTopaid*100,
          "order_tax_amount" => $klarnaTax ? $klarnaTax : 0,
          "order_lines" => [
          [
              "type" => "physical",
              "reference" => $product->article_number,
              "name" => $name,
              "quantity" => 1,
              "unit_price" => $totalAmoutTopaid*100,
              "total_amount" => $totalAmoutTopaid*100,
              "total_tax_amount" => $klarnaTax ? $klarnaTax : 0,
              "tax_rate" => $product->tax*100
          ]
        ],
        "merchant_reference1" =>trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username,
        "merchant_reference2" =>trans('label.invoice_ID').' : '. $transaction_id,
        "merchant_urls" => [
            "terms" => route('pages_route',['terms_and_conditions']),
            "cancellation_terms" => route('pages_route',['terms_and_conditions']),
            "checkout" => route('win_list'),
            "confirmation" => route("product_klarna_status")."?sid={checkout.order.id}",
            "push" =>  route("product_klarna_status")."?sid={checkout.order.id}",
        ]
      ];
        

        try {
            $checkout = new Klarna\Rest\Checkout\Order($connector);
            $checkout->create($order);
            $orderId = $checkout->getId();
            UserTransaction::where('id',$transaction_id)->update(['transaction_id'=>$orderId,'payment_date' => \Carbon\Carbon::now(),'payment_method'=>UserTransaction::KLARNA]);
            return response()->json(['message'=>'','type'=>'klarna','redirct_url'=>$checkout['html_snippet']],200);

            } catch (Klarna\Rest\Transport\Exception\ConnectorException $e) {
                    Log::info($e->getMessage());
                if (\Config::get('app.debug')) {
                    \Session::put('error', trans('message.connection_timeout'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                } else { 
                    \Session::put('error', trans('message.error_in_paypal'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                }
            } 
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }


 /**
     * Used to process the softor for direct purchase of product
     *
     * @param  \Illuminate\Http\Request  $request,$winner,$transaction_id,$user_buy_list_id,$type,$user
     * @return \Illuminate\Http\Response : Json
     */



public function softorDirectProductPayment($request,$product,$transaction_id,$user_buy_list_id,$user){
       
       $Sofortueberweisung = $this->softor_api;

       if($product){
        $name = 'Demo';
        $desc = 'Your transaction description';

        $price = $product->product_price;

         if($price == 0){
          $price = 1;
        }
        $username = $user->username ? $user->username : '';
        $invoiceId = trans('label.invoice_ID').' : '. $transaction_id;
        $userId = trans('label.user_ID').' : '.$user->id.' '.trans('label.username_label').' : '.$username;
        //$tax = calculate_tax($price,$product->tax);       
        $totalAmoutTopaid = round($price+$product->shipping_price,2);
        // round($price+$tax+$winner->shipping_price,2);
       
        if($product){
          $name = $product->product_title;
          $desc = $product->product_long_desc;
        }
        // Log::info($totalAmoutTopaid);
        $Sofortueberweisung->setUserVariable($userId);
        $Sofortueberweisung->setAmount($totalAmoutTopaid);
        $Sofortueberweisung->setCurrencyCode($this->softorCred['currency']);
        $Sofortueberweisung->setReason('product', 'purchase');
        $Sofortueberweisung->setSuccessUrl(route("product_sofort_status").'?transaction=-TRANSACTION-', true); // i.e. http://my.shop/order/success
        $Sofortueberweisung->setAbortUrl(route("product_sofort_abort").'?transaction=-TRANSACTION-');
        $Sofortueberweisung->setNotificationUrl(route("product_sofort_status_notify"));

        $Sofortueberweisung->sendRequest();

        if($Sofortueberweisung->isError()) {
          Log::info($Sofortueberweisung->getError());

           \Session::put('error', $Sofortueberweisung->getError());
            if (\Config::get('app.debug')) {
                    \Session::put('error', $Sofortueberweisung->getError().trans('message.issue_in_backend'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                } else { 
                    \Session::put('error', trans('message.error_in_paypal'));
                    UserTransaction::where('id',$transaction_id)->update(['status'=>UserTransaction::FAILED,'payment_date' => \Carbon\Carbon::now()]);
                    UserBuyList::where('id',$user_buy_list_id)->update(['status'=>UserPlan::FAILED]);
                    $URL = url('/win_list');
                    return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>$URL],200);
                }
        } else {
            // get unique transaction-ID useful for check payment status
            $transactionId = $Sofortueberweisung->getTransactionId();
            // buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
            $paymentUrl = $Sofortueberweisung->getPaymentUrl();
            UserTransaction::where('id',$transaction_id)->update(['transaction_id'=>$transactionId,'payment_date' => \Carbon\Carbon::now(),'payment_method'=>UserTransaction::SOFORT]);
            return response()->json(['message'=>'','redirct_url'=>$paymentUrl],200);
           // header('Location: '.$paymentUrl);
    } 
      }
      $request->session()->flash('message.level','danger');
      $request->session()->flash('message.content',$Sofortueberweisung->getError() ? $Sofortueberweisung->getError() : trans('message.error_in_paypal'));
      return response()->json(['message'=>'','plan_id'=>'','redirct_url'=>''],200);
    }

    /**
     * Used to process the payment of direct purchase product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response : Json
    */

    public function postDirectProductPayment(Request $request)
    {   

     // print_r($request->all());
      $paypal_conf = \Config::get('paypal');
      $userData = Auth::user();
      $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'amount' => 'required',
            'product_id' => 'required',
            'payment_method' => 'required|in:klarna,paypal,softor',
        ]);

      if($validator->fails()){
          return response()->json(['errors'=>$validator->errors()],422);
      }


      // die('here');
      
      $user = User::findOrFail(encrypt_decrypt('decrypt', $request->get('user_id')));
      if($userData->id != $user->id){
          return response()->json(['message'=>trans('message.not_authorize')],403);
      }
      $product = Product::findOrFail(encrypt_decrypt('decrypt', $request->get('product_id')));
     
      if($product){

      $amountPaid = $product->product_price;

      if($amountPaid == 0){
        $amountPaid = 1;
      }
      $tax = calculate_tax($amountPaid,$product->tax);


      DB::beginTransaction();
      $planData = $request->except('_token','step_number');
      $userBuy['user_id'] = $user->id;
      $userBuy['product_id'] = $product->id;
      $userBuy['amount'] = $amountPaid-$tax;
      $userBuy['shipping_price'] = $product->shipping_price;
      $userBuy['tax'] = $tax;
      $userBuy['type'] = UserBuyList::DIRECT_PRODUCT_PURCHASE;
      $userBuy['status'] = UserBuyList::DRAFT;

    //  print_r($userBuy);
     //// die();
      $response = UserBuyList::create($userBuy);
        if($response){
            $transaction['user_buy_list_id'] = $response->id;
            $transaction['user_id'] = $user->id;
            $transaction['amount'] = $amountPaid + $product->shipping_price;
            $trans = UserTransaction::create($transaction);
            if($trans){
                $save = true;
                DB::commit();
            }
        }

       
        if(($request->has('payment_method') && $request->get('payment_method') == 'paypal') &&  $save){
            return $this->paypalDirectProductPayment($request,$product,$trans->id,$response->id,$user);
        }
        else if(($request->has('payment_method') && $request->get('payment_method') == 'klarna')  && $save){    
            return $this->klarnaDirectProductPayment($request,$product,$trans->id,$response->id,$user);
        }
        else if(($request->has('payment_method') && $request->get('payment_method') == 'softor')  && $save){    
            return $this->softorDirectProductPayment($request,$product,$trans->id,$response->id,$user);
        }
        else {
          DB::rollBack();
          return response()->json(['message'=>trans('message.issue_in_backend')],200);
        }
      }
      else{
        return response()->json(['message'=>trans('message.issue_in_backend')],200);
      }

      
    } 

 
 
}
