<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\DateFormat;
use App\Mail\ProductOrderPlaced;
use PDF;
use Mail;


class UserBuyList extends Model
{   

    use DateFormat;

    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    protected $dates_need_to_be_changed = [
      //  'created_at'
    ];

    const DRAFT = '0';
    const PENDING = '0';
    const ACTIVE = '1';
    const EXPIRE = '2';
    const WINNER = '1';
    const INSTANT_PURCHASE = '2';
    const PRODUCT_PURCHASE = '3';
    const DIRECT_PRODUCT_PURCHASE = '4';


    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function getTypenameAttribute()
    { 
       $status='';
        switch ($this->attributes['type']) {
        case UserBuyList::WINNER:
            $status = trans('label.winner');
            break;
        case UserBuyList::INSTANT_PURCHASE:
            $status = trans('label.instant_purchase');
            break;
        case UserBuyList::PRODUCT_PURCHASE:
            $status = trans('label.afterplay');
            break;
        case UserBuyList::DIRECT_PRODUCT_PURCHASE:
            $status = trans('label.direct_purchase');
            break;
        default:
            $status = trans('label.winner');
            break;
        }
        return $status;
    }

    public function transaction()
    {
        return $this->hasOne('App\Models\UserTransaction');
    }

    public function auction_winner()
    {
        return $this->belongsTo('App\Models\AuctionWinner','auction_winner_id');
    }

    public function getProductStatusBadgeAttribute()
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

    public function getProductStatusNameAttribute()
    { 
       $status='';
        switch ($this->attributes['status']) {
        case UserBuyList::PENDING:
            $status = trans('label.pending');
            break;
        case UserBuyList::ACTIVE:
            $status = trans('label.complete');
            break;
        case UserBuyList::EXPIRE:
            $status = trans('label.failed');
            break;
        default:
            $status = trans('label.pending');
            break;
        }
        return $status;
    } 

    public function sendProductInvoice()
    {   
        $order = $this;
        $invoice = $order->transaction;
        $data['invoice_number'] = trans('label.invoice').$invoice->id;
        $data['total_amount'] = $invoice->amount;
        $data['payment_date'] = $invoice->payment_date;
        $data['payment_type'] = $invoice->payment_method_name;
        $data['status'] = $invoice->status_name;
        $data['date'] = $invoice->payment_date;
        $data['type'] = $invoice->buy_auction->type_name;

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

        $pdf = PDF::loadView('pdf.productPdf', $data);
        $text = trans('message.product_invoice_email_message');
        $subject = trans('message.product_invoice_email_subject');
        $email = $invoice->user->email;
        return Mail::to($email)->send(new ProductOrderPlaced($pdf->output(),$text,$subject));

        //return $data;
    }

}
