@if(count($recallList))
@foreach($recallList as $recallAuction)
  @php
    $product = $recallAuction->auction->product;
    if($product->feature_image_1)
      $image = $product->feature_image_1;
    if($product->feature_image_2)
      $image = $product->feature_image_2;
    if($product->feature_image_3)
      $image = $product->feature_image_3;
    if($product->feature_image_4)
      $image = $product->feature_image_4;

    $recallAucId = encrypt_decrypt('encrypt', $recallAuction->id);
  @endphp

<li>
   <span class="img-upper">
      <span class="count-inner1">
      <span class="flower-text">
      <img src="{{ asset('images/flower2.png') }}"><span class="inner-text-flower">{{ $recallAuction->auction->final_countdown }} {{ trans('label.seconds') }}.<span>{{ trans('label.countdown') }}</span></span>
      </span>
      <span class="count-inner count-inner2 {{ $recallAuction->auction->auction_type == 1 ? 'is_queue' : null}}">
      @if($recallAuction->auction->auction_type == 1)
      @else

      <span id="auction_start_date">@if($recallAuction->auction->status == 3) {{ trans('label.expired_on') }}  @else {{ trans('label.start_on') }}  @endif {{$recallAuction->auction->start_date}}</span>
      <span style="display: none" id="planned_count_down{{$recallAucId}}">00:00:00</span>
      @endif
      </span>
      </span>
      @if($recallAuction->auction->auction_type)
      @else
      <script type="text/javascript">
         var countDownDate{{$recallAuction->id}} = new Date("{{$recallAuction->auction->start_date}}").getTime();
         
         var x@php echo $recallAuction->id @endphp = setInterval(function() {
           var now = new Date().getTime();
           var distance = countDownDate{{$recallAuction->id}} - now;
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
           document.getElementById("planned_count_down{{$recallAucId}}").innerHTML = hours + ":"
           + minutes + ":" + seconds;
         
           // If the count down is finished, write some text 
           if (distance < 0) {
             clearInterval(x@php echo $recallAuction->id @endphp);
             document.getElementById("planned_count_down{{$recallAucId}}").innerHTML = "Lived";
             $('#planned_count_down{{$recallAucId}}').closest('li').remove();
           }
         }, 1000);
         
         
      </script>
      @endif
      <span class="img-count">
      <img src="@if($image) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$image }} @else {{ asset('images/Produkt.png') }} @endif"  class="box-img2" alt="{{ $product->product_title }}" title="{{ $product->product_title }}">
      </span>
   </span>
   <span class="text-lower">
   <span class="text1a">{{ $product->product_title }}</span>
   <span class="text1a text2a">{{ trans('label.regular_price') }}: {{ price_reflect_format($product->product_price) }} {{ CURRENCY_ICON }},-</span>
   </span>
</li>
@endforeach
@else
<span id="no_live">{{ trans('label.no_recalled_auctions') }} </span>
@endif
<?php // echo $recallList->render(); ?>