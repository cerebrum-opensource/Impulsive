@extends('layouts.app')
@section('title')
 {{ trans('label.auctions') }}
@endsection
@section('content')

 <!--slider view-->  
@include('slider')

 <!--slider bottom view-->  
@include('slider_bottom')

<div class="live-deals">
   <!--live-deals start-->  
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

@include('news_letter')
@include('support_widget')


<!--  Pass the constant as per page type-->
@include('seo_page',['page_type' =>RUNNING_AUCTION])

@endsection


@section('javascript')

@include('layouts.route_name')

<script src="{{ asset('js/auction/auction.js') }}" type="text/javascript"></script>
<script type="text/javascript">
@if(old('is_frontend'))
      openLoginNav()
@endif

$(document).ready(function () {
   var auctionsId = $("input[name='live_auctions[]']")
              .map(function(){return $(this).val();}).get();
  var user_id = $('input[name="user_id"]').val();
 
   // run the ajax every 10 seconds for new live auctions and stop if any error 
   var timeOut = setInterval(function()
      { 
      var auctionsId = $("input[name='live_auctions[]']")
              .map(function(){return $(this).val();}).get();
      liveAuctionAjax(auctionsId,user_id,0,0);
      if($('div.bootbox-alert').is(':visible')){
         clearInterval(timeOut);
      }
      }, 1000);
});
    

</script>

@endsection