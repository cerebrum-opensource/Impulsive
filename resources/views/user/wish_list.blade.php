@extends('layouts.app')
@section('title')
{{ trans('label.auctions') }}
@endsection
@section('css')
<style>  
   .is_queue{
   background: none;
   }
   .align_right{
   float:right;
   margin: 10px;
   }
</style>
@endsection
@section('content')
<div class="product-otr">
   <div class="live-deals">
      <div class="container">
         <h3 class="sec-heading"><span>{{ trans('label.wish_list') }}</span> </h3>
         <ul class="live-deals-ul list-view-outer">
            @include('user.auctions.overview.wishlist_live_auctions')
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
</div>
@include('news_letter')
@include('support_widget')
<!--  Pass the constant as per page type-->
@include('seo_page',['page_type' =>WISH_LIST])
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
         liveWishListAuctionAjax(auctionsId,user_id,2);
         if($('div.bootbox-alert').is(':visible')){
            clearInterval(timeOut);
         }
         }, 1000);
   });
       
   
       // function to add to recall 
   
   function saveWishList(id,type) {
         var user_id = $("input[name='user_id']").val();
         var userid = $("input[name='user_id']").data('value');
         
   
         if($('#add_to_recall'+id).hasClass('disabled')){
          return;
         }
         $('#add_to_recall'+id).addClass('disabled');
         $.ajax({
             type:"POST",
             url:"{{ route('frontend_add_to_wishlist') }}",
             data:{id: id,user_id:user_id,type:type},
             dataType: "json",
             beforeSend:function(){
   
                 $('#add_to_recall'+id).closest('li').waitMe();
             },
             success:function(data){
                   console.log(data.status)
                   if(data.status == '1'){
                     $('#wishlist_auction'+id).attr('src', '{{ asset("images/watchlist-off.png") }}');
                   }
                   else {
                     $('#wishlist_auction'+id).attr('src', '{{ asset("images/watchlist-on.png") }}');
                   }
   
               //  console.log(data);
             },
             error:function(error){
                // return false; 
                //console.log(error);
             },
             complete:function(data){
               var res = data.responseJSON;
               $('#add_to_recall'+id).closest('li').waitMe('hide');
               if(data.status == 403){
                  $('#recall_auction'+id).addClass('alert-danger').html(res.message).show();
               }
               else if(data.status == 500){
                  alert('not allowed  please refresh the page');
               }
               else {
                 // alert('not allowed');
               }
               $('#add_to_recall'+id).removeClass('disabled');
               fadeOutAlertMessages();
             },
         });
     
   }
   
</script>
@endsection