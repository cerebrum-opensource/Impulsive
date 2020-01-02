@extends('layouts.app')

@section('title')
 {{ trans('label.my') }} {{ trans('label.profile') }}
@endsection
 
@section('content')
@php
  $salutation_title = salutation_title();
  $phone_number_code = phone_number_code();
@endphp
<div class="live-deals profile-container">
   <!--live-deals start--> 
   <div class="container">
      <h3 class="sec-heading"><span>{{ trans('label.my') }}</span> {{ trans('label.profile') }}</h3>
      <div class="container">
         <div class="charge-otr">
            @if(session()->has('message.level'))
            <div class="alert alert-{{ session('message.level') }}" style="margin: 15px";> 
               {!! session('message.content') !!}
            </div>
            @endif
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
               <li class="nav-item">
                  <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-expanded="true">{{ trans('label.update_profile') }}</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-expanded="true">{{ trans('label.change_password') }}</a>
               </li>
               <!-- <li class="nav-item">
                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile2" role="tab" aria-controls="pills-profile2" aria-expanded="true">{{ trans('label.delete_account') }}</a>
               </li> -->
            </ul>
            <div class="tab-content" id="pills-tabContent">
               <div class="tab-pane fade show active charge-pills" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                  <div class="profile-second-otr">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="free-bar free-bar2">
                              {!! Form::model($user,['id' => 'profile_form', 'files' => true]) !!}
                              <div class="mt-4">
                                 <div class="form-group">
                                    <label for="title" class="label-text"> {{ trans('label.title') }} *</label>

                                     {!! Form::select('salutation',$salutation_title,null,array('class'=>'form-control form-control2')) !!} 
                                    <span class="error" style="color:red"></span>
                                 </div>
                                 <div class="form-group">
                                    <label for="first_name" class="label-text"> {{ trans('label.first_name') }} * </label>
                                    {!! Form::text('first_name',null,array('maxlength' => 100,'placeholder'=>trans('label.first_name'),'class'=>'form-control form-control2')) !!}
                                    <span class="error" style="color:red"></span>
                                 </div>
                                 <div class="form-group">
                                    <label for="last_name" class="label-text"> {{ trans('label.last_name') }} * </label>
                                    {!! Form::text('last_name',null,array('maxlength' => 100,'placeholder'=>trans('label.last_name'),'class'=>'form-control form-control2')) !!}
                                    <span class="error" style="color:red"></span>
                                 </div>
                                  <div class="form-group">
                                   <label for="postal_code" class="label-text">{{ trans('label.phone_Number') }} *</label>
                                  <div class="row">
                                     <div class="col-md-2"> 
                                        {!! Form::select('phone_code',$phone_number_code,null,array('class'=>'form-control form-control2')) !!}
                                     </div>
                                     <div class="col-md-10">
                                        {!! Form::text('phone',null,['class' => 'set_phone_format form-control form-control2']) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                  </div>
                                </div>
                                 <div class="form-group">
                                    <label for="last_name" class="label-text"> {{ trans('label.email') }} * </label>
                                    {!! Form::text('email',null,array('maxlength' => 100,'placeholder'=>trans('label.email'),'class'=>'form-control form-control2')) !!}
                                    <span class="error" style="color:red"></span>
                                    @if(count($user->primaryEmail))
                                    <span class="badge badge-warning" > {{ trans('message.new_email_verification_pending') }} </span>
                                    @endif
                                 </div>
                                 <div class="form-group">
                                    <label for="dob" class="label-text"> {{ trans('label.dob') }} </label>
                                    {!! Form::text('dob',null,array('placeholder'=>trans('label.dob'),'class'=>'form-control form-control2 datePicker_dob')) !!}
                                    <span class="error" style="color:red"></span>
                                 </div>

                                 <div class="form-group">
                                    <label for="street" class="label-text"> {{ trans('label.street') }} *</label>
                                    {{ Form::text('street', null, array('class' => 'form-control form-control2','placeholder' => trans('label.street'))) }}
                                    <span class="error" style="color:red"></span>
                                 </div>
                                  <div class="form-group">
                                   <label for="street" class="label-text"> {{ trans('label.house_number') }} *</label>
                                   
                                    {{ Form::text('house_number', null, array('class' => 'form-control form-control2','placeholder' => trans('label.house_number'))) }}
                                   <span class="error" style="color:red"></span>
                                  </div>
                                  <div class="form-group">
                                    <label for="postal_code" class="label-text">{{ trans('label.postcode') }} *</label>
                                  {{ Form::text('postal_code', null, array('class' => 'form-control form-control2','placeholder' =>trans('label.postcode'))) }}
                                   <span class="error" style="color:red"></span>
                                  </div>
                                 <div class="form-group">
                                    <label for="city" class="label-text"> {{ trans('label.city') }} *</label>
                                    {{ Form::text('city', null, array('class' => 'form-control form-control2 ','placeholder' =>trans('label.city'))) }}
                                    <span class="error" style="color:red"></span>
                                 </div>
                                 <div class="form-group">
                                    <label for="state" class="label-text"> {{ trans('label.state') }} *</label>
                                    {{ Form::text('state', null, array('class' => 'form-control form-control2','placeholder' =>trans('label.state'))) }}
                                    <span class="error" style="color:red"></span>
                                 </div>
                                 
                                  <div class="form-group">
                                  <label for="postal_code" class="label-text">{{ trans('label.country') }} *</label>
                                  {{ Form::text('country', null, array('class' => 'form-control form-control2','placeholder' =>trans('label.country'))) }}
                                  <span class="error" style="color:red"></span>
                                 </div>

                                 <input type='hidden' name='user_id' value="{{ $user->id ? encrypt_decrypt('encrypt', $user->id) : 0 }}"  data-value="{{Auth::id()}}">
                                 <button type="button" class="btn-form btn-form2 mt-3 model_box_save" onClick="javascript:saveform('#profile_form')">{{ trans('label.update_profile') }}
                                 </button> 
                              </div>
                              {!! Form::close() !!}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                  <div class="profile-second-otr">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="mt-4">
                              <div class="form-group">
                                 <label for="password" class="label-text"> {{ trans('label.new_password') }} *</label>
                                 <input type="password" class="form-control form-control2" placeholder="{{ trans('label.new_password') }}" name="password">
                                 <span class="error" style="color:red"></span>
                              </div>
                              <div class="form-group">
                                 <label for="password_confirmation" class="label-text"> {{ trans('label.confirm_password') }} *</label>
                                 <input type="password" class="form-control form-control2" placeholder="{{ trans('label.confirm_password') }}" name="password_confirmation">
                                 <span class="error" style="color:red"></span>
                              </div>
                              <input type='hidden' name='user_id' value="{{ $user->id ? encrypt_decrypt('encrypt', $user->id) : 0 }}" data-value="{{$user->id}}"  >
                              <button type="button" class="btn-form btn-form2 mt-3 model_box_save" onClick="javascript:changePassword()">{{ trans('label.change_password') }}</button>
                              </button> 
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="pills-profile2" role="tabpanel" aria-labelledby="pills-profile-tab2">
                  <div class="profile-second-otr">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="mt-4">
                              <input type='hidden' name='user_id' value="{{ $user->id ? encrypt_decrypt('encrypt', $user->id) : 0 }}" data-value="{{$user->id}}" >

                              <button type="button" class="btn-form btn-form2 mt-3 model_box_save" disabled="">{{ trans('label.delete_account') }}
                              </button>
                           <!--   <button type="button" class="btn-form btn-form2 mt-3 model_box_save"  onClick="javascript:changeStatus('{{ encrypt_decrypt('encrypt', $user->id) }}') ">{{ trans('label.delete_account') }}
                              </button> -->
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--live-deals end-->  
</div>
<!--live-deals start--> 
<!--
<div class="live-deals kommende">
   <div class="container">
      <h3 class="sec-heading"><span>{{ trans('label.live') }}</span> {{ trans('label.deals') }}</h3>
      <ul class="live-deals-ul">
         @include('user.auctions.latest_live_auctions')
      </ul>
   </div>
</div> 
-->
<!--live-deals end--> 


<div class="live-deals BEENDETE">    <!--live-deals kommende start-->  
   <div class="container">
      <h3 class="sec-heading sec-heading2"><span>{{ trans('label.coming') }} </span> {{ trans('label.deals') }}</h3>
       <ul class="expired-deals-ul">
         @include('user.auctions.queue_auctions')
      </ul>
   </div>
</div> <!--live-deals kommende end-->  
@endsection
@section('javascript')
@include('layouts.route_name')

<script src="{{ asset('js/auction/auction.js') }}" type="text/javascript"></script>
<script type="text/javascript">
   function saveform(formId){
     $('.model_box_save').attr("disabled", "disabled");
     $('span.error').hide().removeClass('active');
     var formData = new FormData($(formId)[0]);
   
     $.ajax({
       url:"{{ route('user_profile_update') }}",
       data:formData,
       processData: false,
       contentType: false,
       dataType: "json",
       success:function(data){
           $('.model_box_save').removeAttr("disabled");
           window.location.href="{{ route('edit_profile') }}";   
       },
       error:function(error){
           console.log(error.status);
           if(error.status == 500){
               alert('please reload');
           }
           else {
   
   
           $('.model_box_save').removeAttr("disabled");
          
           $.each(error.responseJSON.errors,function(key,value){
                $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
                $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
           }); 
           
           jQuery('html, body').animate({
                 scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
           }, 500);  
           }  
       }
     });
   }
   // $('.datePicker_dob').datepicker({
   //      autoclose: true,
   //      dateFormat: 'mm-dd-yy',
   //      maxDate: '-18y',
   //      minDate: '-100y',
   //      changeMonth:true,
   //      changeYear: true
   //    });
   var date = new Date();
   var currentDay = date.getDate();
   $('.datePicker_dob').datepicker({
     autoclose: true,
     dateFormat: 'dd.mm.yy',
     maxDate: '-18y',
     minDate: '-100y',
     changeMonth:true,
     changeYear: true,
     yearRange: "-100:+0",
     onChangeMonthYear: function(year, month, inst) {
            $(this).val(currentDay + "."+month + "." + year);
        } 
   });
   
   function changePassword(){
     $.ajax({
       url:"{{ route('change-password') }}",
       data:{password:$('input[name=password]').val(),password_confirmation:$('input[name=password_confirmation]').val(),'_token':"{{ csrf_token() }}"},
       dataType: "json",
       success:function(data){
         window.location.reload();
       }
     });
   }
   
   
   function changeStatus(id){
        bootbox.confirm({ 
        message: "{{ trans('message.delete_user_confirmation') }} ", 
        callback: function(result){  
          if (result) {
            $.ajax({
                url:"{{ route('delete-account') }}",
                data:{user_id:id},
                success:function(data){
                    window.location.href="{{ route('login') }}";
                }
            });
          }
        }
      })
   }

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