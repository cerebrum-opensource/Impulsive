@extends('layouts.app')
@section('title')
 {{ trans('label.auctions') }}
@endsection
@section('content')

<div class="live-deals">
   <!--live-deals start-->  
   <div class="container">
      <h3 class="sec-heading"><span>{{ trans('label.live') }}</span> {{ trans('label.deals') }}</h3>
      <ul class="live-deals-ul list-view-outer">
         @include('user.auctions.overview.live_auctions')
      </ul>
   </div>
</div>

<div class="live-deals BEENDETE">    <!--live-deals BEENDETE start-->  
   <div class="container">
      <h3 class="sec-heading sec-heading2"><span>{{ trans('label.coming') }} </span> {{ trans('label.deals') }}</h3>
       <ul class="expired-deals-ul">
         @include('user.auctions.queue_auctions')
      </ul>
   </div>
</div> <!--live-deals BEENDETE end-->  
<!--live-deals end-->   

<!--  Pass the constant as per page type-->
@include('seo_page',['page_type' =>AUCTION_OVERVIEW])



@if (!Auth::guest())
   <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::id()) }}" data-value="{{Auth::id()}}"> 
@else
   <input type="hidden" name="user_id" value="0">
@endif

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
      liveAuctionAjax(auctionsId,user_id,1,0);
      if($('div.bootbox-alert').is(':visible')){
         clearInterval(timeOut);
      }
      }, 1000);
});
    
  
function addToWishlist(id,type,auctionId){

    var user_id = $("input[name='user_id']").val();
   $('.messageTip').hide();
      $.ajax({
            type:"GET",
            url:FRONTEND_LOGIN_CHECK_OVERVIEW,
            data:{id: id },
            dataType: "json",
            beforeSend:function(){
              $('#add_to_recall'+id).closest('div').waitMe();
            },
            success:function(data){
                  if(data.isLoggedIn){
                     $('input[type=hidden][name=user_id]').val(data.user_id);
                     $('input[type=hidden][name=user_id]').attr('data-value',data.userid);
                  }
                  else {
                      var ids = '#bid_user_login'+id;
                      //$(ids).html(data.message);
                      console.log(ids);
                      $('#bid_user_login'+auctionId).addClass('alert-warning').html(data.message).show();
                    //  $(ids).show();
                    fadeOutAlertMessages();
                  }
            },
             error:function(error){
                 return true; 
            },
            complete:function(data){
               $('#add_to_recall'+id).closest('div').waitMe('hide');
               if(data.responseJSON.isLoggedIn){
                  saveWishList(data.responseJSON.id,type,auctionId);
               }

            }         
      });
}

    // function to add to recall 

function saveWishList(id,type,auctionId) {
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

              $('#add_to_recall'+id).closest('div').waitMe();
          },
          success:function(data){
              //  console.log(data.status)
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
            $('#add_to_recall'+id).closest('div').waitMe('hide');
            if(data.status == 403){
               $('#bid_user_login'+auctionId).addClass('alert-danger').html(res.message).show();
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