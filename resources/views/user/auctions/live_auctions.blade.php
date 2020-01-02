<div class="live-deals">
   <div class="container">
      <h3 class="sec-heading"><span>{{ trans('label.live') }}</span> {{ trans('label.deals') }}</h3>
      <ul>
         @if(count($liveAuctions))
            @foreach($liveAuctions as $liveAuction)


            @php
            if($liveAuction->product->feature_image_1)
               $image = $liveAuction->product->feature_image_1;
            if($liveAuction->product->feature_image_2)
               $image = $liveAuction->product->feature_image_2;
            if($liveAuction->product->feature_image_3)
               $image = $liveAuction->product->feature_image_3;
            if($liveAuction->product->feature_image_4)
               $image = $liveAuction->product->feature_image_4;

           $nowDate = \Carbon\Carbon::now()->timezone($timezone)->format('Y-m-d H:i:s');
           $diff = strtotime($liveAuction->end_time) - strtotime($nowDate);
           $diffwithCountdown = strtotime($liveAuction->final_countdown_start_time) - strtotime($nowDate);
           $countDownStart = 0;
           $status = 1;

             // Send the last bidder id and user name in response.
            if(count($liveAuction->bidHistory)){
                 $userData = $liveAuction->bidHistory[0]->user;
                 $name = $userData->username;
                 $user_id = $userData->id;
            }
            else {
                $name = '';
                $user_id = 0;
            }
             // check is the bid time is over or not
               if($diff > 0){
                    $timeStamp = seconds_to_h_m_s($diff);
               }
               else {
                   // if bid time is over complete the bid and assign the winner to it.
                    if($auction->status == 1) {
                        $timeStamp = 'Check';
                    } else {
                        $timeStamp = 'End';
                    }
                    //$timeStamp = '00:00:00';

                  //$timeStamp = '00:00:00';
                  $status = 0;
                  $auctionWinner['auction_queue_id'] = $liveAuction->id;
                  $auctionWinner['user_id'] = $user_id;
                  $auctionWinner['auction_id'] = $liveAuction->auction_id;
                  $auctionWinner['product_id'] = $liveAuction->product_id;
                  $auctionWinner['bid_count'] = $liveAuction->bid_count;
                  $auctionWinner['bid_price'] = $liveAuction->bid_price;
                  AuctionWinner::create($auctionWinner);
                  AuctionQueue::where('id',$liveAuction->id)->update(['status'=>AuctionQueue::NOT_ACTIVE]);
               }
               if($diffwithCountdown > 0){

               }
               else {
                   $countDownStart = 1;
               }


            @endphp
               @php
                  $aucId = encrypt_decrypt('encrypt', $liveAuction->id);
               @endphp

            <li id="auction{{$aucId}}">
               <span class="img-upper">
                  <span class="count-inner1">
                     <span class="flower-text">
                     <img src="{{ asset('images/flower2.png') }}">
                     <span class="inner-text-flower">{{ $liveAuction->final_countdown }} {{ trans('label.seconds') }}.<span>{{ trans('label.countdown') }}</span></span>
                     </span>
                     <span class="count-inner">
                        <span id="count_down{{$aucId}}">{{ $timeStamp }}</span>

                        <ul class="count-inner2">
                           <li>{{ trans('label.hours') }}</li>
                           <li>{{ trans('label.minutes') }}</li>
                           <li>{{ trans('label.seconds') }}</li>
                        </ul>
                     </span>
                  </span>
                  <a href="{{ route('frontend_auction_detail',[encrypt_decrypt('encrypt', $liveAuction->id)])}}">
                  <span class="img-count">
                     <img src="@if($image) {{ asset('/images/admin/products/').'/'.$liveAuction->product->id.'/'.$image }} @else {{ asset('images/tool.png') }} @endif"  class="box-img2" alt="{{ $liveAuction->product->product_title }}" title="{{ $liveAuction->product->product_title }}">
                  </span>
                 </a>
               </span>
               <span class="text-lower ">
               <span class="text1a">{{ $liveAuction->product->product_title }} </span>
               <span class="text1a text2a">{{ trans('label.value') }}: {{ CURRENCY_ICON }} {{ $liveAuction->product->product_price }} </span>
               <span class="text1a text3a" id="bid_price{{$aucId}}">{{ CURRENCY_ICON }} {{ $liveAuction->bid_price }}</span>
               @if(count($liveAuction->bidHistory))
               <span class="text1a text4a" id="bid_latest_user{{$aucId}}">{{ $liveAuction->bidHistory[0]->user->username }}</span>
               @else
               <span class="text1a text4a" id="bid_latest_user{{$aucId}}"></span>
               @endif
               <a class="text1a text5a buy_now_btn" id="buy_now{{$aucId}}" onClick="javascript:setOneBid('{{$aucId}}')" >{{ trans('label.offer') }}<span><img src="{{ asset('images/cart.png') }}" alt="cart" title="cart"></span></a>
               <div class="messageTip alert-warning" id="bid_user_login{{$aucId}}" style="display: none"> </div>
               </span>
               <input type="hidden" name="live_auctions[]" value="{{$aucId}}">
               <input type="hidden" name="countdown_start[]" id="countdown_start{{$aucId}}" value="{{$countDownStart}}">
               <input type="hidden" name="highest_bidder[]" id="highest_bidder{{$aucId}}" value="{{$user_id}}">
            </li>
            @endforeach
         @else
            <h2> No Live Auctions</h2>
         @endif
      </ul>
   </div>
</div>


