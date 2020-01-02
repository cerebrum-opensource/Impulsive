@extends('layouts.app')
@section('content')



<div class="live-deals live-deals2">
   <!--live-deals start--> 
   <div class="container">
      <div class="free-otr">
          @php 
            $message = '';
          if(Session::get('success'))
            $message = Session::get('success');
           if(Session::get('error'))
            $message = Session::get('error');
          //$messageError = Session::get('error');
          @endphp
         <br>
          <div class="tabsmain">
    <div class="row">
      
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item"> <a class="nav-link {{ $message !='' ? 'active':' ' }} " id="selectbid-tab" data-toggle="tab" href="#selectbid" role="tab" aria-controls="home" aria-selected="true">{{ trans('label.select_bid_package') }} </a> </li>
          <li class="nav-item"> <a class="nav-link" data-toggle="" id="choosepayment-tab" href="#choosepayment" role="tab" aria-controls="home" aria-selected="false"> {{ trans('label.choose_payment_method') }} </a> </li>
          <li class="nav-item"> <a class="nav-link {{ $message ? 'active':'' }}" data-toggle="" id="completepayment-tab" href="#completepayment" role="tab" aria-controls="contact" aria-selected="false"> {{ trans('label.complete_payment') }} </a> </li>
        </ul>
      
    </div>


    <div class="tab-content" id="myTabContent">
        @include('user.choose_package')    
        <div class="tab-pane fade" id="choosepayment" role="tabpanel" aria-labelledby="home-tab">
          @include('user.choose_payment_method')
        </div>
        @include('user.complete_payment') 
    </div>
  </div>
      </div>
   </div>
   <!--live-deals end-->  
</div>
@endsection
@section('javascript')
<script type="text/javascript">
   function savePackage(formId,tab){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);
 
  $.ajax({
    url:"{{ route('package-add') }}",
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
              if(tab == '2'){
                $('#choosepayment-tab').attr('data-toggle','tab')
                var success = $.parseJSON(data);
                $('#choosepayment').html(success.html.payment_method_tab);
                $(document).find('input[type=hidden][name=plan_id]').val(response.plan_id);
                $(document).find('input[type=hidden][name=transaction_id]').val(response.transaction_id);
               
              }
              $('.nav-tabs a.active').parent().next('li').find('a').trigger('click');
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
                
                if(response.redirct_url){
                   window.location.href = response.redirct_url;
                }
               
              }
              $('.nav-tabs a.active').parent().next('li').find('a').trigger('click');
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