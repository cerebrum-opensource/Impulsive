@if(count($queueAuctions))
@foreach($queueAuctions as $queueAuction)
            
            
         @php
         if($queueAuction->product->feature_image_1)
            $image = $queueAuction->product->feature_image_1;
         if($queueAuction->product->feature_image_2)
            $image = $queueAuction->product->feature_image_2;
         if($queueAuction->product->feature_image_3)
            $image = $queueAuction->product->feature_image_3;
         if($queueAuction->product->feature_image_4)
            $image = $queueAuction->product->feature_image_4;
         @endphp
         @php
            $plannedAucId = encrypt_decrypt('encrypt', $queueAuction->id);
             $type = encrypt_decrypt('encrypt', '0');

             $productSeller = $queueAuction->product->seller ? $queueAuction->product->seller->full_name : 'Winimi';
         @endphp
         <li>
            <span class="img-upper">
               <span class="count-inner1">
                  <span class="flower-text">
                  <img src="{{ asset('images/flower2.png') }}"><span class="inner-text-flower">{{ $queueAuction->final_countdown }} {{ trans('label.seconds') }}<span>{{ trans('label.countdown') }}</span></span>
                  </span>
                  <span class="count-inner count-inner2">
                     <span id=""></span>
                     <ul class="count-inner2">
                        <li></li>
                        <li></li>
                        <li></li>
                     </ul>
                  </span>
               </span>

                
               <span class="img-count">
               <img src="@if($image) {{ asset('/images/admin/products/').'/'.$queueAuction->product->id.'/'.$image }} @else {{ asset('images/Produkt.png') }} @endif"  class="box-img2" alt="{{ $queueAuction->product->product_title }}" title="{{ $queueAuction->product->product_title }}">
               </span>
            </span>
            <span class="text-lower">
            <span class="text1a">{{ $queueAuction->product->product_title }}</span>

            @if($queueAuction->product->show_seller)
              <span class="text1a text2a">{{ $productSeller }} </span>
            @else
              <span class="text1a text2a">&nbsp </span>
            @endif
            <span class="text1a text2a">{{ trans('label.regular_price') }}: {{ price_reflect_format($queueAuction->product->product_price) }} {{ CURRENCY_ICON }},-</span>
            <a class="text1a text5a green-bg add_to_recall"  id="add_to_recall{{$plannedAucId}}" onClick="javascript:addTorecall('{{$plannedAucId}}','{{$type}}')" >{{ trans('label.recall') }}</a>

            <div class="alert" style="display: none;" id="recall_auction{{$plannedAucId}}" role="alert">
                              
            </div>
            </span>
         </li>
@endforeach
@else

<span id="no_live">{{ trans('label.no_coming_auctions') }} </span>

@endif