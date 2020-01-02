<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

use Mail;
use App\Models\Setting;
use App\Models\ProductData;
use App\Models\UserReferralCode;
use App\Models\UserBidHistory;
use App\Models\AuctionQueue;
use App\Models\AuctionWinner;
use App\Models\UserBid;
use App\Models\Package;
use App\Models\Auction;
use App\User;
use App\Mail\BidWon;
use App\Models\AuctionAutomateAgent;
use DB;
use Session;
use Ekomi\Api;
use Ekomi\Request\PutOrder;
use \Ekomi\Request\GetFeedback;
use \Ekomi\Request\GetProductFeedback;
use \Ekomi\Request\GetVisitorfeedback;
use \Ekomi\Request\PutDialog;
use \Ekomi\Request\PutComment;
use \Ekomi\Request\GetDialog;
use \Ekomi\Request\GetSettings;
use \Ekomi\Request\GetRated;
use App\Mail\EkomiFeedback;

class DemoController extends Controller
{
    


    //
     public function UploadCsv()
    {
      error_reporting(0);

      //die('i am here');
      $file_n = public_path('/csv_file/Codes_Newsletter_referral.csv');
     // $file_n = "https://winimi.de/csv_file/Codes_Newsletter_Registration.csv";
      
      // dd($updated_time);
      $file = fopen($file_n, "r");
      $i = 0;
      $all_data = array();
      echo "<pre>";
      while ( ($filedata = fgetcsv($file, 200, ",")) !==FALSE) {
        $num = count($filedata );
        
        for ($c=0; $c < $num; $c++) {
          $all_data[$i]['referral_code'] = $filedata [$c];
          $all_data[$i]['bid_count'] = 10;
          $all_data[$i]['status'] = 0;
          $all_data[$i]['type'] = 0;
        }
        //print_r($all_data);
       // die();
        $i++;
      }
      fclose($file);
     // print_r($all_data);
      UserReferralCode::insert($all_data);
      //  ProductData::insertData($insertData);
      }



      public function getCURL()
      {
        //  echo "<pre>";
        //  error_reporting(E_ALL);
        //  $url = urlencode('https://in.pinterest.com/seasiainfotech/');

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'https://api.proxycrawl.com/?token=n_YaC4B4pJAd5F430uBM_g&url=' . $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // $response = curl_exec($ch);
        // curl_close($ch);

        // //var_dump($response);
        // print_r($response);
        // die();
         print_r("hello");
         //$apiData = file_get_contents("https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=81ycgv20xxssqx&redirect_uri=https://www.seasiainfotech.com/&state=987654321&scope=r_basicprofile");
         $curl_handle=curl_init();
          //curl_setopt($curl_handle, CURLOPT_URL,'https://api.linkedin.com/oauth/v2/authorization?response_type=code&client_id=81ycgv20xxssqx&redirect_uri=https://dev.example.com/auth/linkedin/callback&state=aRandomString');
           //curl_setopt($curl_handle, CURLOPT_URL,"http://api.linkedin.com/v1/people/~:(777060300,Parvesh,Kumar,linkedin.com/in/parvesh-kumar-81029b198)?format=json");
          //curl_setopt($curl_handle, CURLOPT_URL,"https://www.linkedin.com/in/parvesh-kumar-81029b198/?format=json");
          curl_setopt($curl_handle, CURLOPT_URL,"https://api.linkedin.com/v2/people/(id:777060300)");
          curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
          $query = curl_exec($curl_handle);
           print_r($query);
            if(curl_exec($curl_handle) === false)
            {
                echo 'Curl error: ' . curl_error($curl_handle);
            }
            else
            {
                echo 'Operation completed without any errors, you have the response';
            }
          curl_close($curl_handle);
        // $apiData = file_get_contents("https://www.linkedin.com/oauth/v2/authorization ");
        
         die();
         $setting = Setting::first();
         $package_amount = Package::where('id',1)->get();
         //foreach($package_amount as $packageAmount)
        // print_r($packageAmount->price);
          $userBid = UserBid::where('user_id',12)->first();
          print_r($userBid);
         die();
         //print_r($setting);
         
        $expireAuctions = AuctionQueue::with('bidHistory')->where('status',AuctionQueue::ACTIVE)->get();
       // $data = AuctionQueue::

        foreach ($expireAuctions as $expireAuction) {
            //Log::info($expireAuction->duration_count);
          
            if($expireAuction->duration_count > 0)
            {   
              //  Log::info('Auction is running');
               print_r("auction is running");
               print_r($expireAuction->duration_count);
            }   
            else{
               // echo "auction is over";
                if(count($expireAuction->bidHistory)){
                
        
                    $user_id = $expireAuction->bidHistory[0]->user->id;
                    print_r($user_id);
                }
                else {
                    $user_id = 0; 
                }

                $auctionWinner['auction_queue_id'] = $expireAuction->id;
                $auctionWinner['user_id'] = $user_id;
                $auctionWinner['auction_id'] = $expireAuction->auction_id;
                $auctionWinner['product_id'] = $expireAuction->product_id;
                $auctionWinner['bid_count'] = $expireAuction->bid_count;
                $auctionWinner['bid_price'] = $expireAuction->bid_price;
                $winner = AuctionWinner::where('auction_queue_id',$expireAuction->id)->count();
                print_r($winner);
               die();
                if($winner < 1){
                    AuctionWinner::create($auctionWinner);
                    $userData = User::find($user_id);
                    print_r($userData);
                    if($userData){
                       // echo "mail sent";
                        Mail::to($userData->email)->send(new BidWon($userData));
                       
                    }
                    
                      
                    
                }
                
                $expireAuction->auctionLoseUser($user_id);
                $agentSetups = AuctionAutomateAgent::where('auction_queue_id',$expireAuction->id)->where('status',AuctionAutomateAgent::ACTIVE)->where('remaining_bid','>',0)->get();
                if(count($agentSetups) > 0){
                    foreach ($agentSetups as $key => $agentSetup) {
                        UserBid::where('user_id',$user_id)->increment('bid_count', $agentSetup->remaining_bid);
                        AuctionAutomateAgent::find($agentSetup->id)->update([
                                    'remaining_bid' => 0,
                                    'total_bids' => 0,
                                    'status' => AuctionAutomateAgent::NOT_ACTIVE,
                                ]);
                    }
                }
                AuctionQueue::where('id',$expireAuction->id)->update(['status'=>AuctionQueue::NOT_ACTIVE]);
                Auction::where('id',$expireAuction->auction_id)->update(['status'=>Auction::ENDED]);
            }
            
        }
      }
      // $afterplay_link_status = Session::get('afterplay_status');
      // dd($afterplay_link_status);
   // }

}
