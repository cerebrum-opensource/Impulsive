@extends('layouts.app')
@section('title')
Gutschein
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
<div class="product-otr gutschein-outer-container">
   <div class="live-deals">
      <div class="container">
           @if(session()->has('message.level'))
                        <div class="alert alert-{{ session('message.level') }} alert-dismissible"> 
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {!! session('message.content') !!}
                        </div>
                      @endif

         <h3 class="sec-heading"><span>{{ trans('label.redeem_voucher') }}</span> </h3>
         <ul class="live-deals-ul list-view-outer">
              <form id="redeem_voucher_form">
   
    <input type="hidden" name="user_id" value="{{$user->id}}">
    <input class="form-control form-control2" type="text" name="voucher_code">
    <span class="error" id="redeem_voucher_error" style="color:red"></span>
   @if(session()->has('message.level'))
    <div class="alert alert-{{ session('message.level') }} alert-dismissible"> 
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {!! session('message.content') !!}
    </div>
  @endif
    
    <button type="button" class="btn-form btn-form2" onClick="javascript:saveVoucher('#redeem_voucher_form')">{{trans('label.redeem_voucher')}}</button>
   </form>  
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

@endsection
@section('javascript')
@include('layouts.route_name')
<script src="{{ asset('js/auction/auction.js') }}" type="text/javascript"></script>
<script type="text/javascript">
   @if(old('is_frontend'))
         openLoginNav()
   @endif
   
   $(document).ready(function () {
     
   });
       
   
       // function to add to recall 
   
function saveVoucher(formId){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);
 
  $.ajax({
    url:"{{ route('save_voucher') }}",
    data:formData,
    type:"POST",
    processData: false,
    contentType: false,
    dataType: "json",
    beforeSend: function () {
        $('body').waitMe();
    },
    complete: function () {
        $('body').waitMe('hide');
    },
    success:function(response){
     console.log(response);
     location.reload();
    },
    error: function (error) {
                //console.log(error);
                if (error.responseJSON) {
                    $.each(error.responseJSON.errors, function (key, value) {
                        console.log(value);

                        $('input[name="' + key + '"]').parent().find('span.error').html(value[0]).addClass('active').show();
                        $('select[name="' + key + '"]').parent().find('span.error').html(value[0]).addClass('active').show();
                    });
                    jQuery('html, body').animate({
                        scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
                    }, 500);
                }


            }
  });
}
   
</script>
@endsection