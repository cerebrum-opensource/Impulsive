<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\{UserBid, UserPlan, UserTransaction, UserBonusBid,UserBuyList, UserShippingAddress};
use Klarna;
use Log;


class KlarnaPaymentStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    private $klarna_api;
    protected $signature = 'klarn_payment_status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update the status of the klarna payment every hour.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {   

        /* Initilize the Klarna */
        $klarna_conf = \Config::get('klarna');
        $apiEndpoint = Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL;
        $this->klarna_api = Klarna\Rest\Transport\Connector::create(
            $klarna_conf['merchantId'],
            $klarna_conf['sharedSecret'],
            $apiEndpoint
        );
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
   
    public function handle()
    {
       // $klarna_conf = \Config::get('klarna');

        Log::info('klarna schedular is running');
        $connector = $this->klarna_api;


         $transactions = UserTransaction::where('status',UserTransaction::PENDING)->whereNull('user_buy_list_id')->where('payment_method',UserTransaction::KLARNA)->get();
         $productTransactions = UserTransaction::where('status',UserTransaction::PENDING)->whereNull('package_id')->whereNull('plan_id')->where('payment_method',UserTransaction::KLARNA)->get();
         foreach ($transactions as $transaction) 
            {
             # code...
                Log::info($transaction->transaction_id);
                
                try {
                    if($transaction->transaction_id)
                        {
                            $orderID = $transaction->transaction_id;

                            $checkout = new Klarna\Rest\OrderManagement\Order($connector,$orderID);
                            $order =  $checkout->fetch($checkout);
                            $UserTransaction = UserTransaction::with('package')->where('transaction_id',$orderID)->first();
                            if($order['status'] == 'AUTHORIZED'){
                               
                                $checkout1 = new Klarna\Rest\OrderManagement\Order($connector,$orderID);
                                $orderCapture = [
                                      "captured_amount" => round($UserTransaction->amount*100,2),
                                      "description" => "Amount capture from impulsive",
                                ];
                                $capture =  $checkout1->createCapture($orderCapture);
                                UserTransaction::successPackageTransaction($orderID);
                                
                                /*
                                UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::ACTIVE]);
                                UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::ACTIVE]);
                                $userbid['user_id'] = $UserTransaction->user_id;
                                if(UserBid::where('user_id',$UserTransaction->user_id)->count()){
                                    $bidAdd = $UserTransaction->package->bid+$UserTransaction->package->bonus;
                                    UserBid::where('user_id',$UserTransaction->user_id)->increment('bid_count', $bidAdd);
                                }
                                else {
                                    $userbid['bid_count'] = $UserTransaction->package->bid;
                                    UserBid::create($userbid);
                                } */

                                $invoice = UserTransaction::with('package','user')->findOrfail($UserTransaction->id);
                                $invoice->sendPackageInvoice();
                                UserTransaction::where('transaction_id',$orderID)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::KLARNA,'payer_id'=>$order['klarna_reference']]);
            
                            }

                            if ($order['status'] == 'checkout_incomplete') {
                               /* UserTransaction::where('transaction_id',$orderID)->update(['status'=>UserTransaction::FAILED]);
                                $UserTransaction = UserTransaction::where('transaction_id',$orderID)->first();
                                UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::FAILED]);
                                UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::FAILED]);*/

                                UserTransaction::failedPackageTransaction($orderID);
                
                            }

                            if ($order['status'] == 'CAPTURED') {
                                //$UserTransaction = UserTransaction::with('package')->where('transaction_id',$orderID)->first();
                                
                                UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::ACTIVE]);
                                UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::ACTIVE]);
                                $userbid['user_id'] = $UserTransaction->user_id;
                                if(UserBid::where('user_id',$UserTransaction->user_id)->count()){
                                    $bidAdd = $UserTransaction->package->bid+$UserTransaction->package->bonus;
                                    UserBid::where('user_id',$UserTransaction->user_id)->increment('bid_count', $bidAdd);
                                }
                                else {
                                    $userbid['bid_count'] = $UserTransaction->package->bid;
                                    UserBid::create($userbid);
                                }
                                // send invoice mail
                                $invoice = UserTransaction::with('package','user')->findOrfail($UserTransaction->id);
                                $invoice->sendPackageInvoice();
                                UserTransaction::where('transaction_id',$orderID)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::KLARNA,'payer_id'=>$order['klarna_reference']]);
                            }     
                        }
                    else{
                        UserTransaction::where('id',$transaction->id)->update(['status'=>UserTransaction::FAILED]);
                        $UserTransaction = UserTransaction::where('id',$transaction->id)->first();
                        UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::FAILED]);
                        UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::FAILED]);
                        Log::info('payment failed due to paypal not initialize');
                            //Log::info('payer not exist');

                    }

                }  catch (Klarna\Rest\Transport\Exception\ConnectorException $ex) {
                  /*  UserTransaction::where('transaction_id',$orderID)->update(['status'=>UserTransaction::FAILED]);
                    $UserTransaction = UserTransaction::where('transaction_id',$orderID)->first();
                    UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::FAILED]);
                    UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::FAILED]);*/
                    UserTransaction::failedPackageTransaction($orderID);
                    Log::info($ex);

                }

            }

         foreach ($productTransactions as $transaction) 
            {
             # code...
                Log::info($transaction->transaction_id);
                
                try {
                    if($transaction->transaction_id)
                        {
                            $orderID = $transaction->transaction_id;

                            $checkout = new Klarna\Rest\OrderManagement\Order($connector,$orderID);
                            $order =  $checkout->fetch($checkout);
                            $UserTransaction = UserTransaction::with('package')->where('transaction_id',$orderID)->first();
                            if($order['status'] == 'AUTHORIZED'){
                                
                                $checkout1 = new Klarna\Rest\OrderManagement\Order($connector,$orderID);
                                $orderCapture = [
                                      "captured_amount" => round($UserTransaction->amount*100,2),
                                      "description" => "Amount capture from impulsive",
                                ];
                                $capture =  $checkout1->createCapture($orderCapture);

                                if($order['shipping_address']){
                                    $shipping['user_id'] = $UserTransaction->user_id;
                                    $shipping['user_transaction_id'] = $UserTransaction->id;
                                    $shipping['email'] =  isset($order['shipping_address']['email']) ? $order['shipping_address']['email'] : '' ;
                                    $shipping['first_name'] =  isset($order['shipping_address']['given_name']) ? $order['shipping_address']['given_name'] : '';
                                    $shipping['last_name'] = isset($order['shipping_address']['family_name']) ? $order['shipping_address']['family_name'] : '' ;
                                    $shipping['payer_id'] = '';
                                    $shipping['shipping_address'] = json_encode($order['shipping_address']);
                                    UserShippingAddress::create($shipping);
                                }

                                UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::ACTIVE]);
                                UserTransaction::where('transaction_id',$orderID)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::KLARNA,'payer_id'=>$order['klarna_reference']]);

                                // send invoice mail
                                $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($UserTransaction->id);
                                $invoice->sendProductInvoice();
            
                            }

                            if ($order['status'] == 'checkout_incomplete') {
                                /*UserTransaction::where('transaction_id',$orderID)->update(['status'=>UserTransaction::FAILED]);
                                $UserTransaction = UserTransaction::where('transaction_id',$orderID)->first();
                                UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::EXPIRE]);*/
                                UserTransaction::failedProductTransaction($orderID);
                            }

                            if ($order['status'] == 'CAPTURED') {
                               // $UserTransaction = UserTransaction::with('package')->where('transaction_id',$orderID)->first();
                                UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::ACTIVE]);
                                UserTransaction::where('transaction_id',$orderID)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::KLARNA,'payer_id'=>$order['klarna_reference']]);

                                // send invoice mail
                                $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($UserTransaction->id);
                                $invoice->sendProductInvoice();
                            }     
                        }
                    else{
                        UserTransaction::where('id',$transaction->id)->update(['status'=>UserTransaction::FAILED]);
                        $UserTransaction = UserTransaction::where('id',$transaction->id)->first();
                        UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::EXPIRE]);
                        Log::info('payment failed due to paypal not initialize');
                            //Log::info('payer not exist');

                    }

                }catch (Klarna\Rest\Transport\Exception\ConnectorException $ex) {
                    UserTransaction::failedProductTransaction($orderID);
                    Log::info($ex);
                    /*UserTransaction::where('transaction_id',$orderID)->update(['status'=>UserTransaction::FAILED]);
                    $UserTransaction = UserTransaction::where('transaction_id',$orderID)->first();
                    UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::EXPIRE]);
                    */
                }

            }
    }
}
