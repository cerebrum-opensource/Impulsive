<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Exports\DeactiveExport;
use App\Exports\AuctionProductExport;
use App\User;
use App\Models\Auction;
use App\Models\AuctionQueue;
use App\Models\UserBidHistory;
use App\Models\AuctionAutomateAgent;
use App\Models\Setting;
use Spatie\Permission\Models\Role;
use Auth;
use App\Http\Requests\Auction as AuctionRequest; 
use Carbon\Carbon;
use Config;
use Excel;
use Validator;



class AuctionController extends ApiController{
   


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
        $this->middleware(function ($request, $next) {
            if(Auth::user()->status != User::ACTIVE)
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
    public function getAuctionList() {
        return view('admin.auctions.index');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getAuctions()
    { 
      $model = Auction::with('product');
      return \ DataTables::eloquent($model)->editColumn('live_auction', 'admin.auctions.datatables.live_auction_column')->editColumn('auction_type', 'admin.auctions.datatables.auction_type_column')->addColumn('setting', 'admin.auctions.datatables.setting_buttons ')->rawColumns(['setting','live_auction','auction_type'])->addIndexColumn()->make(true);
    }



     /**
     * Render view for add/edit auction.
     * Parameter : id
     * @param $id
     * @return \Illuminate\Http\View
     * @throws \Throwable
     */


    public function getCreateEdit($id=null)
    {
      if($id)
      {
        $id = encrypt_decrypt('decrypt',$id);
        $auction = Auction::with('product')->find($id);    
         return view('admin.auctions.edit', ['auction'=>$auction, 'active' => 'auctions'])->with('active', 'auctions')->with('sub_active', 'add_auction');          
      }
      else
      {
         $auction = new Auction;   
      }
      return view('admin.auctions.add', ['auction'=>$auction, 'active' => 'auctions'])->with('active', 'auctions')->with('sub_active', 'add_auction');
    }

    // Edit Queue Auction

    public function getQueueAuctionEdit($id=null)
    {
      if($id)
      {
        $id = encrypt_decrypt('decrypt',$id);
        $auction = Auction::with('product')->find($id);              
      }
      
      return view('admin.auctions.edit', ['auction'=>$auction, 'active' => 'auctions'])->with('active', 'auctions')->with('sub_active', 'add_auction');
    }


    /**
     * Save Auction
     * @param ProductRequest $request
     * @return Json response with success and failure message
     */

    public function postCreate(Request $request)
    {   
        $setting = Setting::first();
        $timezone = $this->timezone;
        $nowDate = \Carbon\Carbon::now()->timezone($timezone)->format('Y-m-d H:i:s');  

        /* Validation rule for counter based on condition and value get from form */

        $counter = 'required';
        $sec = 'bail|nullable|integer|min:1|max:59';
        $min = 'bail|nullable|integer|min:1|max:59';
        $hour_valid = 'bail|nullable|integer|min:1|max:24';

       if(($request->has('hours') && $request->get('hours') !='') && (($request->has('seconds') && $request->get('seconds') !='') || ($request->has('minutes') && $request->get('minutes') !=''))){
          $sec = 'bail|nullable|integer|min:1|max:59';
          $min = 'bail|nullable|integer|min:1|max:59';
          $hour_valid = 'bail|integer|min:1|max:23';
          $counter = 'nullable';
        }

        if(($request->has('minutes') && $request->get('minutes') !='') && (($request->has('hours') && $request->get('hours') !='') || ($request->has('seconds') && $request->get('seconds') !=''))){
          $sec = 'bail|nullable|integer|min:1|max:59';
          $hour_valid = 'bail|nullable|integer|min:1|max:23';
          $min = 'bail|nullable|integer|min:1|max:59';
          $counter = 'nullable';
        }

        if(($request->has('seconds') && $request->get('seconds') !='') && (($request->has('hours') && $request->get('hours') !='') || ($request->has('minutes') && $request->get('minutes') !=''))){
          $hour_valid = 'bail|nullable|integer|min:1|max:23';
          $min = 'bail|nullable|min:1|integer|max:59';
          $sec = 'bail|nullable|min:1|integer|max:59';
          $counter = 'nullable';
        }
        if(($request->has('hours') && $request->get('hours') =='24') || ($request->has('minutes') && $request->get('minutes') =='60')){
          $sec = 'bail|nullable|min:1|integer|max:59';
          $counter = 'nullable';
        }


        if(($request->has('hours') && $request->get('hours') !='') && (($request->has('seconds') && $request->get('seconds') =='') || ($request->has('minutes') && $request->get('minutes') ==''))){
          $counter = 'nullable';
        }

        if(($request->has('minutes') && $request->get('minutes') !='') && (($request->has('seconds') && $request->get('seconds') =='') || ($request->has('hours') && $request->get('hours') ==''))){
          $counter = 'nullable';
        }
        if(($request->has('seconds') && $request->get('seconds') !='') && (($request->has('minutes') && $request->get('minutes') =='') || ($request->has('hours') && $request->get('hours') ==''))){
          $counter = 'nullable';
        }

        /* End for condition for validations for counter*/

        if($request->has('live_auction') && $request->get('live_auction') == 'on'){
        //  echo $hour_valid;
            $validator = Validator::make($request->all(),[
            'product_id' => 'required|exists:products,id,deleted_at,NULL,status,1',
            'final_countdown' => 'required|min:1|max:60|integer',
            'discount' => 'nullable|numeric|min:1|max:99.99',
            'detail' => 'nullable',
            'hours' => $hour_valid,
            'minutes' => $min,
            'seconds' => $sec,
            'counter' => $counter
          ]);
        }
        else {
          if($request->has('start_time') && $request->get('start_time')){
              $date = $request->get('start_time');
              $dateN = explode(',',$date);
              $hours = trim($dateN[1]);
              $hour = explode(' ',$hours);
              $time = $hour[0].':00:00';
              $newDate = $dateN[0].' '.$time;
              $request->request->add(['start_time'=>$newDate]);

          }

          $validator = Validator::make($request->all(),[
            'product_id' => 'required|exists:products,id,deleted_at,NULL,status,1',
            'final_countdown' => 'bail|required|min:1|max:60|integer',
            'start_time' => "nullable|date_format:Y-m-d H:i:s|after:".$nowDate,
            'detail' => 'nullable',
            'discount' => 'nullable|numeric|min:1|max:99.99',
            'hours' => $hour_valid,
            'minutes' => $min,
            'seconds' => $sec,
            'counter' => $counter
         ]);
        }

        if($validator->fails()){
          return response()->json(['errors'=>$validator->errors()],422);
        }

        $message = '';
        $auctionData = $request->except('_token', 'auction_id','product','hours','seconds','minutes','counter');
        $duration = '';

        /* Condition to create a duration based on value from form */ 
        if($request->has('hours') && $request->get('hours')) {
            $duration.= $request->get('hours').':';
        } 
        else {
            $duration.= '00:';
        }
        if($request->has('minutes') && $request->get('minutes')) {
            $duration.= $request->get('minutes').':';
        } 
        else {
            $duration.= '00:';
        }
        if($request->has('seconds') && $request->get('seconds')) {
            $duration.= $request->get('seconds');
        } 
        else {
            $duration.= '00';
        }

       /* End for duration condition*/
       $nowTime = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
      
       $durationTime = explode(':',$duration);
       $startBreakTime = \Carbon\Carbon::now()->format('Y-m-d').' '.$setting->break_start_time; 
      $endBreakTime = \Carbon\Carbon::now()->format('Y-m-d').' '.$setting->break_end_time; 
      $ts1 = strtotime($endBreakTime);
      $ts2 = strtotime($startBreakTime);
      $diffInBreakTime = abs($ts1 - $ts2) / 3600;

     // $durationTime[0] = $durationTime[0]+$diffInBreakTime;

       // create the string for add the counter time to end date/time
       $str = '';
       $str.='+'.$durationTime[0].' hour';
       $str.='+'.$durationTime[1].' minutes';
       $str.='+'.$durationTime[2].' seconds';

          
        // is there is setting record used that else value from constant file is used
        if($setting){
            $maximum_live_auction = $setting->maximum_live_auction;
        }
        else {
            $maximum_live_auction = MAXIMUM_LIVE_AUCTION;
        }
      
      if($request->has('live_auction') && $request->get('live_auction') == 'on'){
        $auctionData['auction_type'] = Auction::LIVE;
        $auctionData['live_auction'] = Auction::LIVE_AUCTION_YES;
        $auctionData['start_time'] = $nowTime;
        $auctionData['duration'] = $duration;
      }
      else{
          $auctionData['live_auction'] = Auction::LIVE_AUCTION_NO;
          if($request->has('start_time') && $request->get('start_time') !=''){

            $timezone = $this->timezone;
            $timestamp = $request->get('start_time');
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $timezone);
            $date->setTimezone('UTC');
            $nowTime = $date->format('Y-m-d H:i:s');
            $auctionData['start_time'] = $nowTime;
            $auctionData['auction_type'] = Auction::PLANNED;
          }
          else {
            $auctionData['auction_type'] = Auction::QUEUE;
            if(AuctionQueue::where('status',Auction::ACTIVE)->count() >= $maximum_live_auction){
              $count = Auction::where('auction_type',Auction::QUEUE)->where('status',Auction::ACTIVE)->count();
              $auctionData['priority'] = $count+1;
            }
            
          }
          $auctionData['duration'] = $duration;
      }

      if ($request->has('auction_id') && $request->get('auction_id')) {
            $auction = Auction::find($request->get('auction_id'));
            $auction->fill($auctionData);
            $auction = $auction->save();
            $message = trans('message.auction_updated_successfully');
      }
      else{      

        

        

        $auction = Auction::create($auctionData);
        $auctionQueueData['auction_id'] = $auction->id;
        $auctionQueueData['product_id'] = $auction->product_id;
        $auctionQueueData['bid_count'] = INITIALIZE_BID_COUNT;
        $auctionQueueData['bid_price'] = INITIALIZE_BID_PRICE;
        $auctionQueueData['discount'] = $auction->discount;
        $auctionQueueData['status'] = AuctionQueue::ACTIVE;
        $auctionQueueData['auction_type'] = $auction->auction_type;
        $auctionQueueData['duration'] = $auction->duration;
        $auctionQueueData['duration_count'] = h_m_s_to_seconds($auction->duration);
        $auctionQueueData['final_countdown'] = $auction->final_countdown;
        
        // condition to add the auction in queue to run in frontend
        if($request->has('live_auction') && $request->get('live_auction') == 'on'){
        /*  $auctionQueueData['end_time'] = date('Y-m-d H:i:s',strtotime($str,strtotime($nowTime)));
          $final_countdownTime = strtotime($auctionQueueData['end_time']);
          $time = $final_countdownTime - ($auction->final_countdown);
          $date = date("Y-m-d H:i:s", $time);
          $auctionQueueData['final_countdown_start_time'] = $date; */
          $AuctionQueue = AuctionQueue::create($auctionQueueData);
          Auction::where('id',$auction->id)->update(['status'=>Auction::CURRENTLY_RUNNING]);
        }
        else {
          if($request->has('start_time') && $request->get('start_time') !=''){
           
          }
          else {
            if(AuctionQueue::where('status',Auction::ACTIVE)->count() < $maximum_live_auction){
           /*   $auctionQueueData['end_time'] = date('Y-m-d H:i:s',strtotime($str,strtotime($nowTime)));
              $final_countdownTime = strtotime($auctionQueueData['end_time']);
              $time = $final_countdownTime - ($auction->final_countdown);
              $date = date("Y-m-d H:i:s", $time);
              $auctionQueueData['final_countdown_start_time'] = $date;*/
              $AuctionQueue = AuctionQueue::create($auctionQueueData);
              Auction::where('id',$auction->id)->update(['status'=>Auction::CURRENTLY_RUNNING]);
            }
          }
        }
        $message = trans('message.auction_created_successfully');
      }
     
      if($auction){
        $request->session()->flash('message.level','success');
        $request->session()->flash('message.content',$message);
        return response()->json(['message'=> $message],200);         
      }
      else
      {
        $request->session()->flash('message.level','danger');
        $request->session()->flash('message.content',trans('message.error_created_auction'));
        return response()->json(['message'=>trans('message.error_created_auction')],500);            
      }
    }



    /**
    * Display a listing of the live auctions.
    *
    * @return \Illuminate\Http\Response
    */
    public function getLiveAuctionList() {
        return view('admin.live_auctions.index')->with('active', 'auctions')->with('sub_active', 'live_auctions');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getLiveAuctions()
    { 
      $model = AuctionQueue::with('product');

      return \ DataTables::eloquent($model)->addIndexColumn()->make(true);
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getDashBoard(Request $request)
    {
      $liveAuctions = AuctionQueue::with('product')->where('status',AuctionQueue::ACTIVE)->take(TOTAL_RECORDS_DISPLAY)->get();
      $expiredAuctions = AuctionQueue::with('product')->where('status',AuctionQueue::NOT_ACTIVE)->orderBy('updated_at','desc')->take(TOTAL_RECORDS_DISPLAY)->get();
      $plannedAuctions = Auction::with('product')->where('auction_type',Auction::PLANNED)->where('status',Auction::ACTIVE)->take(TOTAL_RECORDS_DISPLAY)->get();
      $queueAuctions = Auction::with('product')->where('auction_type',Auction::QUEUE)->where('status',Auction::ACTIVE)->take(TOTAL_RECORDS_DISPLAY)->get();

      if ($request->ajax()) {
        $liveAuctions = AuctionQueue::with('product')->where('status',AuctionQueue::ACTIVE)->take(TOTAL_RECORDS_DISPLAY)->get();
        return view('admin.auctions.live_auction_table', ['liveAuctions' => $liveAuctions])->render();  
      }
      return view('admin.auctions.dashboard', ['liveAuctions'=>$liveAuctions, 'expiredAuctions'=>$expiredAuctions, 'plannedAuctions'=>$plannedAuctions, 'queueAuctions'=>$queueAuctions, 'active' => 'auctions'])->with('active', 'auctions')->with('sub_active', 'auction_dashboard');
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getQueueAuctions() {

        $queueAuctions = Auction::with('product')->where('auction_type',Auction::QUEUE)->where('status',Auction::ACTIVE)->orderBy('priority', 'ASC')->get();
        return view('admin.auctions.queue_list', ['queueAuctions'=>$queueAuctions, 'active' => 'auctions'])->with('active', 'auctions')->with('sub_active', 'queue_auctions');;
    }

     public function queueAuctionDelete(Request $request){
      if($request->has('id')){

        $queue_auction_id = $request->id;
        //$product_in_auction = Auction::where('product_id',$product_id)->get();
        $auction_is_live = Auction::with('product')->where('id',$queue_auction_id)->where('auction_type',Auction::LIVE)->get();
        if($auction_is_live->isEmpty()){

          Auction::find($queue_auction_id)->delete();
          //Product::find($product_id)->delete();
          //return response()->json(['message'=>trans('message.product_deleted_successfully'),'product_id'=>$product_id],200);
          return response()->json(['message'=>trans('message.auction_deleted_successfully')],200);
        }
        else{
          
          return response()->json(['message'=>trans('message.auction_exists_live')],200);
        }
      }else{
        return response()->json(['message'=>trans('message.error_in_fetching_auction')],200);
      }
    }
    

    /**
     * Update Queue Auction Priority
     * @param ProductRequest $request
     * @return Json response with success and failure message
     */

    public function postUpdatePriority(Request $request)
    {
     // print_r($request->all());
        $data = [];
        if($request->has('auction_priority_ids') && $request->get('auction_priority_ids')){
          $ids = $request->get('auction_priority_ids');
          foreach ($ids as $key => $id) {
              Auction::find($id)->update(['priority'=>$key+1]);
              $data[$id] = $key+1;
          }
        }

        if(empty($data)){
            return response()->json(['data'=>$data],500);  
        }
        return response()->json(['data'=>$data],200);     
    }



    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getActiveAuctionList() {
        return view('admin.auctions.active_list')->with('active', 'auctions')->with('sub_active', 'active_auctions');;
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getActiveAuctions()
    { 
      $model = AuctionQueue::with('product','auction')->where('status',Auction::ACTIVE);
      return \ DataTables::eloquent($model)->addColumn('start_time', function($setting) {
                    if($setting->auction && $setting->auction->start_time)
                      return $setting->auction->start_time;
                    else
                      return 'N/A';
                  })
        ->editColumn('live_auction', 'admin.auctions.datatables.active.active_live_auction_column')->editColumn('auction_type', 'admin.auctions.datatables.active.active_auction_type_column')->addColumn('setting', 'admin.auctions.datatables.active.active_setting_buttons ')->rawColumns(['setting','live_auction','auction_type'])->addIndexColumn()->make(true);
    }


      /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getPlannedAuctionList() {
        return view('admin.auctions.planned_list')->with('active', 'auctions')->with('sub_active', 'planned_auctions');;
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getPlannedAuctions()
    { 
      $model = Auction::with('product')->where('auction_type',Auction::PLANNED)->where('status',Auction::ACTIVE);
      return \ DataTables::eloquent($model)->editColumn('auction_type', 'admin.auctions.datatables.planned.active_auction_type_column')->addColumn('setting', 'admin.auctions.datatables.planned.active_setting_buttons ')->rawColumns(['setting','live_auction','auction_type'])->addIndexColumn()->make(true);
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getDeActiveAuctionList() {
        return view('admin.auctions.deactive_list')->with('active', 'auctions')->with('sub_active', 'deactive_auctions');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function getDeActiveAuctions()
    { 

      $model = AuctionQueue::with('product','auction','auctionWinner')->where('status',AuctionQueue::NOT_ACTIVE)->orderBy('updated_at','desc');
      return \ DataTables::eloquent($model)->addColumn('product_price', function($setting) {
                  // print_r($setting->auctionWinner[0]->user);
                  //   die();
                    if($setting->product->product_price)
                      return CURRENCY_ICON.' '.$setting->product->product_price;
                    else
                      return ' ';
                  })
                  ->addColumn('winner', function($setting) {
                    foreach($setting->auctionWinner as $auctionWinner)
                    {
                      if($auctionWinner){
                        if($auctionWinner->user){
                          if($auctionWinner->user->username)
                          {
                            return $auctionWinner->user->username;
                          }
                          else{
                            return " ";
                          }
                        }
                        else
                        {
                          return " ";
                        }
                      }
                      else{
                        return " ";
                      }
                    }
                  })
                  ->editColumn('bid_price', function($setting) {
                
                      return CURRENCY_ICON.' '.$setting->bid_price;
                  })->editColumn('auction_type', 'admin.auctions.datatables.active.active_auction_type_column')->addColumn('setting', 'admin.auctions.datatables.active.active_setting_buttons ')->rawColumns(['setting','live_auction','auction_type',])->addIndexColumn()->make(true);
    }


    /**
      Export Deactive auctions
    */

    public function deactiveAuctionExport(Request $request){

      $deactiveAuctions = AuctionQueue::with('product','auction','auctionWinner')->where('status',AuctionQueue::NOT_ACTIVE)->orderBy('id', 'desc')->get();
      $fileName =  str_random(28).'.xlsx';
      $data = Excel::download(new DeactiveExport($deactiveAuctions), $fileName);
        return $data;

    }
     /**
     * Render view for auction detail
     * Parameter : id
     * @param $id
     * @return \Illuminate\Http\View
     * @throws \Throwable
     */


    public function getAuctionDetail($id)
    {
      try{
          $id = encrypt_decrypt('decrypt',$id);
      } catch (DecryptException $e) {
          abort(404);
          exit;
      }
      $auction = Auction::with('product','auctionQueue')->findOrFail($id);    
      $bidHistory = [];

      if($auction && count($auction->auctionQueue)){
          $id = $auction->auctionQueue[0]->id;
          $bidHistory = UserBidHistory::with('user')->where('auction_id',$id)->orderBy('id', 'desc')->paginate(PAGINATION_COUNT_10);
      }   
    
      $automateAgent = AuctionAutomateAgent::where('auction_queue_id',$id)->get();
      return view('admin.auctions.view', ['auction'=>$auction, 'bidHistory' =>$bidHistory, 'automateAgent' => $automateAgent,'active' => 'auctions','sub_active'=>'auction_dashboard']);
    }

    /**
      Export auction product detail page.
    */

    public function auctionProductExport(Request $request){
    
      try{
          $id = encrypt_decrypt('decrypt',$request->route('id'));
      } catch (DecryptException $e) {
          abort(404);
          exit;
      }
      $auction = Auction::with('product','auctionQueue')->findOrFail($id);
      $bidHistory = [];
      if($auction && count($auction->auctionQueue)){
          $id = $auction->auctionQueue[0]->id;
          $bidHistory = UserBidHistory::with('user')->where('auction_id',$id)->orderBy('id', 'desc')->paginate(PAGINATION_COUNT_10);
      }
      
      $fileName =  str_random(28).'.xlsx';
     
      $data = Excel::download(new AuctionProductExport($auction,$bidHistory), $fileName);
        return $data;
    
    }

}
