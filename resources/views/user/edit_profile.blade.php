@extends('layouts.app')
@section('title')
  {{ trans('label.complete_profile') }}
@endsection

@section('content')
@php
  $salutation_title = salutation_title();
  $phone_number_code = phone_number_code();
@endphp
<div class="live-deals live-deals2">
   <!--live-deals start-->  
   <div class="container">
      
      <div class="free-otr">
        @if(session()->has('message.level'))
        <div class="alert alert-{{ session('message.level') }}"> 
          {!! session('message.content') !!}
       </div>
      @endif
         <div class="row">
            <div class="col-md-7">
               <div class="free-bar free-bar2">
                  <h4>{{ trans('label.complete_profile') }} </h4>
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

                       

                        <input type='hidden' name='user_id' value="{{ $user->id ? encrypt_decrypt('encrypt', $user->id) : 0 }}" >
                        <button type="button" class="btn-form btn-form2 mt-3 model_box_save" onClick="javascript:saveform('#profile_form')">{{ trans('label.complete_profile') }}
                        </button> 
                     </div>
                   {!! Form::close() !!}
               </div>
            </div>
            
         </div>
      </div>
   </div>
   <!--live-deals end-->  
</div>

        
  
@endsection

@section('javascript')



<script type="text/javascript">
function saveform(formId){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);

  $.ajax({
    url:"{{ route('user_profile_complete') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('dashboard') }}";   
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

</script>

@endsection