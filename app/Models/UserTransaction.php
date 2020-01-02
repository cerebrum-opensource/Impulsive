<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PDF;
use Mail;
use Storage;
use App\Mail\ProductOrderPlaced;
use App\Mail\PackageOrderPlaced;
use App\Mail\LossOrderPlaced;
use App\Models\UserPlan;
use App\Models\UserBonusBid;
use App\Models\Package;
use App\Models\UserBid;
use App\Models\UserBuyList;
use App\Models\Setting;
use App\Models\UserLossAuction;
use Log;
// Ekomi Api libraries
use Ekomi\Api;
use Ekomi\Request\PutOrder;
use App\Mail\EkomiFeedback;

class UserTransaction extends Model
{
    //
    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    const PENDING = '0';
    const COMPLETE = '1';
    const FAILED = '2';
    const REFUND = '3';

    const PAYPAL = '0';
    const KLARNA = '1';
    const SOFORT = '2';

     public function package()
    {
        return $this->belongsTo('App\Models\Package','package_id')->where('deleted_at', NULL);
    }

    public function buy_auction()
    {
        return $this->belongsTo('App\Models\UserBuyList','user_buy_list_id');
    }

    public function shipping_address()
    {
        return $this->hasMany('App\Models\UserShippingAddress','user_transaction_id');
    }
    

    public function user()
    {
        return $this->belongsTo('App\User','user_id')->where('deleted_at', NULL);
    }

    public function getPaymentMethodnameAttribute()
    { 
       $status='';
        switch ($this->attributes['payment_method']) {
        case UserTransaction::PAYPAL:
            $status = trans('label.paypal');
            break;
        case UserTransaction::KLARNA:
            $status = trans('label.klarna');
            break;
        case UserTransaction::SOFORT:
            $status = trans('label.sofort');
            break;
        default:
            $status = trans('label.paypal');
            break;
        }
        return $status;
    }


    public function getStatusnameAttribute()
    { 
       $status='';
        switch ($this->attributes['status']) {
        case UserTransaction::PENDING:
            $status = trans('label.pending');
            break;
        case UserTransaction::COMPLETE:
            $status = trans('label.complete');
            break;
        case UserTransaction::FAILED:
            $status = trans('label.failed');
            break;
        case UserTransaction::REFUND:
            $status = trans('label.refund');
            break;
        default:
            $status = trans('label.pending');
            break;
        }
        return $status;
    }

    public function sendProductInvoice()
    {   
        $invoice = $this;
        //$data['invoice_number'] = trans('label.invoice').$invoice->id;
        $data['invoice_number'] = ' '.$invoice->id;
        $data['total_amount'] = $invoice->amount;
        $data['payment_date'] = $invoice->payment_date;
        $data['payment_type'] = $invoice->payment_method_name;
        $data['status'] = $invoice->status_name;
        $data['date'] = $invoice->payment_date;
        $data['type'] = $invoice->buy_auction->type_name;
        $data['user'] = $invoice->user;

        if($invoice->buy_auction){
            $data['auction_price'] = $invoice->buy_auction->amount;
            $data['auction_tax'] = $invoice->buy_auction->tax;
            $data['auction_shipping'] = $invoice->buy_auction->shipping_price;
            $data['total_price'] =  round($invoice->buy_auction->amount+ $invoice->buy_auction->tax+ $invoice->buy_auction->shipping_price,2);
            if($invoice->buy_auction->product){
                // print_r($invoice->buy_auction->product);
                // die();
                $data['product_title'] = $invoice->buy_auction->product->product_title;
                $data['article_number'] = $invoice->buy_auction->product->article_number;
                $data['product_original_price'] = $invoice->buy_auction->product->product_price;
                $data['product_product_brand'] = $invoice->buy_auction->product->product_brand;
                $data['product_article_number'] = $invoice->buy_auction->product->article_number;
                $data['customer_no'] = $invoice->user_id;
            }

        }
        // in case if the product is a package.
        if($invoice->buy_auction)
        {
            if($invoice->buy_auction->product){
                if($invoice->buy_auction->product->is_package == 1){
                    // code to recharge the winis
                    Log::info("The product is a package");
                    $package_id = $invoice->buy_auction->product->package_id;
                    $package_amount = Package::where('id',$package_id)->first();
                    
                        $planData['user_id'] = $invoice->user_id;
                        $planData['package_id'] = $package_id;
                        $planData['amount'] = $package_amount->price; 
                        $planData['tax'] = $invoice->buy_auction->tax; 
                        $planData['bid_count'] = $package_amount->bid; 
                        $planData['status'] = UserPlan::ACTIVE; 
                        $planData['type'] = UserPlan::AUCTION; 
                        
                        UserPlan::create($planData);

                        $userBid = UserBid::where('user_id',$invoice->user_id)->first();
                        $bidCount =  $userBid->bid_count + $package_amount->bid;
                        $update_bid = UserBid::where('user_id',$invoice->user_id)->update(['bid_count' => $bidCount]);
                        Log::info("Bid updated");
                }
            }
        }
        if($invoice->shipping_address->count()){
            $data['name'] = @$invoice->shipping_address[0]->first_name. ' ' .@$invoice->shipping_address[0]->last_name;
            $data['email'] = @$invoice->shipping_address[0]->email;
            $data['shipping_address'] = @$invoice->shipping_address[0]->shipping_address;
        }

        // $pdf = PDF::loadView('pdf.productPdf', $data)->setPaper('a4');
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.productPdf', $data)->setPaper('a4'); 
        $text = trans('message.product_invoice_email_message');
        $subject = trans('message.product_invoice_email_subject');
         $email = $invoice->user->email; 
          $ekomi_conf = \Config::get('ekomi');
          $api = new Api($ekomi_conf['ekomiAPIId'],$ekomi_conf['ekomiAPIKey']);
          $apiCall = new PutOrder();
          $apiCall->setOrderId($data['invoice_number']);
          $result = $api->exec($apiCall);
      // print_r($result->link);
      // die();
      Mail::to($email)->send(new EkomiFeedback($result->link,$invoice->user));
        if($invoice->buy_auction && $invoice->buy_auction->type == UserBuyList::PRODUCT_PURCHASE ){
            $text = trans('message.product_invoice_email_message');
            $subject = trans('message.product_invoice_email_subject');
            return Mail::to($email)->send(new LossOrderPlaced($pdf->output(),$text,$invoice->user));
        }

        return Mail::to($email)->send(new ProductOrderPlaced($pdf->output(),$text,$invoice->user));

        //return $data;
    }

    public function sendPackageInvoice()
    {   
        $invoice = $this;
        //$data['invoice_number'] = trans('label.invoice').$invoice->id;
        
        //$data['invoice_number'] = trans('label.invoice').$invoice->id;
        $data['invoice_number'] = ' '.$invoice->id;
        
        $data['total_amount'] = $invoice->amount;
        $data['payment_date'] = $invoice->payment_date;
        $data['payment_type'] = $invoice->payment_method_name;
        $data['status'] = $invoice->status_name;
        $data['date'] = $invoice->payment_date;
        $data['type'] = trans('label.package');
        $data['user'] = $invoice->user;

        if($invoice->package){
            $data['total_price'] = $invoice->package->price;
            $data['package_name'] = $invoice->package->name;
            $data['package_description'] = $invoice->package->description;
            $data['package_price'] = get_tax_amount($invoice->package->price,TAX_PERCENT);
            $data['customer_no'] = $invoice->user_id;
            if($invoice->package->tax)
                $data['tax_price'] = $invoice->package->tax;
            else
                $data['tax_price'] = $invoice->package->price-get_tax_amount($invoice->package->price,TAX_PERCENT);
        }
       // $pdf = PDF::loadView('pdf.packagePdf', $data)->setPaper('a4');
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.packagePdf', $data)->setPaper('a4');
        $text = trans('message.package_invoice_email_message');
        $subject = trans('message.package_invoice_email_subject');
        $email = $invoice->user->email;
        $setting = Setting::firstOrFail();
        if(!empty($setting))
        {
            $bid_day_count = $setting->bonus_bid_day_count;
        }
        else{
            $bid_day_count = BONUS_BID_DAY_COUNT;
        }
        return Mail::to($email)->send(new PackageOrderPlaced($pdf->output(),$text,$invoice->user,$invoice->package,$bid_day_count));
    }


    public static function failedPackageTransaction($transaction_id)
    {   
        UserTransaction::where('transaction_id',$transaction_id)->update(['status'=>UserTransaction::FAILED]);
        $UserTransaction = UserTransaction::where('transaction_id',$transaction_id)->first();
        UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::FAILED]);
        UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::FAILED]);
    }

    public static function successPackageTransaction($transaction_id)
    {   

        $UserTransaction = UserTransaction::with('package')->where('transaction_id',$transaction_id)->first();
        UserPlan::where('id',$UserTransaction->plan_id)->update(['status'=>UserPlan::ACTIVE]);
        UserBonusBid::where('plan_id',$UserTransaction->plan_id)->update(['status'=>UserBonusBid::ACTIVE]);
        $userbid['user_id'] = $UserTransaction->user_id;
        if(UserBid::where('user_id',$UserTransaction->user_id)->count()){
            $bidAdd = $UserTransaction->package->bid+$UserTransaction->package->bonus;
            UserBid::where('user_id',$UserTransaction->user_id)->increment('bid_count', $bidAdd);
            if($UserTransaction->package->bonus)
                UserBid::where('user_id',$UserTransaction->user_id)->increment('free_bid', $UserTransaction->package->bonus);
        }
        else {
            $userbid['bid_count'] = $UserTransaction->package->bid;
            UserBid::create($userbid);
        }
    }

    public static function failedProductTransaction($transaction_id)
    {   
        $UserTransaction = UserTransaction::where('transaction_id',$transaction_id)->first();
        UserTransaction::where('transaction_id',$transaction_id)->update(['status'=>UserTransaction::FAILED]);
        UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::EXPIRE]);
    }

    public static function successProductTransaction($transaction_id,$payment_method,$payer_id)
    {   
        $UserTransaction = UserTransaction::where('transaction_id',$transaction_id)->first();
        UserBuyList::where('id',$UserTransaction->user_buy_list_id)->update(['status'=>UserBuyList::ACTIVE]);
        UserTransaction::where('transaction_id',$transaction_id)->update(['status'=>UserTransaction::COMPLETE,'payment_method'=>$payment_method,'payer_id'=>$payer_id]);

            $buyList = UserBuyList::where('id',$UserTransaction->user_buy_list_id)->first();

           

            if(isset($buyList->link_token)){
                Log::info('loss payment_method');
                Log::info($buyList);
                UserLossAuction::where('link',$buyList->link_token)->update(['status'=>UserLossAuction::EXPIRE]);
                //return Redirect::to('/win_list');  
            }
    }

    public function getStatusBadgeAttribute()
    { 
       $status='';
        switch ($this->attributes['status']) {
        case UserBuyList::PENDING:
            $status = '<span class="ps-setting">'.trans('label.pending').'</span>';
            break;
        case UserBuyList::ACTIVE:
            $status = '<span class="pd-setting">'.trans('label.complete').'</span>';
            break;
        case UserBuyList::EXPIRE:
            $status = '<span class="ds-setting">'.trans('label.failed').'</span>';
            break;
        default:
            $status = '<span class="pd-setting">'.trans('label.complete').'</span>';
            break;
        }
        return $status;
    } 


    public function getTypenameAttribute()
    { 
        $status = '';
        if($this->attributes['package_id']){
            $status = trans('label.package');
        }
        if($this->attributes['user_buy_list_id']){
            $status = trans('label.product');
        }
        return $status;
    }

     public function downloadProductInvoice($isDetail=0,$string="")
    {   
        $invoice = $this;
        $data['invoice_number'] = trans('label.invoice').$invoice->id;
        $data['total_amount'] = $invoice->amount;
        $data['payment_date'] = $invoice->payment_date;
        $data['payment_type'] = $invoice->payment_method_name;
        $data['status'] = $invoice->status_name;
        $data['date'] = $invoice->payment_date;
        $data['type'] = $invoice->buy_auction ? $invoice->buy_auction->type_name : '';
        $data['user'] = $invoice->user;

        if($invoice->buy_auction){
            $data['auction_price'] = $invoice->buy_auction->amount;
            $data['auction_tax'] = $invoice->buy_auction->tax;
            $data['auction_shipping'] = $invoice->buy_auction->shipping_price;
            $data['total_price'] =  round($invoice->buy_auction->amount+ $invoice->buy_auction->tax+ $invoice->buy_auction->shipping_price,2);
            if($invoice->buy_auction->product){
                $data['product_title'] = $invoice->buy_auction->product->product_title;
                $data['article_number'] = $invoice->buy_auction->product->article_number;
                $data['product_original_price'] = $invoice->buy_auction->product->product_price;
                $data['product_product_brand'] = $invoice->buy_auction->product->product_brand;
                $data['product_article_number'] = $invoice->buy_auction->product->article_number;
                $data['customer_no'] = $invoice->user_id;
            }

        }

        if($invoice->shipping_address->count()){
            $data['name'] = @$invoice->shipping_address[0]->first_name. ' ' .@$invoice->shipping_address[0]->last_name;
            $data['email'] = @$invoice->shipping_address[0]->email;
            $data['shipping_address'] = @$invoice->shipping_address[0]->shipping_address;
        }
        $customPaper = array(0,0,720,750);
        $pdf = PDF::loadView('pdf.productPdf', $data)->setPaper('a4');

        $name = 'rechnung'.$invoice->id.'.pdf';
        $savePath = 'pdf'.$string.'/'.$name;

        if($isDetail){
          return $pdf->download($name);  
        }
        else {
          //  $pdf->save(public_path($savePath));
            Storage::put($savePath, $pdf->output());
            //Storage::put($savePath, $pdf->output());
        }
       // $name = $invoice->id.'product_invoice.pdf';
       
        //return $pdf->download($name);
    
    }


    public function downloadPackageInvoice($isDetail=0,$string="")
    {   
        $invoice = $this;
        $data['invoice_number'] = trans('label.invoice').$invoice->id;
        $data['total_amount'] = $invoice->amount;
        $data['payment_date'] = $invoice->payment_date;
        $data['payment_type'] = $invoice->payment_method_name;
        $data['status'] = $invoice->status_name;
        $data['date'] = $invoice->payment_date;
        $data['type'] = trans('label.package');
        $data['user'] = $invoice->user;

        if($invoice->package){
            $data['total_price'] = $invoice->package->price;
            $data['package_name'] = $invoice->package->name;
            $data['package_description'] = $invoice->package->description;
            $data['package_price'] = get_tax_amount($invoice->package->price,TAX_PERCENT);
            $data['customer_no'] = $invoice->user_id;
            if($invoice->package->tax)
                $data['tax_price'] = $invoice->package->tax;
            else
                $data['tax_price'] = $invoice->package->price-get_tax_amount($invoice->package->price,TAX_PERCENT);
        }
        $customPaper = array(0,0,720,750); 
        $pdf = PDF::loadView('pdf.packagePdf', $data)->setPaper('a4');
        $name = 'rechnung'.$invoice->id.'.pdf';
        $savePath = 'pdf'.$string.'/'.$name;

        if($isDetail){

          return $pdf->download($name);  
        }
        else {
          //  $pdf->save(public_path($savePath));
            Storage::put($savePath, $pdf->output());
            //Storage::put($savePath, $pdf->output());
        }
        
    }
}
