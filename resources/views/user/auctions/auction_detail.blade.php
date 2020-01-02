@extends('layouts.app')
@section('title')
 {{ trans('label.auctions') }}
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
   .product1 .text3a {
    font-size: 75px;
   }
</style>
@endsection
@section('content')
@inject('settingModal','App\Models\Setting')

@php
   $imgdetail = '';
//   $array[] = '';
   if($auction->product->feature_image_4){
      $array[] = $auction->product->feature_image_4;
      $imgdetail = $auction->product->feature_image_4;
   }
   if($auction->product->feature_image_3 && $imgdetail ==''){

      $imgdetail =  $auction->product->feature_image_3;
   }
   if($auction->product->feature_image_2 && $imgdetail ==''){

      $imgdetail =  $auction->product->feature_image_2;
   }
   if($auction->product->feature_image_1 && $imgdetail ==''){

      $imgdetail =  $auction->product->feature_image_1;
   }

   if($auction->product->feature_image_3 ){
      $array[] = $auction->product->feature_image_3;

   }
   if($auction->product->feature_image_2 ){
      $array[] = $auction->product->feature_image_2;

   }
   if($auction->product->feature_image_1 ){
      $array[] = $auction->product->feature_image_1;
   }



   $aucId = encrypt_decrypt('encrypt', $auction->id);


   $setting = $settingModal::first();
   $nowDateGermany = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s');


   $auctionStartTime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d').' '.$setting->break_start_time;
   $auctionEndTime   = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d').' '.$setting->break_end_time;

  $break = auction_time_helper($auctionStartTime,$auctionEndTime);

   $diffGermanyEnd = strtotime($auctionEndTime) - strtotime($nowDateGermany);

  $breakTime = date("g:i ", strtotime($auctionStartTime));



   if($auction->duration_count > 0){
       if($auction->duration_count > $diffGermanyEnd)
           $timerTime = $auction->duration_count;
       else
           $timerTime = $auction->duration_count;

       $timeStamp = seconds_to_h_m_s($timerTime);
   }
   else {
        if($auction->status == 1) {
            $timeStamp = 'Check';
        } else {
            $timeStamp = 'End';
        }
        //$timeStamp = '00:00:00';
   }

   $bidAgentStartOptions = bid_agent_start_type();

   /*
   if($auction->duration_count < 14400){
      unset($bidAgentStartOptions[FOUR_HOURS]);
   }
*/
   $productSeller = $auction->product->seller ? $auction->product->seller->full_name : 'Winimi';


   $product_id = encrypt_decrypt('encrypt', $auction->product_id);

@endphp
<div class="product-otr">
   <div class="container">
      <h3 class="sec-heading"><span>{{ $auction->product->product_title }}</span></h3>
      <div class="product-inner">
         <div class="row">
            <div class="col-md-8">
               <div class="product-inner">


                  <span class="flower-text"><img src="{{ asset('images/flower2.png') }}"><span class="inner-text-flower">{{ $auction->final_countdown }} {{ trans('label.seconds') }}<span>{{ trans('label.countdown') }}</span></span></span>
                  <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($imgdetail) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$imgdetail }} @else {{ asset('images/mobile-large.png') }} @endif" id="feature_img">
                  <img width="650px" height="650px" class="image_responsive"  src="@if($imgdetail) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$imgdetail }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $auction->product->product_title }}" title="{{ $auction->product->product_title }}">
                  </a>
                  <ul>
                      @if(@$array[0])
                     <li>
                        <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($array[0]) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$array[0] }} @else {{ asset('images/mobile-large.png') }} @endif" >
                        <img class="pro_img_width"  src="@if($array[0]) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$array[0] }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $auction->product->product_title }}" title="{{ $auction->product->product_title }}" onmouseover="set_feature_image(this)">
                        </a>
                     </li>
                     @endif
                     @if(@$array[1])
                     <li>
                        <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($array[1]) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$array[1] }} @else {{ asset('images/mobile-large.png') }} @endif" >
                        <img class="pro_img_width"  src="@if($array[1]) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$array[1] }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $auction->product->product_title }}" title="{{ $auction->product->product_title }}" onmouseover="set_feature_image(this)">
                        </a>
                     </li>
                     @endif
                     @if(@$array[2])
                     <li>
                        <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($array[2]) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$array[2] }} @else {{ asset('images/mobile-large.png') }} @endif" >
                        <img class="pro_img_width" src="@if($array[2]) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$array[2] }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $auction->product->product_title }}" title="{{ $auction->product->product_title }}" onmouseover="set_feature_image(this)">
                        </a>
                     </li>
                     @endif
                     @if(@$array[3])
                     <li>
                        <a class="slider_data" data-lightbox="mobile-large" data-title="" data-lightbox="roadtrip" href="@if($array[3]) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$array[3] }} @else {{ asset('images/mobile-large.png') }} @endif" >
                        <img class="pro_img_width" src="@if($array[3]) {{ asset('/images/admin/products/').'/'.$auction->product->id.'/'.$array[3] }} @else {{ asset('images/mobile-large.png') }} @endif"title="{{ $auction->product->product_title }}" title="{{ $auction->product->product_title }}" onmouseover="set_feature_image(this)">
                        </a>
                     </li>
                     @endif
                  </ul>
               </div>
            </div>
            <div class="col-md-4">
               <div id="bid_history_div">
                  @include('user.auctions.bid_history')
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


                        @if($break)
                           <div class="break_time_div break_time_div2" id="break_time{{$aucId}}">
                           @else
                           <div class="break_time_div break_time_div2 hide_break_time" id="break_time{{$aucId}}">
                           @endif
                             <h3>{{ trans('label.break_time') }} </h3>
                             <h4 id="break_time_h4{{$aucId}}">{{ trans('label.counitnue_at') }} {{ $breakTime }} {{ trans('label.clock') }} </h4>
                           </div>

                        <span class="text1a">{{ $auction->product->product_title }}</span>
                        <span class="text1a text2a">{{ trans('label.regular_price') }}: {{ price_reflect_format($auction->product->product_price) }}&nbsp;{{ CURRENCY_ICON }}</span>
                     </div>
                  </div>
               </div>
               <div class="row product1">
                  <div class="col-md-8">
                     <span class="text1a text3a" id="detail_bid_price{{$aucId}}"> {{ price_reflect_format($auction->bid_price) }} {{ CURRENCY_ICON }}</span>
                     @if(count($auction->bidHistory))
                     <span class="text1a text4a text-5a" id="detail_bid_latest_user{{$aucId}}">{{ $auction->bidHistory[0]->user->username }}</span>
                     @else
                     <span class="text1a text4a text-5a" id="detail_bid_latest_user{{$aucId}}"> {{ trans('label.no_bidder') }}</span>
                     @endif
                  </div>
                  @if($auction->status)
                  <div class="col-md-4">
                     @if($auction->status)
                                       <div class="bid-agent-counter">
									   <span id="pending_bid_count" class="bid_count_number" style='color:#fece0a;font-weight: bold; font-size: 25px;'> {{ $pendingBidCount ?? ''}} </span>&nbsp;
                    @if($bidAgentSetup)

                        <span style="white-space: nowrap ;"> {{ trans('message.bid_agent_is_setup') }} </span>
					@else
                     <span id = "pending_bid_count_message" style="white-space: nowrap;"></span>
                     @endif

									   @endif

                     <span class="img-upper img-upper2">
                        <span class="count-inner1">
                           <span class="count-inner count-inner2">
                              <span id="detail_count_down{{$aucId}}"> {{ $timeStamp }}</span>
                              <ul class="count-inner2">
                                 <li>{{ trans('label.hours') }}</li>
                                 <li>{{ trans('label.minutes') }}</li>
                                 <li>{{ trans('label.seconds') }}</li>
                              </ul>
                           </span>
                        </span>
                     </span>
                  </div>
                  @endif
               </div>
               @if($auction->status)
               <div id="setup_bid" class="row">
                  <div class="col-md-12">
                     <li>
                     <div class="bienten-otr">
					 <div class="gebotseinstellungen">
                        <span class="text-lower text-lower2 pr-2">
                        <a class="text1a text5a">{{ trans('label.set_winimis_agent') }}</a>
                        <input type="number" class="ml-2" name="number_of_bid" value="1">
                        </span>
                        <span class="text-lower text-lower2 pr-2">
                            <select name="start_time"  id="select_agent_time" class="form-control detail_select ml-2">
                              @foreach($bidAgentStartOptions as $key=> $option)
                                 <option value="{{$key}}">{{ $option }}</option>
                              @endforeach
                            </select>
                        </span>
						</div>
					<div class="gebotsbutton">
						 <div class="button-outer ">
							<div class="outer-box">
							<a class="text1a text5a text6a" id="setup_bid_agent" onClick="javascript:setAuctionAutomate('{{ encrypt_decrypt('encrypt',$auction->id)}}')" >{{ trans('label.offer') }}</a>

							 <a class="text1a text5a buy_now_btn" id="buy_now{{$aucId}}" href="{{ route('get_product_detail',$product_id) }}" ><span><img src="{{ asset('images/cart.png') }}" alt="cart" title="cart"></span></a>

							  <div class="messageTip" id="bid_user_login{{$aucId}}" style="display: none"> </div>
							  <div class="messageTip" id="bid_agent_user_login" style="display: none"> </div>
							</div>
						 </div>
					 </div>

                     </div>
                     <span class="error" id="bid_number" style="color:red"></span>
                     </li>
                  </div>
               </div>
               @endif
            </div>
			</div>
			<div class="col-md-4">
               <div class="table-otr table-otr2">
                  <h3>{{ trans('label.auction_detail') }}</h3>
                  <p>{{ $auction->auction->detail }}</p>
                  <table class="table table-responsive table-condensed">
                     <tbody>
                        @if($auction->product->show_seller)
                        <tr>
                           <td style="width:200px;"><b>{{ trans('label.seller_name') }}</b></td>
                           <td style="width:200px;">{{ $productSeller }} </td>
                        </tr>
                        @endif
                        <tr>
                           <td style="width:200px;"><b>{{ trans('label.countdown') }}</b></td>
                           <td style="width:200px;">{{ $auction->final_countdown }} {{ trans('label.seconds') }}</td>
                        </tr>
                        <tr>
                           <td><b> {{ trans('label.status') }}</b></td>
                           <td>{{ $auction->status_name }}</td>
                        </tr>
                        <tr>
                           @if($auction->auction->start_date)
                           <td><b> {{ trans('label.auction_start') }} </b></td>
                           <td>{{ $auction->auction->start_date }}:00</td>
                           @endif
                        </tr>

                        <tr>
                           <td><b> {{ trans('label.shipping') }} </b></td>
                           <td>{{ $auction->product->shipping_price ? price_reflect_format($auction->product->shipping_price).' '.CURRENCY_ICON : trans('label.free_shipping') }}
                           </td>
                        </tr>
                     </tbody>
                  </table>
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
               echo $auction->product->product_long_desc;
            @endphp
            <p></p>
         </div>
			<span class="more-text-button">Mehr lesen</span>
      </div>
   </div>
   </div>
               @if(count($fields))
            @foreach($fields as $key => $field)
            @if($attribute_value[$field->id] !='')
   <div class="container">
      <div class="product-otr2 product-otr4">
         <h3>{{ trans('label.product_detail') }}</h3>
         <div class="row">

            <div class="col-sm-6">
               <div class="product-inner1">
                  <ul>
                     <li>{{ $field->name }} </li>
                     <li>{{ $attribute_value[$field->id]}}</li>
                  </ul>
               </div>
            </div>

         </div>
      </div>
   </div>
              @endif
            @endforeach
            @endif
   <div class="live-deals">
      <!--live-deals start-->
	  <!--
      <div class="container">
         <h3 class="sec-heading"><span>{{ trans('label.live') }}</span> {{ trans('label.deals') }}</h3>
         <ul class="live-deals-ul">
            @include('user.auctions.latest_live_auctions')
         </ul>
      </div>
   </div>
   <!--live-deals end-->
   @if (!Auth::guest())
   <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::id()) }}" data-value="{{Auth::id()}}">
   @else
   <input type="hidden" name="user_id" value="0">
   @endif
   <!--live-deals end-->
    <div class="live-deals kommende">

      <div class="container">
         <h3 class="sec-heading sec-heading2"><span>{{ trans('label.planned') }}</span> {{ trans('label.deals') }}</h3>
         <ul class="planned-deals-ul">
            @include('user.auctions.planned_auctions')
         </ul>
      </div>
   </div>
   <!---live-deals kommende end-->
   <!--live-deals end-->
   <!--live-deals end-->

</div>

<!--live-deals end-->
<!--live-deals end-->
@include('news_letter')

@include('support_widget')

@include('seo_page',['page_type' =>AUCTION_OVERVIEW])
<!--service-grid start-->

@endsection
@section('javascript')
@include('layouts.route_name')
<script src="{{ asset('js/lightbox.js') }}"></script>
<script src="{{ asset('js/auction/auction.js') }}" type="text/javascript"></script>
<script type="text/javascript">
   function set_feature_image(img_detail){
    var img_address = img_detail.src;
    $(".image_responsive").attr("src", img_address);

   }
   $(document).ready(function () {
      var auctionsId = $("input[name='live_auctions[]']")
                 .map(function(){return $(this).val();}).get();
      var user_id = $('input[name="user_id"]').val();


     /* if(!$('div.bootbox-alert').is(':visible')){
      setInterval(function()
         {
            var auctionsId = $("input[name='live_auctions[]']")
                 .map(function(){return $(this).val();}).get();
            liveAuctionAjax(auctionsId,user_id);
         }, 1000);
      } */
       var timeOut = setInterval(function()
      {
      var auctionsId = $("input[name='live_auctions[]']")
              .map(function(){return $(this).val();}).get();
      liveAuctionAjax(auctionsId,user_id,0,"{{$auction->id}}");

      if($('div.bootbox-alert').is(':visible')){
         clearInterval(timeOut);
      }

      }, 1000);

   });
     var timeOutDetail = setInterval(function()
         {

            // ajax to load the auction detail in every second for real time result
            $.ajax({
               type:"GET",
                url:"{{ route('frontend_auction_detail',[encrypt_decrypt('encrypt', $auction->id)])}}",
                dataType: "json",
                success:function(data){
                     console.log(data.html.breaktime);
                      var aid = "{{encrypt_decrypt('encrypt', $auction->id)}}";
                     if(data.html.breaktime == 1 ){

                        $('#break_time'+aid).removeClass('hide_break_time');
                        $('#break_time_h4'+aid).html(data.html.breakTimeHtml);
                     }
                     else {
                        $('#break_time'+aid).addClass('hide_break_time');
                        $('#break_time_h4'+aid).html('');
                     }
                     $('#bid_history_div').html(data.html.bid_history);
                     $('#detail_bid_latest_user{{$aucId}}').html(data.html.bid_latest_user);
                     $('#detail_bid_price{{$aucId}}').html(data.html.bid_price);
                     $('#detail_count_down{{$aucId}}').html(data.html.count_down);
                     $('#pending_bid_count').html(data.html.pending_bid_count);
                },
                error:function(error){
                }
              });

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
                      //console.log("hello");
                     $('#setup_bid').waitMe('hide');
                     if(data.responseJSON.isLoggedIn){
                           if(data.responseJSON.count){
                              setupBidAgent(data.responseJSON.id);
                           }else if(pending_bid_count != 0){
                              // console.log(data.responseJSON.id);
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
              // console.log($(this));
               e.preventDefault();
               console.log();
            });
      }
</script>
@endsection
