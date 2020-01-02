@extends('layouts.app')
@section('title')
{{ trans('label.bid_account_detail') }}
@endsection
@section('content')
@section('css')
<style>
   .custom-pagination ul.pagination li a{
   color: #fff;
   background-color: #434c5e;
   border: 1px solid #434c5e;
   }
   .custom-pagination ul.pagination{
   margin-bottom: 0px;
   width: unset;
   }
   .custom-pagination ul li{
   padding: 0;
   margin: 0;
   width: unset;
   }
   .custom-pagination .charge-otr{
   background: unset;
   margin: 5px;
   padding: 5px;
   }
</style>
@endsection
<div class="live-deals dien">
   <!--live-deals start-->
   <div class="container">
      <h3 class="sec-heading"><span>{{ trans('label.bid') }}</span> {{ trans('label.detail') }}</h3>
      <ul>
         <li>
            <span class="box1"></span>
            <span class="box2 box2a">{{ trans('label.bid_count') }}</span>
            <span class="box3">
            {{ count($user->userBid) ?  $user->userBid[0]->bid_count : '0' }}
            </span>
         </li>
         <li>
            <span class="box1"></span>
            <span class="box2 box2a">{{ trans('label.win_auction') }}</span>
            <span class="box3">
            {{ $data['winAuctionCount'] }}
            </span>
         </li>
         <li>
            <span class="box1"></span>
            <span class="box2 box2a">{{ trans('label.lose_auction') }} </span>
            <span class="box3">
            {{ $data['lossAuctionCount'] }}
            </span>
         </li>
      </ul>
      <ul>
         <li>
            <span class="box1"></span>
            <span class="box2 box2a">{{ trans('label.total_bid_placed_on_auction') }}</span>
            <span class="box3">
            {{ $data['total_bid_placed'] }}
            </span>
         </li>
         <li>
            <span class="box1"></span>
            <span class="box2 box2a">{{ trans('label.total_bid_agent_count') }} </span>
            <span class="box3">
            {{ $data['bidAgentCount'] }}
            </span>
         </li>
         <li>
            <span class="box1"></span>
            <span class="box2 box2a"> {{ trans('label.total_auction') }}</span>
            <span class="box3">
            {{ $data['winAuctionCount']+$data['lossAuctionCount'] }}
            </span>
         </li>
      </ul>
   </div>
</div>
<!--live-deals end-->
<div class="table-impulsive">
   <div class="container package_table">
      @include('user.user_package_table')
   </div>
</div>
<!--live-deals end-->
<div class="table-impulsive">
   <div class="container">
      <h3 class="sec-heading"><span>{{ trans('label.my')}} </span> {{ trans('label.wins')}}</h3>
      <div class="winis_table">
         @include('user.user_buy_winis_table')
      </div>
   </div>
</div>
<input type='hidden' name='user_id' value="{{ $user->id ? encrypt_decrypt('encrypt', $user->id) : 0 }}" data-value="{{$user->id}}" >
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
<div class="live-deals kommende">
   <!--live-deals kommende start-->
   <div class="container">
      <h3 class="sec-heading sec-heading2"><span>{{ trans('label.coming') }} </span> {{ trans('label.deals') }}</h3>
      <ul class="expired-deals-ul">
         @include('user.auctions.queue_auctions')
      </ul>
   </div>
</div>
<!--live-deals kommende end-->
@endsection
@section('javascript')
@include('layouts.route_name')
<script src="{{ asset('js/auction/auction.js') }}" type="text/javascript"></script>
<script type="text/javascript">
   $('body').on('click', '.package_table .pagination a', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
       $.ajax({
         url:url,
         type:"GET",
         data:{type:'bid'},
         dataType: "json",
         beforeSend:function(){
            $('.package_table').waitMe();
         },
         complete:function(){
            $('.package_table').waitMe('hide');
         },
         success:function(data){
             $(".package_table").html(data.html);
         },
         error:function(data){
             $(".package_table").html(data.html);
         }
      });

   });

   $('body').on('click', '.winis_table .pagination a', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
       $.ajax({
         url:url,
         type:"GET",
         data:{type:'win_list'},
         dataType: "json",
         beforeSend:function(){
            $('.winis_table').waitMe();
         },
         complete:function(){
            $('.winis_table').waitMe('hide');
         },
         success:function(data){
             $(".winis_table").html(data.html);
         },
         error:function(data){
             $(".winis_table").html(data.html);
         }
      });

   });

   $(document).ready(function () {
      var auctionsId = $("input[name='live_auctions[]']")
                 .map(function(){return $(this).val();}).get();
     var user_id = $('input[name="user_id"]').val();
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
