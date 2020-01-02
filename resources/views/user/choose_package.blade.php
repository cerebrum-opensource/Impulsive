<div class="tab-pane charge-pills {{ !$message ? 'show active':' ' }} " id="selectbid" role="tabpanel" aria-labelledby="pills-home-tab">
   <ul>
      <span class="error" id="package_error" style="color:red"></span>


      <form action="javascript:;" method="post" id="add_package_form">
      @foreach($packages as $package)
      <li>
         <span class="img-upper"><span class="count-inner1">
         @if($package->type == 'promotional')
        <span class="flower-text flower-text3"><img src="{{ asset('/images/aktion-klee_red.png') }}"></span>
        </span> 
        @endif
            <span class="img-count">
            <img src="@if($package->image) {{ asset('/images/admin/packages/').'/'.$package->id.'/'.$package->image }} @else {{ asset('images/admin/img/notification/4.jpg') }} @endif" class="box-img2" alt="{{$package->name}}" title="{{$package->name}}">

               <!-- <span class="promo_text">{{ $package->type != 'promotional' ? '' : trans('label.promotional_package')  }}</span> -->
              

               @if($package->type == 'promotional')
              <!--  <img src="{{ asset('/images/aktion-klee_red.png') }}"> -->
               
               <p id="demo{{$package->id}}" class="promo_counter"></p>

               <script type="text/javascript">
                  var countDownDate{{$package->id}} = new Date("{{$package->end_date}}").getTime();
                  var t = countDownDate{{$package->id}};
                  var t1 = '{{$package->end_date}}';

                  var x = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countDownDate{{$package->id}} - now;
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    document.getElementById("demo{{$package->id}}").innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";

                    // If the count down is finished, write some text 
                    if (distance < 0) {
                      clearInterval(x);
                      document.getElementById("demo{{$package->id}}").innerHTML = "EXPIRED";
                      $('#demo{{$package->id}}').closest('li').find('.buy_now_btn').remove();
                    }
                  }, 1000);


               </script>
               @endif

            </span>
         </span>
         <span class="text-lower">
           @if($package->type == 'promotional' && $package->bonus)
            <span class="text1a">{{ trans('label.bonus_bid') }} {{ $package->bonus }}</span>
           @endif
         <span class="text1a">{{ trans('label.bids') }} {{ $package->bid+$package->bonus }}</span>
         <span class="text1a">{{ price_reflect_format($package->price) }} {{ CURRENCY_ICON }}</span>
         <input class="radio radio_button" type="radio" name="package_id" id="radio_1" value="{{ $package->id }}" style="display: none">
         <a class="text1a text5a text6a buy_now_btn" style="cursor: pointer;"onClick="javascript:savePackage('#add_package_form','2','{{ $package->id }}')">{{ trans('label.buy') }}</a>
         </span>
      </li>
      @endforeach
      <input type="hidden" name="step_number" value="1">
      <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
      </form>
   </ul>
</div>