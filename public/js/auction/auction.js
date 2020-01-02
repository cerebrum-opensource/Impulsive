 $.ajaxSetup({
        type:"POST",
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
        beforeSend:function(){
        },
        complete:function(){
          fadeOutAlertMessages();
        },
        error:function(error){
          fadeOutAlertMessages();
        }
});

var IDX_AUCTION_ID = 0;
var IDX_STATUS = 1;
var IDX_COUNTDOWN_FORMATTED = 2;
var IDX_CURRENT_BID_COUNT = 3;
var IDX_CURRENT_BID_PRICE = 4;
var IDX_COUNTDOWN_ENDTIME_FORMATTED = 5;
var IDX_USERNAME = 6;
var IDX_USERID = 7;
var COUNTDOWN_START = 8;
var AUTO_BID = 9;
var BREAK_TIME_START = 11;
var BREAK_TIME_END = 12;
var BREAK_TIME = 13;
var currentAuctionData = new Object();




// update the current javascript object by auction id

function updateCurrentAuctionData(data) {
	currentAuctionData['aid'+data[IDX_AUCTION_ID]] = data;
}


// function to save the auction object and render the html


var updateAuction = function(ai) {
   var aid = ai[IDX_AUCTION_ID];
   var status = ai[IDX_STATUS];
   var countdownFormatted = ai[IDX_COUNTDOWN_FORMATTED];
   var nBids = ai[IDX_CURRENT_BID_COUNT];
   var price = ai[IDX_CURRENT_BID_PRICE];
   var username = ai[IDX_USERNAME];
   var userId = ai[IDX_USERID];
   var countdownStart = ai[COUNTDOWN_START];

 //  var formatPrice =  price + CURRENCY_SYSMBOL_JS;
   var formatPrice =  price ;
   $('#bid_price'+aid).html(formatPrice);
   $('#bid_latest_user'+aid).html(username);
   //console.log(status);
    console.log(countdownFormatted);
   $('#count_down'+aid).html(countdownFormatted);
   $('#countdown_start'+aid).val(countdownStart);

   // console.log(ai[BREAK_TIME_START]);

   //if(ai[BREAK_TIME_START] <= 0 && ai[BREAK_TIME_END] > 0){
   if(ai[BREAK_TIME_START] == 1 ){
      $('#break_time'+aid).removeClass('hide_break_time');
      $('#break_time_h4'+aid).html(ai[BREAK_TIME]);
   }
   else {
      $('#break_time'+aid).addClass('hide_break_time');
      $('#break_time_h4'+aid).html('');


   }
 //  console.log(ai[COUNTDOWN_START]);
  // console.log(ai[BREAK_TIME_END]);
 //  console.log(ai[BREAK_TIME]);

   //if(status == '0'){

      if($('#count_down'+aid).text() == '00:00:00' || $('#count_down'+aid).text() == '00:00:00'){
          $('#auction'+aid).find('.buy_now_btn').remove();
          $('#auction'+aid).remove();
      }

 //// }
   if (nBids != '0') {
      updateCurrentAuctionData(ai);
   }
   //console.log(ai);
};


/*
// function to render the live listing every second
var updateBidView = function(r) {

    if(r.status == 200){
      var response = r.responseText;
      var auctionResponses = response.split(';');
     // console.log(auctionResponses);

      for(var j = 0; j < auctionResponses.length; ++j) {
         var ai = auctionResponses[j].split('|');
        // console.log(ai);
         updateAuction(ai);
      }
   }
};
*/

// function to render the live listing every second
var updateBidView = function(r) {
    if(r.status == 200){
      var response = r.responseText;
      var success = $.parseJSON(response);
      var auctionResponses = success.html.data.split(';');
     // console.log(success.html.data);
     // check if any new or latest live than append it in the last
     if(success.html.latest_live_auctions){
         $('#no_live').remove();
         $('.live-deals-ul').append(success.html.latest_live_auctions);
     }

     if(success.html.data=='' && !success.html.latest_live_auctions){
      //  console.log('1');
        $('.live-deals-ul').html('<span id="no_live">'+MESSAGE_NO_LISTING_FOUND+'</span>');
     }

      for(var j = 0; j < auctionResponses.length; ++j) {
         var ai = auctionResponses[j].split('|');
         updateAuction(ai);
      }
   }
};


var updateAuctionWishList = function(ai) {
   var aid = ai[IDX_AUCTION_ID];
   var status = ai[IDX_STATUS];
   var countdownFormatted = ai[IDX_COUNTDOWN_FORMATTED];
   var nBids = ai[IDX_CURRENT_BID_COUNT];
   var price = ai[IDX_CURRENT_BID_PRICE];
   var username = ai[IDX_USERNAME];
   var userId = ai[IDX_USERID];
   var countdownStart = ai[COUNTDOWN_START];
   var formatPrice = CURRENCY_SYSMBOL_JS+price;
   $('#bid_price'+aid).html(formatPrice);
   $('#bid_latest_user'+aid).html(username);
   //console.log(status);
   $('#count_down'+aid).html(countdownFormatted);
   $('#countdown_start'+aid).val(countdownStart);
   if(status == '0'){

    $('#auction'+aid).find('.buy_now_btn').remove();

  }
   if (nBids != '0') {
      updateCurrentAuctionData(ai);
   }

};

// function to render the live listing every second for wishlist
var updateBidViewWishList = function(r) {
    if(r.status == 200){
      var response = r.responseText;
      var success = $.parseJSON(response);
      var auctionResponses = success.html.data.split(';');
     // console.log(success.html.data);
     // check if any new or latest live than append it in the last
     if(success.html.latest_live_auctions){
         $('#no_live').remove();
         $('.live-deals-ul').append(success.html.latest_live_auctions);
     }

     if(success.html.data=='' && !success.html.latest_live_auctions){
      //  console.log('1');
     //   $('.live-deals-ul').html('<span id="no_live">'+MESSAGE_NO_LISTING_FOUND+'</span>');
     }

      for(var j = 0; j < auctionResponses.length; ++j) {
         var ai = auctionResponses[j].split('|');
         updateAuctionWishList(ai);
      }
   }
};

// function to Place the bid and while user is logged in

function placeBid(id) {

      var user_id = $("input[name='user_id']").val();
      var userid = $("input[name='user_id']").data('value');
      var countdown = $('#countdown_start'+id).val();

      if(currentAuctionData['aid'+id]){
         data = currentAuctionData['aid'+id];
         //console.log(data[AUTO_BID])
         if(data[AUTO_BID] == 1){
             var ids = '#bid_user_login'+id;
             $(ids).html(MESSAGE_ALREADY_AUTO_BIDDER);
             $(ids).show();
            return;
         }
      }

      if(currentAuctionData['aid'+id]){
         data = currentAuctionData['aid'+id];
         if(data[IDX_USERID] == userid){
             var ids = '#bid_user_login'+id;
             $(ids).html(MESSAGE_ALREADY_HIGHER_BIDDER);
             $(ids).show();
            return;
         }
      }


      if($('#buy_now'+id).hasClass('disabled')){
       return;
      }
      $('#buy_now'+id).addClass('disabled');
      $.ajax({
          type:"POST",
          url:FRONTEND_BID_ON_AUCTION,
          data:{id: id,user_id:user_id,countdown:countdown},
          dataType: "json",
          beforeSend:function(){
              $('#buy_now'+id).closest('li').waitMe();
          },
          success:function(data){
              // console.log(data.message);
              if(data.message == 3){
                 var ids = '#bid_user_login'+id;
                 $(ids).html(MESSAGE_ALREADY_AUTO_BIDDER);
                 $(ids).show();
               }
               if(data.message == 4){
                 var ids = '#bid_user_login'+id;
                 $(ids).html(MESSAGE_ALREADY_HIGHER_BIDDER);
                 $(ids).show();
               }
               if(data.message == 5){
                 var ids = '#bid_user_login'+id;
                 $(ids).html(MESSAGE_EXPIRED_AUCTION);
                 $(ids).show();
               }
              if(data.message == 6){
                  var ids = '#bid_user_login'+id;
                  $(ids).html(MESSAGE_BIDLOCK);
                  $(ids).show();
              }
          },

          error:function(error){
            $('#buy_now'+id).closest('li').waitMe('hide');
             // return false;
             //console.log(error);
          },
          complete:function(data){

            var bid_count = document.getElementById("header_bid_count").innerHTML;
            var topbar_free_bid_count = document.getElementById("header_free_bid_count").innerHTML;
            var topbar_purchased_bid_count = document.getElementById("header_purchased_bid_count").innerHTML;
            var sidenav_bid_count = document.getElementById("sidenav_bid_count").innerHTML;
            var sidenav_free_bid_count = document.getElementById("sidenav_free_bid_count").innerHTML;
            var sidenav_purchased_bid_count = document.getElementById("sidenav_purchased_bid_count").innerHTML;
            // alert(bid_count);

            if(bid_count > 0 && data.responseJSON.data) {
              //$('#header_bid_count').html(bid_count - 1);
              $('#header_bid_count').html(data.responseJSON.data['bid_count']);
              $('#sidenav_bid_count').html(data.responseJSON.data['bid_count']);
              //$('#sidenav_bid_count').html(sidenav_bid_count - 1);
              if(topbar_free_bid_count > 0)
              {
                //$('#header_free_bid_count').html(topbar_free_bid_count - 1);
                $('#header_free_bid_count').html(data.responseJSON.data['free_bid']);
                $('#sidenav_free_bid_count').html(data.responseJSON.data['free_bid']);
                //$('#sidenav_free_bid_count').html(sidenav_free_bid_count - 1);
              }else{
                // $('#header_purchased_bid_count').html(topbar_purchased_bid_count - 1);
                // $('#sidenav_purchased_bid_count').html(sidenav_purchased_bid_count - 1);
                $('#header_purchased_bid_count').html(data.responseJSON.data['bid_count'] - data.responseJSON.data['free_bid']);
                $('#sidenav_purchased_bid_count').html(data.responseJSON.data['bid_count'] - data.responseJSON.data['free_bid']);
              }
            }
            $('#buy_now'+id).closest('li').waitMe('hide');
            if(data.status == 403){
               alert('not allowed');
            }
            else if(data.status == 500){
               alert('not allowed1');
            }
            else {
              // alert('not allowed');
            }
            $('#buy_now'+id).removeClass('disabled');
          },
      });

}


function requestRefused(id) {
   var user_id = $("input[name='user_id']").val();
   $('.messageTip').hide();
      $.ajax({
            type:"GET",
            url:FRONTEND_LOGIN_CHECK,
            data:{id: id },
            dataType: "json",
            beforeSend:function(){
              $('#buy_now'+id).closest('li').waitMe();
            },
            success:function(data){
                  if(data.isLoggedIn){
                     $('input[type=hidden][name=user_id]').val(data.user_id);
                     $('input[type=hidden][name=user_id]').attr('data-value',data.userid);
                  }
                  else {
                      var ids = '#bid_user_login'+id;
                      $(ids).addClass('alert-warning').html(data.message).show();
                     // $(ids).show();
                  }
            },
             error:function(error){
                $('#buy_now'+id).closest('li').waitMe('hide');
                 return true;
            },
            complete:function(data){
               $('#buy_now'+id).closest('li').waitMe('hide');
               if(data.responseJSON.isLoggedIn){
                     if(data.responseJSON.count){
                        placeBid(data.responseJSON.id);
                     }
                     else {
                        var ids = '#bid_user_login'+id;
                        $(ids).html(data.responseJSON.message);
                        $(ids).show();
                     }
               }
               fadeOutAlertMessages();
            }
      });
   }


//place a bid
function setOneBid(id) {
   requestRefused(id)
}

function liveAuctionAjax(auctionsId,userid,is_watchlist,auctionDetailId){

	$.ajax({
  		type:"POST",
	    url:FRONTEND_LIVE_AUCTIONS,
	    data:{auctionsId: auctionsId,user_id:userid,is_watchlist:is_watchlist,detail_id:auctionDetailId},
	    dataType: "json",
	    success:function(data){

	    },
	    error:function(error){
          if(error.status !=200){
                if(!$('div.bootbox-alert').is(':visible')){
                   bootbox.alert(MESSAGE_TOKEN_EXPIRE, function(){
                      location.reload();
                   });

                }
          }

	    },
     	complete: updateBidView,
	});
}


// function to Place the bid and while user is logged in

function setupBidAgent(id) {
      var user_id = $("input[name='user_id']").val();
      var userid = $("input[name='user_id']").data('value');
      var bidCount = $("input[name='number_of_bid']").val();
      var start_type = $("#select_agent_time option:selected" ).val();

      if($('#setup_bid_agent').hasClass('disabled')){
       return;
      }
      $('#setup_bid_agent').addClass('disabled');

      $("input[name='number_of_bid']").val(1);

      $.ajax({
          type:"POST",
          url:FRONTEND_SETUP_BID_AGENT,
          data:{id: id,user_id:user_id,bidCount:bidCount,start_type:start_type},
          dataType: "json",
          beforeSend:function(){
            $('#setup_bid').waitMe();
          },
          success:function(data){
            $('#bid_number').addClass('alert-success');
            $('#bid_number').html(data.message).show();
            // $('#pending_bid_count_message').html(MESSAGE_BID_AGENT_SETUP);
            //$('#buy_now'+id).closest('li').remove();
           // fadeOutAlertMessages();
          },
          error:function(error){
            $('#setup_bid').waitMe('hide');
            $.each(error.responseJSON.errors,function(key,value){
                  $('#bid_number').addClass('alert-error');
                  $('#bid_number').html(value).show();
            });
          },
          complete:function(data){

            var response_data = data.responseText;
            var bid_count = document.getElementById("header_bid_count").innerHTML;
            var topbar_free_bid_count = document.getElementById("header_free_bid_count").innerHTML;
            var topbar_purchased_bid_count = document.getElementById("header_purchased_bid_count").innerHTML;
            var sidenav_free_bid_count = document.getElementById("sidenav_free_bid_count").innerHTML;
            var sidenav_purchased_bid_count = document.getElementById("sidenav_purchased_bid_count").innerHTML;
            if(bid_count > 0){
              $('#header_bid_count').html(data.responseJSON.data['bid_count']);
              $('#sidenav_bid_count').html(data.responseJSON.data['bid_count']);
              if(topbar_free_bid_count > 0)
              {
                $('#header_free_bid_count').html(data.responseJSON.data['free_bid']);
                $('#sidenav_free_bid_count').html(data.responseJSON.data['free_bid']);
              }else{
                $('#header_purchased_bid_count').html(data.responseJSON.data['bid_count'] - data.responseJSON.data['free_bid']);
                $('#sidenav_purchased_bid_count').html(data.responseJSON.data['bid_count'] - data.responseJSON.data['free_bid']);
              }
            }
            //console.log(bid_count);
            //console.log(MESSAGE_BID_AGENT_SETUP);
            $('#pending_bid_count_message').html(MESSAGE_BID_AGENT_SETUP);
            $('#setup_bid').waitMe('hide');
            $('#setup_bid_agent').removeClass('disabled');
          },
      });

}

function addTorecall(id,type){

    var user_id = $("input[name='user_id']").val();
   $('.messageTip').hide();
      $.ajax({
            type:"GET",
            url:FRONTEND_LOGIN_CHECK,
            data:{id: id },
            dataType: "json",
            beforeSend:function(){
              $('#add_to_recall'+id).closest('li').waitMe();
            },
            success:function(data){
                  if(data.isLoggedIn){
                     $('input[type=hidden][name=user_id]').val(data.user_id);
                     $('input[type=hidden][name=user_id]').attr('data-value',data.userid);
                  }
                  else {
                      var ids = '#bid_user_login'+id;
                      //$(ids).html(data.message);
                      $('#recall_auction'+id).addClass('alert-warning').html(data.message).show();
                    //  $(ids).show();
                    fadeOutAlertMessages();
                  }
            },
             error:function(error){
                 return true;
            },
            complete:function(data){
               $('#add_to_recall'+id).closest('li').waitMe('hide');
               if(data.responseJSON.isLoggedIn){

                  saveRecall(data.responseJSON.id,type);

               }

            }
      });
}


// function to add to recall

function saveRecall(id,type) {
      var user_id = $("input[name='user_id']").val();
      var userid = $("input[name='user_id']").data('value');


      if($('#add_to_recall'+id).hasClass('disabled')){
       return;
      }
      $('#add_to_recall'+id).addClass('disabled');
      $.ajax({
          type:"POST",
          url:FRONTEND_ADD_TO_RECALL,
          data:{id: id,user_id:user_id,type:type},
          dataType: "json",
          beforeSend:function(){

              $('#add_to_recall'+id).closest('li').waitMe();
          },
          success:function(data){
                //console.log(data.status)
                if(data.status == '1'){
                  $('#recall_auction'+id).addClass('alert-success').html(data.message).show();
                }
                else {
                  $('#recall_auction'+id).addClass('alert-warning').html(data.message).show();
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



function liveWishListAuctionAjax(auctionsId,userid,is_watchlist){
  $.ajax({
      type:"POST",
      url:FRONTEND_LIVE_AUCTIONS,
      data:{auctionsId: auctionsId,user_id:userid,is_watchlist:is_watchlist },
      dataType: "json",
      success:function(data){
      },
      error:function(error){
          if(error.status !=200){
                if(!$('div.bootbox-alert').is(':visible')){
                   bootbox.alert(MESSAGE_TOKEN_EXPIRE, function(){
                      location.reload();
                   });

                }
          }

      },
      complete: updateBidViewWishList,
  });
}
