@if(count($expiredAuctions))
@foreach($expiredAuctions as $expiredAuction)
        @php
         if($expiredAuction->product->feature_image_1)
            $image = $expiredAuction->product->feature_image_1;
         if($expiredAuction->product->feature_image_2)
            $image = $expiredAuction->product->feature_image_2;
         if($expiredAuction->product->feature_image_3)
            $image = $expiredAuction->product->feature_image_3;
         if($expiredAuction->product->feature_image_4)
            $image = $expiredAuction->product->feature_image_4;


        $productSeller = $expiredAuction->product->seller ? $expiredAuction->product->seller->full_name : 'Winimi';
        @endphp
    <li>
        <span class="img-upper">
       <span class="count-inner1">
         <span class="flower-text flower-text3"><img src="{{ asset('images/flower3.png') }}"><span class="inner-text-flower">
            @php
                $marked_price = $expiredAuction->product->product_price;
                $bid_price = $expiredAuction->bid_price;
                $reduced_price = $marked_price - $bid_price;
                $reduced_percent = ($marked_price - $bid_price) / $marked_price * 100;
                $reduced_percent = round($reduced_percent);
             
                if($reduced_percent >= 100)
                {
                    $reduced_percent = 99;
                }
            @endphp
            -{{ $reduced_percent }}%
        </span></span> 
        </span>
        <span class="img-count">
        <img src="@if($image) {{ asset('/images/admin/products/').'/'.$expiredAuction->product->id.'/'.$image }} @else {{ asset('images/tool.png') }} @endif"  class="box-img2" alt="tool" title="tool">
        </span>
        </span>
        <span class="text-lower">
        <span class="text1a">{{ $expiredAuction->product->product_title }}</span>

        @if($expiredAuction->product->show_seller)
          <span class="text1a text2a">{{ $productSeller }} </span>
        @else
          <span class="text1a text2a">&nbsp </span>
        @endif
        <span class="text1a text2a">WARENWERT: {{ price_reflect_format($expiredAuction->product->product_price) }}  {{ CURRENCY_ICON }} </span>
        <span class="text1a text3a greentext2"> {{ price_reflect_format($expiredAuction->bid_price) }} {{ CURRENCY_ICON }}</span>

        @if(count($expiredAuction->auctionWinner))
        <span class="text1a text3b">{{ trans('label.sold_to') }}: {{ $expiredAuction->auctionWinner[0]->user ? $expiredAuction->auctionWinner[0]->user->username : trans('label.not_sold') }}</span>
        @else
        <span class="text1a text3b">{{ trans('label.sold_to') }} </span>
        @endif
        </span>
    </li>
@endforeach
@else

<span id="no_live">{{ trans('label.no_expired_auctions') }} </span>

@endif