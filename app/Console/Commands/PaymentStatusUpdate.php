<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Models\{UserBid, UserPlan, UserTransaction, UserBonusBid,UserBuyList, UserShippingAddress};

/** All Paypal Details class **/
use PayPal\Api\{Amount, Details, Item, ItemList, Payer, Payment, PaymentExecution, RedirectUrls, Transaction};
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaymentStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    private $_api_context;
    protected $signature = 'payment_status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of the user payment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
         $transactions = UserTransaction::where('status',UserTransaction::PENDING)->whereNull('user_buy_list_id')->where('payment_method',UserTransaction::PAYPAL)->get();
         $productTransactions = UserTransaction::where('status',UserTransaction::PENDING)->whereNull('package_id')->whereNull('plan_id')->where('payment_method',UserTransaction::PAYPAL)->get();
         foreach ($transactions as $transaction) 
            {
             # code...
                Log::info($transaction->transaction_id);
                
                try {
                if($transaction->transaction_id)
                    {
                        $id = $transaction->transaction_id;
                        $UserTransaction = UserTransaction::with('package')->where('transaction_id',$id)->first();
                        $payment = Payment::get($id,$this->_api_context);
                        if($payment->payer){


                            $execution = new PaymentExecution();
                            $execution->setPayerId($payment->payer->payer_info->payer_id);
                            /**Execute the payment **/
                            $result = $payment->execute($execution, $this->_api_context);
                            if ($result->getState() == 'approved') {
                                
                                UserTransaction::successPackageTransaction($id);

                                /*
                                UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::ACTIVE]);
                                UserBonusBid::where('id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::ACTIVE]);
                                $userbid['user_id'] = $UserTransaction->user_id;
                                if(UserBid::where('user_id',$UserTransaction->user_id)->count()){
                                    $bidAdd = $UserTransaction->package->bid+$UserTransaction->package->bonus;
                                    UserBid::where('user_id',$UserTransaction->user_id)->increment('bid_count', $bidAdd);
                                }
                                else {
                                    $userbid['bid_count'] = $UserTransaction->package->bid;
                                    UserBid::create($userbid);
                                }
                                */

                                if($payment->getTransactions()){
                                    if($payment->getTransactions()[0]->getRelatedResources()){
                                        $saveId = @$payment->getTransactions()[0]->getRelatedResources()[0]->sale->id;
                                    }
                                    else{
                                        $saveId = $payment->payer->payer_info->payer_id; 
                                    }
                                }
                                else{
                                    $saveId = $payment->payer->payer_info->payer_id;
                                }

                                UserTransaction::where('transaction_id',$id)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::PAYPAL,'payer_id'=>$saveId]);

                                // send invoice mail
                                $invoice = UserTransaction::with('package','user')->findOrfail($UserTransaction->id);
                                $invoice->sendPackageInvoice();
                                Log::info('payment success');
                            }

                            else {

                                UserTransaction::failedPackageTransaction($id);
                                 /*UserTransaction::where('transaction_id',$id)->update(['status'=>UserTransaction::FAILED]);
                                 $UserTransaction = UserTransaction::where('transaction_id',$id)->first();
                                 UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::FAILED]);
                                 UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::FAILED]);*/
                                     Log::info('payment failed');
                            }

                             //Log::info('payer exist');
                            }
                            else {
                                UserTransaction::failedPackageTransaction($id);

                                 /*  UserTransaction::where('transaction_id',$id)->update(['status'=>UserTransaction::FAILED]);
                                    $UserTransaction = UserTransaction::where('transaction_id',$id)->first();
                                    UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::FAILED]);
                                    UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::FAILED]);*/
                                    Log::info('payment failed');
                                    Log::info('payer not exist');
                            }
                    }
                    else {
                        
                       // UserTransaction::failedPackageTransaction($id);

                        UserTransaction::where('id',$transaction->id)->update(['status'=>UserTransaction::FAILED]);
                        $UserTransaction = UserTransaction::where('id',$transaction->id)->first();
                        UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::FAILED]);
                        UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::FAILED]);
                        Log::info('payment failed due to paypal not initialize');
                        //Log::info('payer not exist');

                    }

                } catch (Exception $ex) {
                    UserTransaction::failedPackageTransaction($transaction->transaction_id);
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
                        $id = $transaction->transaction_id;
                        $payment = Payment::get($id,$this->_api_context);
                        if($payment->payer){


                            $execution = new PaymentExecution();
                            $execution->setPayerId($payment->payer->payer_info->payer_id);
                            /**Execute the payment **/
                            $result = $payment->execute($execution, $this->_api_context);
                            if ($result->getState() == 'approved') {
                                $UserTransaction = UserTransaction::where('transaction_id',$id)->first();
                                
                                UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::ACTIVE]);
                                 if($payment->getTransactions()){
                                    if($payment->getTransactions()[0]->getRelatedResources()){
                                        $saveId = @$payment->getTransactions()[0]->getRelatedResources()[0]->sale->id;
                                    }
                                    else{
                                        $saveId = $payment->payer->payer_info->payer_id; 
                                    }
                                }
                                else{
                                    $saveId = $payment->payer->payer_info->payer_id;
                                }
                                
                                UserTransaction::where('transaction_id',$id)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::PAYPAL,'payer_id'=>$saveId]);

                                // save the shipping address for product
                                $payer = $payment->getPayer();
                                if($payment->getPayer()->payer_info){
                                    $payerInfo = $payer->payer_info;
                                    if($payer->payer_info){
                                        $shipping['user_id'] = $UserTransaction->user_id;
                                        $shipping['user_transaction_id'] = $UserTransaction->id;
                                        $shipping['email'] =  $payer->payer_info->email ? $payer->payer_info->email : '';
                                        $shipping['first_name'] = $payer->payer_info->first_name ? $payer->payer_info->first_name : '';
                                        $shipping['last_name'] = $payer->payer_info->last_name ? $payer->payer_info->last_name : '';
                                        $shipping['payer_id'] = $payer->payer_info->payer_id ? $payer->payer_info->payer_id : '';
                                        $shipping['shipping_address'] = $payer->payer_info->shipping_address ? json_encode($payer->payer_info->shipping_address->toArray()) : '';
                                        UserShippingAddress::create($shipping);
                                    }
                                }
                                // send invoice mail
                                $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($UserTransaction->id);
                                $invoice->sendProductInvoice();

                                Log::info('product payment success');
                            }

                            else {

                                 UserTransaction::failedProductTransaction($id);
                                 /*UserTransaction::where('transaction_id',$id)->update(['status'=>UserTransaction::FAILED]);
                                 $UserTransaction = UserTransaction::where('transaction_id',$id)->first();
                                 UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::EXPIRE]);*/
                            }

                             //Log::info('payer exist');
                                }
                                else {
                                        UserTransaction::failedProductTransaction($id);
                                      /*  UserTransaction::where('transaction_id',$id)->update(['status'=>UserTransaction::FAILED]);
                                        $UserTransaction = UserTransaction::where('transaction_id',$id)->first();
                                        UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::EXPIRE]);*/
                                        Log::info('product payment failed');
                                        Log::info('product payer not exist');
                                }
                    }
                    else {
                        UserTransaction::where('id',$transaction->id)->update(['status'=>UserTransaction::FAILED]);
                        $UserTransaction = UserTransaction::where('id',$transaction->id)->first();
                        UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::EXPIRE]);
                        Log::info('produuct payment failed due to paypal not initialize');
                        //Log::info('payer not exist');

                    }

                } catch (Exception $ex) {
                    UserTransaction::failedProductTransaction($transaction->transaction_id);
                    Log::info($ex);
                }

            }

    }
}
