<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/** All Paypal Details class **/
use PayPal\Api\{ Amount, Details, Item, ItemList, Payer, Payment, PaymentExecution, RedirectUrls, Transaction };
use App\Models\{ UserTransaction, UserPlan, UserBid, UserBuyList, UserShippingAddress, UserBonusBid };
use Sofort\SofortLib\{ Sofortueberweisung, Notification, TransactionData };
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Klarna;
use Exception;
use Redirect;
use Session;
use URL;
use Log;


class PaymentController extends Controller
{
    private $_api_context;
    private $klarna_api;
    private $softor_api;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

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
        $configkey = $softor_conf['customer_id'].':'.$softor_conf['project_id'].':'.$softor_conf['key'];
       // echo $configkey = '185467:518559:8506c3941256c9d982727fd01d6015c1';
        $this->softor_api = new Sofortueberweisung($configkey);

    }
   
    /**
     * hit this url when payment from the paypal and check the status
     *
     * @param  \Illuminate\Http\Request  PayerID, token
     * @return \Illuminate\Http\Response : Json
     */

    // 
    public function getPaymentStatus()
    {   
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        try {
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            
            if($payment_id){
                // marked the status to failed
                UserTransaction::failedPackageTransaction($payment_id);
                \Session::put('error', trans('message.payment_not_proceed').' '.$payment_id);
            }
            else {
                \Session::put('error', trans('message.payment_not_proceed_issue'));
            }
            
            return Redirect::to('/packages?tab=complete');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
            $UserTransaction = UserTransaction::with('package')->where('transaction_id',$payment_id)->first();

            // marked the status and update the bid into account
            UserTransaction::successPackageTransaction($payment_id);

            if($payment->getTransactions()){
                if($payment->getTransactions()[0]->getRelatedResources())
                    $saveId = @$payment->getTransactions()[0]->getRelatedResources()[0]->sale->id;
                else
                    $saveId = Input::get('PayerID');  
            }
            else{
                $saveId = Input::get('PayerID');
            }
            UserTransaction::where('transaction_id',$payment_id)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::PAYPAL,'payer_id'=>$saveId]);
            \Session::put('success', trans('message.payment_proceed_successfully'));
           
            $invoice = UserTransaction::with('package','user')->findOrfail($UserTransaction->id);
            // send the invoice 

            $invoice->sendPackageInvoice();

            return Redirect::to('/packages');
        }
        else {
            // marked the status to failed

            UserTransaction::failedPackageTransaction($payment_id);
            \Session::put('error', trans('message.payment_not_proceed').' '.$payment_id);
            return Redirect::to('/packages');
            
        }

        }catch (Exception $e) {
            // marked the status to failed
            UserTransaction::failedPackageTransaction($payment_id);
            Log::info($e->getMessage());
            \Session::put('error', trans('message.payment_not_proceed_issue'));
            return Redirect::to('/packages');
        }

        //print_r($result);
         \Session::put('error', trans('message.payment_not_proceed').' '.$payment_id);
        // return Redirect::to('/packages');
    }


    
  /*  public function getIpn(Request $request)
    {   

        try {
            $id = 'PAYID-LUPM7YI6RV159605M7968818';
            $payment = Payment::get($id,$this->_api_context);
            echo "<pre>";
            print_r($payment->getTransactions()[0]->getRelatedResources()[0]->sale->id);
          //  print_r($payment->getPayer());
        } catch (Exception $ex) {

            print_r($ex);

        }

        Log::info($_GET);
        //print_r($request->all());

    }*/


   
     /**
     * hit this url when payment from the klarna and check the status
     *
     * @param  \Illuminate\Http\Request  sid
     * @return \Illuminate\Http\Response : Json
     */

    public function getKlarnaPaymentStatus()
    {   
        $connector = $this->klarna_api;

        $orderID = $_GET['sid'];
        try {
            $checkout = new Klarna\Rest\OrderManagement\Order($connector,$orderID);
            $order =  $checkout->fetch($checkout);
            $UserTransaction = UserTransaction::with('package')->where('transaction_id',$orderID)->first();
           // echo $order['klarna_reference'];
           if($order['status'] == 'AUTHORIZED'){
            $checkoutKlarna = new Klarna\Rest\OrderManagement\Order($connector,$orderID);
            $orderCapture = [
                  "captured_amount" => round($UserTransaction->amount*100,2),
                  "description" => "Amount capture from impulsive",
            ];
            $capture =  $checkoutKlarna->createCapture($orderCapture);

            // marked the status to success and update the bid

            UserTransaction::successPackageTransaction($orderID);
            UserTransaction::where('transaction_id',$orderID)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::KLARNA,'payer_id'=>$order['klarna_reference']]);
            \Session::put('success', trans('message.payment_proceed_successfully'));

            $invoice = UserTransaction::with('package','user')->findOrfail($UserTransaction->id);
            $invoice->sendPackageInvoice();
        
            return Redirect::to('/packages');
            }

            if ($order['status'] == 'checkout_incomplete') {
                // marked the status to failed
                UserTransaction::failedPackageTransaction($orderID);
                \Session::put('error', trans('message.payment_not_proceed').' '.$order['klarna_reference']);
                return Redirect::to('/packages');
            }

            if ($order['status'] == 'CAPTURED') {
                $invoice = UserTransaction::with('package','user')->findOrfail($UserTransaction->id);
                $invoice->sendPackageInvoice();
                return Redirect::to('/packages');
            }
        } catch (Klarna\Rest\Transport\Exception\ConnectorException $e) {

            // marked the status to failed

            UserTransaction::failedPackageTransaction($orderID);

            \Session::put('error', trans('message.payment_not_proceed').' '.$orderID);
            return Redirect::to('/packages');

        }
    }


     /**
     * success url for sofort for package
     *
     * @param  \Illuminate\Http\Request  Request $request
     * @return \Illuminate\Http\Response : Redirect
     */

    public function getSofortSuccess(Request $request)
    {   
        $transactionId = $request->transaction;
        $UserTransaction = UserTransaction::with('package')->where('transaction_id',$transactionId)->first();

        $softor_conf = \Config::get('softor');
        $configkey = $softor_conf['customer_id'].':'.$softor_conf['project_id'].':'.$softor_conf['key'];
        $SofortLibTransactionData = new TransactionData($configkey);
        $SofortLibTransactionData->addTransaction($transactionId);
        $SofortLibTransactionData->setApiVersion('2.0');
        $SofortLibTransactionData->sendRequest();

        if($SofortLibTransactionData->isError()) {

                // marked the status to failed
                UserTransaction::failedPackageTransaction($transactionId);
                \Session::put('success', trans('message.payment_not_proceed').' '.$transactionId);
            }else {
              $status = $SofortLibTransactionData->getStatus();
                if($status =='untraceable' || $status=='pending'){
                    $transactionId = $SofortLibTransactionData->getTransaction();
                    Log::info('taa'.$transactionId);

                    if($UserTransaction->status == UserTransaction::PENDING) {

                        // marked the status to success and update the bid account
                        UserTransaction::successPackageTransaction($transactionId);
                        UserTransaction::where('transaction_id',$transactionId)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::SOFORT,'payer_id'=>$transactionId]);
                        $invoice = UserTransaction::with('package','user')->findOrfail($UserTransaction->id);
                        $invoice->sendPackageInvoice();
                    }
                    \Session::put('success', trans('message.payment_proceed_successfully'));
                 //   return Redirect::to('/packages');

                }
            }
        return Redirect::to('/packages');
    }

    /**
     * abort url for sofort for package
     *
     * @param  \Illuminate\Http\Request  Request $request
     * @return \Illuminate\Http\Response : Redirect
     */

    public function getSofortAbort(Request $request)
    {   
       
        $transactionId = $request->transaction;
        // marked the status to failed
        UserTransaction::failedPackageTransaction($transactionId);
        \Session::put('success', trans('message.payment_abort'));
        return Redirect::to('/packages');
    }


    /**
     * success url for sofort product
     *
     * @param  \Illuminate\Http\Request  Request $request
     * @return \Illuminate\Http\Response : Redirect
     */

    public function getProductSofortSuccess(Request $request)
    {   
        
        $transactionId = $request->transaction;
        $UserTransaction = UserTransaction::where('transaction_id',$transactionId)->first();

        $softor_conf = \Config::get('softor');
        $configkey = $softor_conf['customer_id'].':'.$softor_conf['project_id'].':'.$softor_conf['key'];
        $SofortLibTransactionData = new TransactionData($configkey);
        $SofortLibTransactionData->addTransaction($transactionId);
        $SofortLibTransactionData->setApiVersion('2.0');
        $SofortLibTransactionData->sendRequest();

        if($SofortLibTransactionData->isError()) {
               
                // marked the status to failed
                UserTransaction::failedProductTransaction($transactionId);
                \Session::put('success', trans('message.payment_not_proceed').' '.$request->transaction);
            }else {
              $status = $SofortLibTransactionData->getStatus();
                if($status =='untraceable' || $status=='pending'){
                    $transactionId = $SofortLibTransactionData->getTransaction();
                    Log::info('taa'.$transactionId);

                    if($UserTransaction->status == UserTransaction::PENDING) {

                        // marked the status to success
                        UserTransaction::successProductTransaction($transactionId,UserTransaction::SOFORT,$transactionId);
                     
                        \Session::put('success', trans('message.product_payment_proceed_successfully'));
                        $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($UserTransaction->id);
                        $invoice->sendProductInvoice();
                    }
                    \Session::put('success', trans('message.product_payment_proceed_successfully'));
                    return Redirect::to('/win_list');

                }
            }
    }

    /**
     * abort url for sofort for product
     *
     * @param  \Illuminate\Http\Request  Request $request
     * @return \Illuminate\Http\Response : Redirect
     */

    public function getProductSofortAbort(Request $request)
    {   
        \Session::put('success', trans('message.payment_abort'));
        $transactionId = $request->transaction;
        // marked the status to failed
        UserTransaction::failedProductTransaction($transactionId);
        return Redirect::to('/win_list');
    }

    /**
     * notify url for sofort for package
     *
     * @param  \Illuminate\Http\Request  Request $request
     * @return \Illuminate\Http\Response :
     */

    public function getSofortPaymentNotificationStatus(Request $request)
    {   
        $Sofortueberweisung = $this->softor_api;
        $softor_conf = \Config::get('softor');
        $configkey = $softor_conf['customer_id'].':'.$softor_conf['project_id'].':'.$softor_conf['key'];
        $SofortLib_Notification = new Notification();

        $TestNotification = $SofortLib_Notification->getNotification(file_get_contents('php://input'));
         if($SofortLib_Notification->errors){
            Log::info(json_encode($SofortLib_Notification->errors));
         }
         else {
            $SofortLibTransactionData = new TransactionData($configkey);
            $SofortLibTransactionData->addTransaction($TestNotification);
            $SofortLibTransactionData->setApiVersion('2.0');
            $SofortLibTransactionData->sendRequest();
            $transactionId = $SofortLib_Notification->getTransactionId();
            Log::info('t'.$transactionId);

            $UserTransaction = UserTransaction::with('package')->where('transaction_id',$transactionId)->first();

            if($SofortLibTransactionData->isError()) {
                  // marked the status to failed
                UserTransaction::failedPackageTransaction($transactionId);

            }else {
              $status = $SofortLibTransactionData->getStatus();
                if($status =='untraceable' || $status=='pending'){
                    $transactionId = $SofortLibTransactionData->getTransaction();
                    Log::info('taa'.$transactionId);

                    if($UserTransaction->status == UserTransaction::PENDING) {
                        // marked the status to success and update the bid account
                        UserTransaction::successPackageTransaction($transactionId);
                        UserTransaction::where('transaction_id',$transactionId)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>UserTransaction::SOFORT,'payer_id'=>$transactionId]);
                        $invoice = UserTransaction::with('package','user')->findOrfail($UserTransaction->id);
                        $invoice->sendPackageInvoice();
                    }
                }
            }
         }


    }


    /**
     * notify url for sofort for product
     *
     * @param  \Illuminate\Http\Request  Request $request
     * @return \Illuminate\Http\Response : Redirect
     */

    public function getProductSofortPaymentNotificationStatus(Request $request)
    {   
        $Sofortueberweisung = $this->softor_api;
        $softor_conf = \Config::get('softor');
        $configkey = $softor_conf['customer_id'].':'.$softor_conf['project_id'].':'.$softor_conf['key'];
        $SofortLib_Notification = new Notification();

        $TestNotification = $SofortLib_Notification->getNotification(file_get_contents('php://input'));
        if($SofortLib_Notification->errors){
            Log::info(json_encode($SofortLib_Notification->errors));
        }
         else {
            $SofortLibTransactionData = new TransactionData($configkey);
            $SofortLibTransactionData->addTransaction($TestNotification);
            $SofortLibTransactionData->setApiVersion('2.0');
            $SofortLibTransactionData->sendRequest();
            $transactionId = $SofortLib_Notification->getTransactionId();
            Log::info('transaction_id'.$transactionId);

            $UserTransaction = UserTransaction::where('transaction_id',$transactionId)->first();

            if($SofortLibTransactionData->isError()) {
               // marked the status to failed

                UserTransaction::failedProductTransaction($transactionId);

            }else {
              $status = $SofortLibTransactionData->getStatus();
               $transactionId = $SofortLibTransactionData->getTransaction();
               $UserTransaction = UserTransaction::where('transaction_id',$transactionId)->first();
                if($status =='untraceable' || $status=='pending'){
                   
                    Log::info('transaction_id2'.$transactionId);

                    if($UserTransaction->status == UserTransaction::PENDING) {

                         // marked the status to success 
                        UserTransaction::successProductTransaction($transactionId,UserTransaction::SOFORT,$transactionId);

                        \Session::put('success', trans('message.product_payment_proceed_successfully'));
                        $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($UserTransaction->id);
                        $invoice->sendProductInvoice();
                    }
                   
                 //   return Redirect::to('/packages');

                }
            }
         }


    }



    /**
     * hit this url when payment from the paypal for auction buy and check the status
     *
     * @param  \Illuminate\Http\Request  PayerID, token
     * @return \Illuminate\Http\Response : Json
     */


     public function getProductPaymentStatus()
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('product_paypal_payment_id');
        try {
       // die($payment_id);
        /** clear the session payment ID **/
       Session::forget('product_paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            
            if($payment_id){
                // marked the status to failed 
                $UserTransaction = UserTransaction::where('transaction_id',$payment_id)->first();

                $buyList = UserBuyList::where('id',$UserTransaction->user_buy_list_id)->first();
                if(isset($buyList->link_token)){
                	//return Redirect::to('/win_list');
                	return redirect()->route('frontend_loss_auction', [$buyList->link_token]);
                }

                UserTransaction::failedProductTransaction($payment_id);
                \Session::put('error', trans('message.payment_not_proceed').' '.$payment_id);
            }
            else {
                \Session::put('error', trans('message.payment_not_proceed_issue'));
            }
            
            return Redirect::to('/win_list');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        
      
        if ($result->getState() == 'approved') {
            $UserTransaction = UserTransaction::with('user')->where('transaction_id',$payment_id)->first();
            
            $saveId = '';
            if($payment->getTransactions()){
                if($payment->getTransactions()[0]->getRelatedResources())
                    $saveId = @$payment->getTransactions()[0]->getRelatedResources()[0]->sale->id;
                else
                    $saveId = Input::get('PayerID');  
            }
            else{
                $saveId = Input::get('PayerID');
            }

            // marked the status to success 
            UserTransaction::successProductTransaction($payment_id,UserTransaction::PAYPAL,$saveId);

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

            $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($UserTransaction->id);
            $invoice->sendProductInvoice();
            \Session::put('success', trans('message.product_payment_proceed_successfully'));

            // if($invoice->buy_auction && $invoice->buy_auction->type == UserBuyList::PRODUCT_PURCHASE ){
            // 	 return Redirect::to('/win_list');
            // }
            return Redirect::to('/win_list');
        }
        else {
            UserTransaction::where('transaction_id',$payment_id)->update(['status'=>UserTransaction::FAILED]);
            $UserTransaction = UserTransaction::where('transaction_id',$payment_id)->first();
            UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::EXPIRE]);
            \Session::put('error', trans('message.payment_not_proceed').' '.$payment_id);
            return Redirect::to('/win_list');
            
        }

        }catch (Exception $e) {
            Log::info($e->getMessage());
            \Session::put('error', trans('message.payment_not_proceed_issue'));
            return Redirect::to('/win_list');
        }
         \Session::put('error', trans('message.payment_not_proceed').' '.$payment_id);
    }



    /**
     * hit this url when payment from the klarna for auction buy and check the status
     *
     * @param  \Illuminate\Http\Request  sid
     * @return \Illuminate\Http\Response : Json
     */



    public function getProductKlarnaPaymentStatus()
    {   
        $connector = $this->klarna_api;

        $orderID = $_GET['sid'];
        try {
            $checkout = new Klarna\Rest\OrderManagement\Order($connector,$orderID);
            $order =  $checkout->fetch($checkout);
            $UserTransaction = UserTransaction::where('transaction_id',$orderID)->first();
           if($order['status'] == 'AUTHORIZED'){
            
            $checkoutKlarna = new Klarna\Rest\OrderManagement\Order($connector,$orderID);
            $orderCapture = [
                  "captured_amount" => round($UserTransaction->amount*100,2),
                  "description" => "Amount capture from impulsive",
            ];
            $capture =  $checkoutKlarna->createCapture($orderCapture);
            
            // marked the status to success 

            UserTransaction::successProductTransaction($orderID,UserTransaction::KLARNA,$order['klarna_reference']);
            \Session::put('success', trans('message.product_payment_proceed_successfully'));

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
                $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($UserTransaction->id);
                $invoice->sendProductInvoice();
                return Redirect::to('/win_list');
            }

            if ($order['status'] == 'checkout_incomplete') {

                 // marked the status to failed 
                UserTransaction::failedProductTransaction($orderID);
                \Session::put('error', trans('message.payment_not_proceed').' '.$order['klarna_reference']);
                return Redirect::to('/win_list');
            }

            if ($order['status'] == 'CAPTURED') {
              //  $UserTransaction = UserTransaction::with('package')->where('transaction_id',$orderID)->first();
                $invoice = UserTransaction::with('buy_auction','shipping_address','user')->findOrfail($UserTransaction->id);
                $invoice->sendProductInvoice();
                return Redirect::to('/win_list');
            }
        } catch (Klarna\Rest\Transport\Exception\ConnectorException $e) {
           // print_r($e);
            Log::info($e->getMessage());

            // marked the status to failed 
            UserTransaction::failedProductTransaction($orderID);
            \Session::put('error', trans('message.payment_not_proceed').' '.$orderID);
            return Redirect::to('/win_list');

        }
    }


    public function getPayPalPlus(Request $request)
    {   
      return view('paypal_plus');
    }
}
