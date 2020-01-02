@extends('layouts.adminapp')

@section('content')

@php
  $name = trans('label.order_detail');
  $product = $detail->product;
  $transaction = $detail->transaction;
@endphp
@include('admin.breadcome', ['name' => $name])

<div class="single-product-tab-area mg-b-30">
   <!-- Single pro tab review Start-->
   <div class="single-pro-review-area">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="review-tab-pro-inner">
                  <ul id="myTab3" class="tab-review-design">
                     <li class="active"><a href="#description">{{ $name }} </a></li>
                  </ul>
                  <div class="row">
                     <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="review-content-section">
                           <p> {{ trans('label.product_title') }} : <span> {{ $product->product_title != ' ' ? $product->product_title  : 'N/A'}} </span></p>
                           <p> {{ trans('label.status') }} : <span>  {!! $detail->getProductStatusNameAttribute() !!} </span></p>
                           <p> {{ trans('label.price') }} : <span> {{ $detail->amount ? CURRENCY_ICON.$detail->amount  : 'N/A'}} </span></p>
                           <p> {{ trans('label.shiping_price') }}  : <span> {{ $detail->shipping_price ? CURRENCY_ICON.$detail->shipping_price  : 'N/A'  }} </span></p>
                           <p> {{ trans('label.tax') }}  : <span> {{ $detail->tax ? CURRENCY_ICON.$detail->tax  : 'N/A'  }} </span></p>
                           <p> {{ trans('label.invoice_number') }}  : <span> {{ $transaction->id ? trans('label.invoice').$transaction->id  : 'N/A'  }} </span></p>
                           <p> {{ trans('label.payment_method') }}  : <span> {!! $transaction->getPaymentMethodnameAttribute() !!} </span></p>

                            <p> {{ trans('label.total_amount') }}  : <span> {!! CURRENCY_ICON.($detail->amount+$detail->shipping_price+$detail->tax) !!} </span></p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('javascript')

@endsection
