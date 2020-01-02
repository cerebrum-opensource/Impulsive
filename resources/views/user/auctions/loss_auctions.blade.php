@if(count($lossBids['html']))
@foreach($lossBids['html'] as $lossBid)
  @php
    $product = $lossBid->auction->product;
    if($product->feature_image_1)
      $image = $product->feature_image_1;
    if($product->feature_image_2)
      $image = $product->feature_image_2;
    if($product->feature_image_3)
      $image = $product->feature_image_3;
    if($product->feature_image_4)
      $image = $product->feature_image_4;

    $recallAucId = encrypt_decrypt('encrypt', $lossBid->id);

    $userBidCount = isset($lossBids['auction_count'][$lossBid->id]) ? $lossBids['auction_count'][$lossBid->id] : 0;

    //echo $userBidCount;
    $discountPrice = ($product->product_price + $product->shipping_price) - ($userBidCount * $discount_per_bid_price);
    $discount = ($userBidCount) * ($discount_per_bid_price);

  //  echo $userBidCount;

  //print_r($lossBids['auction_count']);
  //echo $lossBid->id;
  //echo $discount;

  //echo $userBidCount;
  @endphp

<li>
   <span class="img-upper">
    <span class="count-inner1">
        <span class="flower-text flower-text3"><img src="/images/flower3.png"><span class="inner-text-flower">-50%</span></span>
        </span>
      <span class="img-count">
      <img src="@if($image) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$image }} @else {{ asset('images/Produkt.png') }} @endif"  class="box-img2" alt="{{ $product->product_title }}" title="{{ $product->product_title }}">
      </span>
   </span>
   <span class="text-lower">
   <span class="text1a">{{ trans('label.bids') }} {{ $lossBid->bid_count }} </span>
   <span class="text1a">{{ $product->product_title }}</span>
   <span class="text1a text2a">{{ trans('label.regular_price') }}: {{ price_reflect_format($product->product_price) }} {{ CURRENCY_ICON }},-</span>
   <span class="text1a text2a">{{ trans('label.discount_price') }}: {{ $discount }} {{ CURRENCY_ICON }}</span>
   <span class="text1a text2a">{{ trans('label.shiping_price') }}:  {{ $product->shipping_price ? price_reflect_format($product->shipping_price).' '.CURRENCY_ICON : '' }}</span>
   <span class="text1a text2a">{{ trans('label.buy') }}: € {{ $discountPrice }}</span>

   
     @if(in_array($lossBid->id,$buyInstantPurchase))
     <a class="text1a text5a" data-id="{{ $recallAucId }}" title="{{ trans('message.already_purchased') }}">{{ trans('label.purchased') }}</a> 
    @else
    <a class="text1a text5a buy_now_btn"  data-type="{{ encrypt_decrypt('encrypt', 'lose') }}" data-id="{{ $recallAucId }}" data-amount="{{ encrypt_decrypt('encrypt', $discountPrice) }}">{{ trans('label.buy') }}</a> 
    @endif

   </span>
</li>
@endforeach
@else
<span id="no_live">{{ trans('label.no_loss_list') }} </span>
@endif
<?php  //echo $lossBids->render(); ?>