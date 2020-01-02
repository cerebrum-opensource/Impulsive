@extends('layouts.app')

@section('title')
 {{ trans('label.packages') }}
@endsection
@section('content')
<div class="live-deals package-container">
            <!--live-deals start--> 
            <div class="container">
               <h3 class="sec-heading"><span>{{ trans('label.buy') }}</span> {{ trans('label.bid') }}</h3>
               <div class="container">
                  @php 
                    $message = '';
                    if(Session::get('success'))
                      $message = Session::get('success');
                    if(Session::get('error'))
                      $message = Session::get('error');
                  @endphp

                  <div class="charge-otr">
                     <div class="bd-example bd-example-tabs">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                           <li class="nav-item">
                              <a class="nav-link {{ $message !='' ? '':'active' }}" id="selectbid-tab" data-toggle="tab" href="#selectbid" role="tab" aria-controls="pills-home" aria-expanded="true">{{ trans('label.select_bid_package') }} </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" id="choosepayment-tab"  data-toggle="" href="#choosepayment" role="tab" aria-controls="pills-profile" aria-expanded="true">{{ trans('label.choose_payment_method') }} </a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link {{ $message !='' ? 'active':' ' }}"  data-toggle="" id="completepayment-tab" href="#completepayment" role="tab" aria-controls="pills-profile2" aria-expanded="true">{{ trans('label.complete_payment') }} </a>
                           </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            @include('user.choose_package')
                            @include('user.choose_payment_method')
                            @include('user.complete_payment')
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
@endsection
@section('javascript')

<script src="{{ asset('js/ppplus.min.js') }}" type="text/javascript"></script>
<!--<script src="https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js" type="text/javascript"></script> -->
<script type="text/javascript">

  $.ajaxSetup({
        type:"POST",
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        beforeSend:function(){
            $('body').waitMe();
        },
        complete:function(){
            $('body').waitMe('hide');
        },
        error:function(error){

           /* $.each(error.responseJSON.errors,function(key,value){
                $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
                $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
            });

            jQuery('html, body').animate({
                scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
            }, 500);*/

        }
    });

   function savePackage(formId,tab,packageId){


  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);
  formData.append('package_id', packageId)
  $.ajax({
    url:"{{ route('package-add') }}",
    data:formData,
    type:"POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(response){
     
      //console.log(response);
      $('.model_box_save').removeAttr("disabled");
      $('input,textarea,select').removeClass('changed-input');
        $.ajax({  
            url:"{{ route('package-tab') }}",
            type: "GET",
            dataType: "html",
            data: {tab:tab},
            success:function(data){
              if(tab == '2'){
                $('#choosepayment-tab').attr('data-toggle','tab')
                var success = $.parseJSON(data);
                $('#choosepayment').html(success.html.payment_method_tab);
                $(document).find('input[type=hidden][name=plan_id]').val(response.plan_id);
                $(document).find('input[type=hidden][name=transaction_id]').val(response.transaction_id);
               
              }
              $('.nav-pills a.active').parent().next('li').find('a').trigger('click');
              //scroll top
              jQuery('html, body').animate({
                  scrollTop: jQuery('body').offset().top
              }, 500);

           }
        });
      
      
      /*setTimeout(function(){
        $(document).find('input[type=hidden][name=message_type]').val(data.message_type);
      }, 2000);*/
    },
    error:function(error){
        $('.model_box_save').removeAttr("disabled");
       
        $.each(error.responseJSON.errors,function(key,value){
            //console.log(key.indexOf("patient_concern"));
             // console.log(key);
              if(key == 'package_id'){
                  $('#package_error').html(value).addClass('active').show();
              }
              else {
                  $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
                  $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
              }
        }); 
        
         jQuery('html, body').animate({
              scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
          }, 500);   
    }
  });
}

function savePayment(formId,tab){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);
 
  $.ajax({
    url:"{{ route('package-payment') }}",
    data:formData,
    type:"POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(response){
     
      console.log(response);
      $('.model_box_save').removeAttr("disabled");
      $('input,textarea,select').removeClass('changed-input');
      $.ajax({  
            url:"{{ route('package-tab') }}",
            type: "GET",
            dataType: "html",
            data: {tab:tab},
            success:function(data){
              
              if(tab == '3'){
              $('#completepayment-tab').attr('data-toggle','tab')
              var success = $.parseJSON(data);
              $('#completepayment').html(success.html.payment_method_tab);
                
                if(response.type && response.type=='klarna'){
                  $('body').html(response.redirct_url);
                }
                else if(response.type && response.type=='paypal'){
                   // console.log('I am here');

                   /* var ppp = PAYPAL.apps.PPP({
                      "approvalUrl": response.redirct_url,
                      "placeholder": "ppplus",
                      "mode": "live",
                      "country": "DE"
                      });*/
                 //   console.log(ppp);
               
                   window.location.href = response.redirct_url;
                }
                else {
                  if(response.redirct_url){
                   window.location.href = response.redirct_url;
                  }
                }
                
               
              }
            //  $('.nav-pills a.active').parent().next('li').find('a').trigger('click');
              //scroll top
              jQuery('html, body').animate({
                  scrollTop: jQuery('body').offset().top
              }, 500);

           }
        });
      
      
      /*setTimeout(function(){
        $(document).find('input[type=hidden][name=message_type]').val(data.message_type);
      }, 2000);*/
    },
    error:function(error){
        $('.model_box_save').removeAttr("disabled");
       
        $.each(error.responseJSON.errors,function(key,value){
            console.log(key.indexOf("patient_concern"));
              console.log(key);
              if(key == 'package_id'){

                $('#package_error').html(value).addClass('active').show();
              }
              else if(key == 'payment_method'){

                $('#payment_method').html(value).addClass('active').show();
              }
              else {
                $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
               $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
              }
        }); 
        
         jQuery('html, body').animate({
              scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
          }, 500);   
    }
  });
}


</script>
@endsection