@extends('layouts.app')
@section('title')
 {{ trans('label.direct_purchase') }}
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">
<style>
   .product-inner ul li{
   padding: 5px;
   }
   .pro_img_width{
   width: 120px;
   }
   .text-lower2 .text1a.text5a{
   padding: 11px 0 0 34px; 
   }
   .lb-number {
      display: none !important;
   }
</style>
@endsection
@section('content')
@inject('settingModal','App\Models\Setting')

@php
   $imgdetail = '';
//   $array[] = '';
   if($product->feature_image_1){
      $array[] = $product->feature_image_1;
      $imgdetail = $product->feature_image_1;
   }
   if($product->feature_image_2 && $imgdetail ==''){
   
      $imgdetail =  $product->feature_image_2;
   }
   if($product->feature_image_3 && $imgdetail ==''){
      
      $imgdetail =  $product->feature_image_3;
   }
   if($product->feature_image_4 && $imgdetail ==''){
     
      $imgdetail =  $product->feature_image_4;
   }

   if($product->feature_image_2 ){
      $array[] = $product->feature_image_2;
      
   }
   if($product->feature_image_3 ){
      $array[] = $product->feature_image_3;
     
   }
   if($product->feature_image_4 ){
      $array[] = $product->feature_image_4;
   }



   $productId = encrypt_decrypt('encrypt', $product->id);
   $productSeller = $product->seller ? $product->seller->full_name : 'Winimi';

@endphp
<div class="product-otr">
   <div class="container">
      <h3 class="sec-heading"><span>{{ $product->product_title }}</span></h3>
      <div class="product-inner">
         <div class="row">
            <div class="col-md-8">
               <div class="product-inner">


                  <!-- <span class="flower-text"><img src="{{ asset('images/flower2.png') }}"><span class="inner-text-flower">.<span>{{ trans('label.countdown') }}</span></span></span> -->
                  <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($imgdetail) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$imgdetail }} @else {{ asset('images/mobile-large.png') }} @endif" id="feature_img">
                  <img width="650px" height="650px" class="image_responsive"  src="@if($imgdetail) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$imgdetail }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $product->product_title }}" title="{{ $product->product_title }}">
                  </a>
                  <ul>
                      @if(@$array[0]) 
                     <li>
                        <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($array[0]) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$array[0] }} @else {{ asset('images/mobile-large.png') }} @endif" >
                        <img class="pro_img_width"  src="@if($array[0]) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$array[0] }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $product->product_title }}" title="{{ $product->product_title }}" onmouseover="set_feature_image(this)">
                        </a>
                     </li>
                     @endif
                     @if(@$array[1]) 
                     <li>
                        <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($array[1]) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$array[1] }} @else {{ asset('images/mobile-large.png') }} @endif" >
                        <img class="pro_img_width"  src="@if($array[1]) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$array[1] }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $product->product_title }}" title="{{ $product->product_title }}" onmouseover="set_feature_image(this)">
                        </a>
                     </li>
                     @endif
                     @if(@$array[2])
                     <li>
                        <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($array[2]) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$array[2] }} @else {{ asset('images/mobile-large.png') }} @endif" >
                        <img class="pro_img_width" src="@if($array[2]) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$array[2] }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $product->product_title }}" title="{{ $product->product_title }}" onmouseover="set_feature_image(this)">
                        </a>
                     </li>
                     @endif
                     @if(@$array[3])
                     <li>
                        <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($array[3]) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$array[3] }} @else {{ asset('images/mobile-large.png') }} @endif" >
                        <img class="pro_img_width" src="@if($array[3]) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$array[3] }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $product->product_title }}" title="{{ $product->product_title }}" onmouseover="set_feature_image(this)">
                        </a>
                     </li>
                     @endif
                  </ul>
               </div>
            </div>
            <div class="col-md-4">
               <div id="bid_history_div">
                  
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="container">
      <div class="product-otr2">
         <div class="row">
            <div class="col-md-8">
               <div class="row">
                  <div class="col-md-12">
                     <div class="product-left">
                        <span class="text1a">{{ $product->product_title }}</span>
                        <span class="text1a text2a">{{ trans('label.shiping_price') }}: {{ price_reflect_format($product->shipping_price) }}&nbsp;{{ CURRENCY_ICON }}</span>
                         <span class="text1a text3a">{{ trans('label.regular_price') }}: {{ price_reflect_format($product->product_price) }}&nbsp;{{ CURRENCY_ICON }}</span>
                     
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
   <div class="container">
      <div class="product-otr2 product-otr3">
         <div class="product-inner3a">
            <h3> {{ trans('label.product_description') }}</h3>
            @php
               echo $product->product_long_desc;
            @endphp
            <p></p>
         </div>
      </div>
   </div>
   <div class="container">
      <div class="product-otr2 product-otr4">
         <h3>{{ trans('label.product_detail') }}</h3>
         <div class="row">
            @if(count($fields))
            @foreach($fields as $key => $field) 
            @if($attribute_value[$field->id] !='')
            <div class="col-sm-6">
               <div class="product-inner1">
                  <ul>
                     <li>{{ $field->name }} </li>
                     <li>{{ $attribute_value[$field->id]}}</li>
                  </ul>
               </div>
            </div>
            @endif                 
            @endforeach
            @endif   
         </div>
      </div>
   </div>
    @if (!Auth::guest())
   <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::id()) }}" data-value="{{Auth::id()}}"> 
   <!--Payment Form-->
   <div class="container">
      <div class="product-otr2 product-otr5">



         <h3>{{ trans('label.payment_methods') }}</h3>
         <form action="javascript:;" method="post" id="direct_product_purchase_payment">
               <h4>{{ trans('label.set_payment_method') }}</h4>
               <div class="paypal">
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="payment_method" checked value="paypal">
                     <div class="sec1">
                     <img src="{{ asset('images/paypal.png') }}" alt="paypal" title="paypal">
                     </label>
                     <p>{{ trans('label.payment_with_paypal') }} </p>
                     </div>
                       <div class="sec1">
                        <img src="{{ asset('images/bank-logo.png') }}" alt="{{ trans('label.payment_with_debit_card') }}" title="{{ trans('label.payment_with_debit_card') }}">
                       
                        <p>{{ trans('label.payment_with_debit_card') }} </p>
                        </div>
                         <div class="sec1">
                        <img src="{{ asset('images/cc-logo.png') }}" alt="{{ trans('label.payment_with_credit_card') }}" title="{{ trans('label.payment_with_credit_card') }}">
                        
                        <p>{{ trans('label.payment_with_credit_card') }} </p>
                        </div>
                  </div>
               </div>
              
                <div class="paypal">
                 <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="payment_method" value="softor" >
                    <img src="{{ asset('images/softor.png') }}" alt="softor" title="softor" style="width: 100px">
                    </label>
                    <p>{{ trans('label.payment_with_softor') }} </p>
                 </div>
                </div>

               <span class="error" id="payment_method" style="color:red"></span>
               <input type="hidden" name="amount" value="{{ encrypt_decrypt('encrypt', $product->product_price) }}">
               
               <input type="hidden" name="product_id" value="{{ $productId }}">
               <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::user()->id) }}">
               <span class="error" id="errorId" style="color:red"></span>
               <div class="">
                  <div class="buttonsbottom">
                     <button type="button" class="btn-form btn-form2 mt-3 model_box_save" onClick="javascript:savePayment('#direct_product_purchase_payment')">{{trans('label.pay')}}</button>
                  </div>
               </div>
            </form>
            <div id="ppplus">

          </div>
      </div>
   </div>
   @endif
   <!--live-deals end-->   
   @if (!Auth::guest())
   <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::id()) }}" data-value="{{Auth::id()}}"> 
   
   
   @else
   <input type="hidden" name="user_id" value="0">
   @endif
     
</div>




<!--service-grid start-->  

@endsection
@section('javascript')

<script src="{{ asset('js/ppplus.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/lightbox.js') }}"></script>

<script type="text/javascript">
   function set_feature_image(img_detail){
    var img_address = img_detail.src;
    $(".image_responsive").attr("src", img_address);
    console.log(img_address);
   }
   function savePayment(formId,tab){
      
       $('.model_box_save').attr("disabled", "disabled");
       $('span.error').hide().removeClass('active');
       var formData = new FormData($(formId)[0]);
   
       $.ajax({
         url:"{{ route('direct_purchase_product') }}",
         data:formData,
         type:"POST",
         processData: false,
         contentType: false,
         dataType: "json",
         success:function(response){
           console.log(response);
           $('.model_box_save').removeAttr("disabled");
           $('input,textarea,select').removeClass('changed-input');
           
           if(response.type && response.type=='klarna'){
                $('body').html(response.redirct_url);
             }
             else if(response.type && response.type=='paypal'){
                   // console.log('I am here');
/*
                    $('#ppplus').show();
                    $('#formbody').hide();
                    var ppp = PAYPAL.apps.PPP({
                      "approvalUrl": response.redirct_url,
                      "placeholder": "ppplus",
                      "mode": "live",
                      "country": "DE"
                      });

*/

window.location.href = response.redirct_url;

                 // $('body').html(response.redirct_url);
            }
           else {
             if(response.redirct_url){
              window.location.href = response.redirct_url;
             }
           }
           
         },
         error:function(error){
             $('.model_box_save').removeAttr("disabled");

             $.each(error.responseJSON.errors,function(key,value){
                   if(key == 'package_id'){
                     $('#package_error').html(value).addClass('active').show();
                   }
                   else if(key == 'payment_method'){
                     $('#payment_method').html(value).addClass('active').show();
                   }
                   else {
                     $('#errorId').html(value).addClass('active').show();
                   }
             }); 
         },
         complete:function(data){
                 console.log(data.responseJSON.message);
                 if(data.status == 403){
                    alert(data.responseJSON.message);
                 }
                 else if(data.status == 500){
                    location.reload();
                 }
                 else {
                   // alert('not allowed');
                 }
                $('body').waitMe('hide');
               // $('#paymentModal').modal('hide');
          },
       });
   }
     var timeOutDetail = setInterval(function()
         { 

            // ajax to load the auction detail in every second for real time result
            // $.ajax({
            //    type:"GET",
            //     url:"",
            //     dataType: "json",
            //     success:function(data){
            //          $('#bid_history_div').html(data.html.bid_history);
            //          $('#detail_bid_latest_user').html(data.html.bid_latest_user);
            //          $('#detail_bid_price').html(data.html.bid_price);
            //          $('#detail_count_down').html(data.html.count_down);
            //          $('#pending_bid_count').html(data.html.pending_bid_count);
            //     },
            //     error:function(error){
            //     }
            //   });

            if($('div.bootbox-alert').is(':visible')){
               clearInterval(timeOutDetail);
            }
         }, 1000);
      
         // Function for getting the result/static for user 
         // implmentation is pending

      function getUserActivity(id) {
         $.ajax({
             type:"GET",
             url:"{{ route('user_activity_detail') }}",
             data:{user_id: id},
             dataType: "json",
             success:function(data){
                  console.log(data);
             },
             error:function(error){
                // return false; 
                console.log(error);
             },
             complete:function(data){
                  
                   var res = data.responseJSON;
              //    $('#add_to_recall'+id).closest('li').waitMe('hide');
                  if(data.status == 403){
                     //$('#recall_auction'+id).addClass('alert-danger').html(res.message).show();
                  }
                  else if(data.status == 500){
                    // alert('not allowed  please refresh the page');
                  }
                  else {
                    // alert('not allowed');
                  }
                  fadeOutAlertMessages();

             },
         });
      
      }

      // function to setup the bid agent on click
      function setAuctionAutomate(id) {
         // alert($('#pending_bid_count').html());
         var ids = '#bid_agent_user_login';
         $(ids).hide();
         var bidCount = $("input[name='number_of_bid']").val();
         var start_type = $("#select_agent_time option:selected" ).val();
         var pending_bid_count = $('#pending_bid_count').html();
            let hitajax = false;
            var reg = new RegExp('/^\d+$/');
            if(bidCount == ''){
              $('#bid_number').html("{{ trans('message.bid_number_empty') }}").show();
            }
            else if(bidCount <= 0 || reg.test(bidCount)){
               $('#bid_number').html("{{ trans('message.bid_number_zero') }}").show();
            }
            else if(bidCount == 1){
               setOneBid(id);
            }
            else {
             //  $('#bid_number').html('').hide();
               hitajax = true;
            }
            
            // ajax to check user login before setup a bid agent

            if(hitajax){ 
               $.ajax({
                  type:"GET",
                  url:"{{ route('frontend_login_check') }}",
                  data:{id: id,bidCount:bidCount,start_type:start_type},
                  dataType: "json",
                  beforeSend:function(){

                     $('#setup_bid').waitMe();
                  },
                  success:function(data){
                  if(data.isLoggedIn){
                     $('input[type=hidden][name=user_id]').val(data.user_id);
                     $('input[type=hidden][name=user_id]').attr('data-value',data.userid);
                  }
                  else {
                      var ids = '#bid_agent_user_login';
                      $(ids).html(data.message);
                      $(ids).show();
                  }
                  },
                  error:function(error){
                    $('#setup_bid').waitMe('hide'); 
                    return true; 
                  },
                  complete:function(data){
                     //alert(data);
                     $('#setup_bid').waitMe('hide'); 
                     if(data.responseJSON.isLoggedIn){
                           if(data.responseJSON.count){
                              setupBidAgent(data.responseJSON.id);
                           }else if(pending_bid_count != 0){
                              setupBidAgent(data.responseJSON.id);
                           }     
                           else {
                              $(ids).html(data.responseJSON.message);
                              $(ids).show();
                           }
                     }
                  }         
               });
            }

 

            $('.slider_data').click(function(e){
               console.log($(this));
               e.preventDefault();
               console.log();
            });
      }
</script>
@endsection
