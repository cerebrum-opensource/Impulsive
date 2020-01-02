 @inject('settingModal','App\Models\Setting')

@if(count($liveAuctions))
@php
 $liveAuctions = $liveAuctions->sortBy('duration_count');
@endphp
@foreach($liveAuctions as $liveAuction)
@php
$product_id = encrypt_decrypt('encrypt', $liveAuction->product_id);
if($liveAuction->product->feature_image_1)
$image = $liveAuction->product->feature_image_1;
if($liveAuction->product->feature_image_2)
$image = $liveAuction->product->feature_image_2;
if($liveAuction->product->feature_image_3)
$image = $liveAuction->product->feature_image_3;
if($liveAuction->product->feature_image_4)
$image = $liveAuction->product->feature_image_4;





/*if($diff > 0){
$timeStamp = seconds_to_h_m_s($diff);
}
else {
$timeStamp = '00:00:00';
$status = 0;
}
*/

    $nowDate = \Carbon\Carbon::now()->timezone($timezone)->format('Y-m-d H:i:s');
    $diff = strtotime($liveAuction->end_time) - strtotime($nowDate);




     $setting = $settingModal::first();

      $nowDateGermany = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s');
     $auctionStartTime = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d').' '.$setting->break_start_time;
    $auctionEndTime   = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d').' '.$setting->break_end_time;

    $break = auction_time_helper($auctionStartTime,$auctionEndTime);

    $nowDateGermany = \Carbon\Carbon::now()->timezone('Europe/Berlin')->format('Y-m-d H:i:s');

    $diffGermanyEnd = strtotime($auctionEndTime) - strtotime($nowDateGermany);

    $breakTime = date("g:i ", strtotime($auctionStartTime));






if($liveAuction->duration_count > 0){
   if($liveAuction->duration_count > $diffGermanyEnd)
        $timerTime = $liveAuction->duration_count;
    else
        $timerTime = $liveAuction->duration_count;

    $timeStamp = seconds_to_h_m_s($timerTime);
   }
   else {
        if($liveAuction->status == 1) {
            $timeStamp = 'Check';
        } else {
            $timeStamp = 'End';
        }
        //$timeStamp = '00:00:00';
      //$timeStamp = '00:00:00';
      $status = 0;
   }


@endphp
@php
$aucId = encrypt_decrypt('encrypt', $liveAuction->id);
$plannedAucId = encrypt_decrypt('encrypt', $liveAuction->auction_id);
$type = encrypt_decrypt('encrypt', '1');

   $watchListImage = asset('images/watchlist-on.png');
   if($liveAuction->wishList){
      $watchListImage = asset('images/watchlist-off.png');
   }
$productSeller = $liveAuction->product->seller ? $liveAuction->product->seller->full_name : 'Winimi';
@endphp
<li id="auction{{$aucId}}" class="list-view">
   <span class="img-upper">
   <span class="view-img"><a class="add_to_recall"  id="add_to_recall{{$plannedAucId}}" onClick="javascript:saveWishList('{{$plannedAucId}}','{{$type}}')"><img src="{{ $watchListImage }}" id="wishlist_auction{{$plannedAucId}}" ></a></span>

   <a href="{{ route('frontend_auction_detail',[encrypt_decrypt('encrypt', $liveAuction->id)])}}">
   <span class="img-count">
   <img src="@if($image) {{ asset('/images/admin/products/').'/'.$liveAuction->product->id.'/'.$image }} @else {{ asset('images/tool.png') }} @endif"  class="box-img2" alt="{{ $liveAuction->product->product_title }}" title="{{ $liveAuction->product->product_title }}">
   </span>
   </a>
   </span>
   <span class="text-lower list-view-text">
    @if($break)
      <div class="break_time_div break_time_div_detail" id="break_time{{$aucId}}">
      @else
      <div class="break_time_div break_time_div_detail hide_break_time" id="break_time{{$aucId}}">
      @endif
        <h3>{{ trans('label.break_time') }} </h3>
        <h4 id="break_time_h4{{$aucId}}">{{ trans('label.counitnue_at') }} {{ $breakTime }} {{ trans('label.clock') }} </h4>
      </div>

      <span class="text1a">{{ $liveAuction->product->product_title }} </span>
       @if($liveAuction->product->show_seller)
          <span class="text1a text2a">{{ $productSeller }} </span>
      @endif
      <span class="text1a text2a">{{ trans('label.value') }}: {{ $liveAuction->product->product_price }}  {{ CURRENCY_ICON }} </span>
      <span class="count-inner1">
         <span class="flower-text"><img src="{{ asset('images/flower2.png') }}"><span class="inner-text-flower">{{ $liveAuction->final_countdown }} {{ trans('label.seconds') }}<span>{{ trans('label.countdown') }}</span></span></span>
         <span class="count-inner">

            @if($liveAuction->status == 0)
            <span >Expired</span>

            @else
            <span id="count_down{{$aucId}}">{{ $timeStamp }}</span>
            <ul class="count-inner2">
               <li>{{ trans('label.hours') }}</li>
               <li>{{ trans('label.minutes') }}</li>
               <li>{{ trans('label.seconds') }}</li>
            </ul>
            </span>

            @endif


      </span>
      <span class="text1a text3a" id="bid_price{{$aucId}}"> {{ price_reflect_format($liveAuction->bid_price) }} {{ CURRENCY_ICON }} ({{$liveAuction->bid_count}})</span>
      @if(count($liveAuction->bidHistory))
      <span class="text1a text4a" id="bid_latest_user{{$aucId}}">{{ $liveAuction->bidHistory[0]->user->username }}</span>
      @else
      <span class="text1a text4a" id="bid_latest_user{{$aucId}}"></span>
      @endif
      <div class="outer-box">
          @if($liveAuction->status == 1)
         <!--  <input class="form-control" type="number" min="1" max="10" id="example-number-input"> -->
         <a class="text1a text5a buy_now_btn" id="buy_now{{$aucId}}" onClick="javascript:setOneBid('{{$aucId}}')" >{{ trans('label.offer') }}</a>

         <a class="text1a text5a" href="{{ route('get_product_detail',$product_id) }}" ><span><img src="{{ asset('images/cart.png') }}" alt="cart" title="cart"></span></a>
           @endif
         <div class="messageTip" id="bid_user_login{{$aucId}}" style="display: none"> </div>
      </div>
   </span>
    @if($liveAuction->status == 1)
   <input type="hidden" name="live_auctions[]" value="{{$aucId}}">
   @endif
   <input type="hidden" name="countdown_start[]" id="countdown_start{{$aucId}}" value="">
   <input type="hidden" name="highest_bidder[]" id="highest_bidder{{$aucId}}" value="">
</li>
@endforeach
@else
<span id="no_live">{{ trans('message.wishlist_empty') }} </span>
<span id="no_live">{{ trans('message.wishlist_empty_select') }} <a href="{{ route('auction_overview') }}"> {{ trans('label.auction') }} </a> {{ trans('message.wishlist_empty_overview') }} </span>
@endif
