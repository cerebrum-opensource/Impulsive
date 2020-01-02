
@extends('layouts.app')

@section('title')
FAIRPLAY-AUKTIONEN
@endsection
@section('content')

 <!--slider view-->  
@include('slider')

 <!--slider bottom view-->  
@include('slider_bottom')
<div class="pop-container"><div class="close-pop">✖</div><iframe class="uvembed78132 pop-style" frameborder="0" src="https://static.upviral.com/loader.html" ></iframe></div>

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
<div class="live-deals kommende"> <!--live-deals kommende start--> 
   <div class="container">
      <h3 class="sec-heading sec-heading2"><span>{{ trans('label.planned') }}</span> {{ trans('label.deals') }}</h3>
      <ul class="planned-deals-ul">
         @include('user.auctions.planned_auctions')
      </ul>
   </div>
</div> 


<div class="live-deals kommende-deals-section">    <!--live-deals BEENDETE start-->  
   <div class="container">
      <h3 class="sec-heading sec-heading2"><span>{{ trans('label.coming') }} </span> {{ trans('label.deals') }}</h3>
       <ul class="expired-deals-ul">
         @include('user.auctions.queue_auctions')
      </ul>
   </div>
</div> <!--live-deals BEENDETE end-->  

<div class="live-deals BEENDETE beendete-deals">    <!--live-deals BEENDETE start-->  
   <div class="container">
      <h3 class="sec-heading sec-heading2"><span>{{ trans('label.expired') }} </span> {{ trans('label.deals') }}</h3>
       <ul class="expired-deals-ul">
         @include('user.auctions.expired_auctions')
      </ul>
   </div>
</div> <!--live-deals BEENDETE end-->  

@include('news_letter')
@include('support_widget')

<!--  Pass the constant as per page type-->
@include('seo_page',['page_type' =>HOMEPAGE])

@endsection
<!--  End content section -->
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
   //} 


});
  	

</script>

@endsection