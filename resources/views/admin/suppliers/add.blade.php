@extends('layouts.adminapp')
@section('css')
 <style>    
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: #959494;
    opacity: 1;
    }
    .form_bal_textfield{
        height: 100% !important;
    }
 </style> 
  <link rel="stylesheet" href="{{ asset('css/admin/form_builder.css') }}">
@endsection
@section('content')

@php
  $name = trans('label.add_new_seller');
  if($user->id)
  $name = trans('label.update_seller');
@endphp

@include('admin.breadcome', ['name' => $name])


 <div class="single-product-tab-area mg-b-30">
            <!-- Single pro tab review Start-->
            <div class="single-pro-review-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="review-tab-pro-inner">
                                <ul id="myTab3" class="tab-review-design">
                                    <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> Seller </a></li>
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>
                                
                        {!! Form::model($user, ['class'=>'form-horizontal','id'=>'user_form']) !!}
                        <div class="row">
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <div class="review-content-section">
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('title', trans('label.first_name')) }}*
                                        {{ Form::text('first_name', null, array('class' => 'form-control','placeholder' =>trans('label.first_name'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('last_name', trans('label.last_name')) }}*
                                        {{ Form::text('last_name', null, array('class' => 'form-control','placeholder' =>trans('label.first_name'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('email', trans('label.email')) }}*
                                        @if($user->id)
                                        {{ Form::text('email', null, array('class' => 'form-control','placeholder' =>trans('label.email'),"disabled"=>true)) }}
                                        @else
                                        {{ Form::text('email', null, array('class' => 'form-control','placeholder' =>trans('label.email'))) }}
                                        @endif
                                        
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="mg-b-pro-edt">
                                        {{ Form::label('phone', trans('label.phone_Number')) }}*
                                        <div class="row">
                                           <div class="col-md-2">
                                              {!! Form::text('phone_code','+49',['class' => 'phone_number_class text-center form-control','disabled'=>true]) !!} 
                                           </div>
                                           <div class="col-md-10">
                                              {!! Form::text('phone',null,['class' => 'set_phone_format form-control']) !!}
                                              <span class="error" style="color:red"></span>
                                           </div>
                                        </div>
                                     </div>
                                      <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('company', trans('label.company')) }}*
                                        {{ Form::text('company', null, array('class' => 'form-control','placeholder' =>trans('label.company'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                  </div>
                               </div>
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <div class="review-content-section">
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('street', trans('label.street')) }}*
                                        {{ Form::text('street', null, array('class' => 'form-control','placeholder' =>trans('label.street'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('city', trans('label.city')) }}*
                                        {{ Form::text('city', null, array('class' => 'form-control','placeholder' =>trans('label.city'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('state', trans('label.state')) }}*
                                        {{ Form::text('state', null, array('class' => 'form-control','placeholder' =>trans('label.state'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('additional_address', trans('label.address')) }}*
                                        {{ Form::text('additional_address', null, array('class' => 'form-control','placeholder' =>trans('label.address'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('comment', trans('label.comment')) }}
                                           {!! Form::textarea('comment',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.comment')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                  </div>
                               </div>
                             </div>

                               <div class="row">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <div class="text-center custom-pro-edt-ds">
                                        <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#user_form')">{{ $user->id ? trans('label.update') : trans('label.save') }}
                                        </button>
                                        <input type='hidden' name='user_id' value='{{ $user->id ? $user->id : 0 }}' >
                                       
                                         <button type="button" onClick="javascript:disCardForm('{{ route('suppliers') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">{{ trans('label.discard') }}
                                                        </button>
                                     </div>
                                  </div>
                               </div>
                               {!! Form::close() !!}
                            

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('javascript')

<script type="text/javascript">
function saveform(formId){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);

  $.ajax({
    url:"{{ route('user-add') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('suppliers') }}";   
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
             console.log($('select[name="'+key+'"]').parent());
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