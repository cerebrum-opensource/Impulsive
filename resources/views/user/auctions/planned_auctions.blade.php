@if(count($plannedAuctions))
@foreach($plannedAuctions as $plannedAuction)
            
            
         @php

         if($plannedAuction->product->feature_image_1)
            $image = $plannedAuction->product->feature_image_1;
         if($plannedAuction->product->feature_image_2)
            $image = $plannedAuction->product->feature_image_2;
         if($plannedAuction->product->feature_image_3)
            $image = $plannedAuction->product->feature_image_3;
         if($plannedAuction->product->feature_image_4)
            $image = $plannedAuction->product->feature_image_4;
         @endphp
         @php
            $plannedAucId = encrypt_decrypt('encrypt', $plannedAuction->id);
            $type = encrypt_decrypt('encrypt', '0');

            $productSeller = $plannedAuction->product->seller ? $plannedAuction->product->seller->full_name : 'Winimi';
         @endphp
          <li>
            <span class="img-upper">
               <span class="count-inner1"> 
                  <span class="flower-text">
                  <img src="{{ asset('images/flower2.png') }}"><span class="inner-text-flower">{{ $plannedAuction->final_countdown }} {{ trans('label.seconds') }}<span>{{ trans('label.countdown') }}</span></span>
                  </span>
                 
                  <span class="count-inner count-inner2">
                    <span id="auction_start_date">{{ trans('label.start_on') }}  {{date("d.m.Y H:i", strtotime($plannedAuction->start_date))}}</span>
                    <span style="display: none" id="planned_count_down{{$plannedAucId}}">00:00:00</span>
                  </span>
                  
                 
               </span>

                 <script type="text/javascript">
                  
                  var countDownDate{{$plannedAuction->id}} = new Date("{{$plannedAuction->start_date}}").getTime();
                  
                  var x@php echo $plannedAuction->id @endphp = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countDownDate{{$plannedAuction->id}} - now;
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    if(hours < 10){
                        hours = '0'+hours;
                    }
                    if(seconds < 10){
                        seconds = '0'+seconds;
                    }
                    if(minutes < 10){
                        minutes = '0'+minutes;
                    }
                    document.getElementById("planned_count_down{{$plannedAucId}}").innerHTML = hours + ":"
                    + minutes + ":" + seconds;

                    // If the count down is finished, write some text 
                    if (distance < 0) {
                      clearInterval(x@php echo $plannedAuction->id @endphp);
                      document.getElementById("planned_count_down{{$plannedAucId}}").innerHTML = "Lived";
                      $('#planned_count_down{{$plannedAucId}}').closest('li').remove();
                    }
                  }, 1000);

                  
               </script>
               <span class="img-count">
               <img src="@if($image) {{ asset('/images/admin/products/').'/'.$plannedAuction->product->id.'/'.$image }} @else {{ asset('images/Produkt.png') }} @endif"  class="box-img2" alt="{{ $plannedAuction->product->product_title }}" title="{{ $plannedAuction->product->product_title }}">
               </span>
            </span>
            <span class="text-lower">
            <span class="text1a">{{ $plannedAuction->product->product_title }}</span>
            @if($plannedAuction->product->show_seller)
              <span class="text1a text2a">{{ $productSeller }} </span>
            @else
              <span class="text1a text2a">&nbsp </span>
            @endif

            <span class="text1a text2a">{{ trans('label.regular_price') }}: {{ price_reflect_format($plannedAuction->product->product_price) }} {{ CURRENCY_ICON }}</span>
            <div class="outer-box">
              <a class="text1a text5a green-bg add_to_recall"  id="add_to_recall{{$plannedAucId}}" onClick="javascript:addTorecall('{{$plannedAucId}}','{{$type}}')">{{ trans('label.recall') }}</a>

              <div class="alert messageTip" style="display: none;" id="recall_auction{{$plannedAucId}}" role="alert">
                                
              </div>
            </div>
          <!--  <span class="text1a text3a greentext">€ 29,99</span>
            <a class="text1a text5a green-bg">Erinnern</a> -->
            </span>
         </li>
@endforeach
@else

<span id="no_live">{{ trans('label.no_planned_auctions') }} </span>

@endif