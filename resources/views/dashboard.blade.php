@extends('layouts.app')
@section('title')
 {{ trans('label.dashboard') }}
@endsection
@section('content')


<div class="live-deals"><!--live-deals start-->	
    @if(session()->has('message.level'))
                <div class="alert alert-{{ session('message.level') }} alert-dismissible"> 
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! session('message.content') !!}
                </div>
            @endif
   <div class="container">
      <h3 class="sec-heading"><span>{{ trans('label.live') }}</span> {{ trans('label.deals') }}</h3>
      <ul class="live-deals-ul">
         @include('user.auctions.latest_live_auctions')
      </ul>
   </div>
</div><!--live-deals end-->	
@if (!Auth::guest())
   <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::id()) }}" data-value="{{Auth::id()}}"> 
@else
   <input type="hidden" name="user_id" value="0">
@endif
 <div class="live-deals kommende"> 
   <div class="container">
      <h3 class="sec-heading sec-heading2"><span>{{ trans('label.planned') }}</span> {{ trans('label.deals') }}</h3>
      <ul class="planned-deals-ul">
         @include('user.auctions.planned_auctions')
      </ul>
   </div>
</div> <!---live-deals kommende end-->  
<div class="live-deals ">    <!--live-deals BEENDETE start-->  
   <div class="container">
      <h3 class="sec-heading sec-heading2"><span>{{ trans('label.coming') }} </span> {{ trans('label.deals') }}</h3>
       <ul class="expired-deals-ul">
         @include('user.auctions.queue_auctions')
      </ul>
   </div>
</div> <!--live-deals BEENDETE end-->  
<!--
@include('seo_page',['page_type' =>DASH_BOARD])
	--> 		
	
@endsection


@section('javascript')

@include('layouts.route_name')

 <script src="{{ asset('js/auction/auction.js') }}" type="text/javascript"></script>
<script type="text/javascript">


$(document).ready(function () {
   var auctionsId = $("input[name='live_auctions[]']")
              .map(function(){return $(this).val();}).get();
	var user_id = $('input[name="user_id"]').val();
 // console.log(auctionsId);

  /* if(!$('div.bootbox-alert').is(':visible')){
   setInterval(function()
		{ 
      var auctionsId = $("input[name='live_auctions[]']")
              .map(function(){return $(this).val();}).get();
      liveAuctionAjax(auctionsId,user_id);
		}, 1000);
   } 
*/
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