@if(count($winLists))
@foreach($winLists as $winList)
  @php
    $product = $winList->auction->product;
    if($product->feature_image_1)
      $image = $product->feature_image_1;
    if($product->feature_image_2)
      $image = $product->feature_image_2;
    if($product->feature_image_3)
      $image = $product->feature_image_3;
    if($product->feature_image_4)
      $image = $product->feature_image_4;

    $recallAucId = encrypt_decrypt('encrypt', $winList->id);

    $discountPrice = ($winList->bid_price + $product->shipping_price);
  @endphp

<li>
   <span class="img-upper">

      <span class="img-count">
      <img src="@if($image) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$image }} @else {{ asset('images/Produkt.png') }} @endif"  class="box-img2" alt="{{ $product->product_title }}" title="{{ $product->product_title }}">
      </span>
   </span>
  
   <span class="text-lower">
   <!--<span class="text1a">{{ trans('label.bids') }} {{ $winList->bid_count }} </span> -->
   <span class="text1a">{{ $product->product_title }}</span>
   <span class="text1a text2a warenwert">{{ trans('label.regular_price') }}: {{ price_reflect_format($product->product_price) }} {{ CURRENCY_ICON }}</span>
   <span class="text1a text2a">{{ trans('label.auction_price') }}: {{ price_reflect_format($winList->bid_price) }} {{ CURRENCY_ICON }}</span>
   <span class="text1a text2a">{{ trans('label.shiping_price') }}:  {{ $product->shipping_price ? price_reflect_format($product->shipping_price).' '.CURRENCY_ICON : '' }}</span>
    <span class="text1a text2a gesamtpreis">{{ trans('label.total_price') }}: {{ price_reflect_format($discountPrice) }} {{ CURRENCY_ICON }}</span>

    @if(in_array($winList->id,$buyWinBid))
     <a class="text1a text5a" data-id="{{ $recallAucId }}" title="{{ trans('message.already_purchased') }}">{{ trans('label.purchased') }}</a> 
    @else
    <a class="text1a text5a buy_now_btn"  data-type="{{ encrypt_decrypt('encrypt', 'win') }}" data-id="{{ $recallAucId }}" data-amount="{{ encrypt_decrypt('encrypt', $discountPrice) }}">{{ trans('label.buy') }}</a> 
    @endif
   </span>
</li>
@endforeach
@else
<span id="no_live">{{ trans('label.no_win_list') }} </span>
@endif
<?php  //echo $winLists->render(); ?>