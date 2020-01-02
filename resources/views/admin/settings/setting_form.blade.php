@extends('layouts.adminapp')
@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/jquery.datetimepicker.css') }}">
@endsection
@section('content')
@php
  $name = trans('label.settings');
@endphp

@include('admin.breadcome', ['name' => $name])


 <div class="single-product-tab-area mg-b-30">
            <!-- Single pro tab review Start-->
            <div class="single-pro-review-area">
                <div class="container-fluid">
                    <div class="row">

                      @if(session()->has('message.level'))
                        <div class="alert alert-{{ session('message.level') }} alert-dismissible"> 
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {!! session('message.content') !!}
                        </div>
                      @endif
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="review-tab-pro-inner">
                                <ul id="myTab3" class="tab-review-design">
                                    <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.settings') }} </a></li>
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>
                                
                        {!! Form::model($setting, ['class'=>'form-horizontal','id'=>'setting_form']) !!}
                        <div class="row">
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('free_bid_amount', trans('label.free_bid_amount')) }}*
                                      {!! Form::text('free_bid_amount',null,['class'=>'form-control','placeholder' =>trans('label.free_bid_amount')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     
                                     <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('invite_bid_amount', trans('label.invite_bid_amount')) }}*
                                      {!! Form::text('invite_bid_amount',null,['class'=>'form-control','placeholder' =>trans('label.invite_bid_amount')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('maximum_live_auction', trans('label.maximum_live_auction')) }}*
                                      {!! Form::text('maximum_live_auction',null,['class'=>'form-control','placeholder' =>trans('label.maximum_live_auction')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                     <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('per_bid_price_raise', trans('label.per_bid_price_raise')) }}*
                                      {!! Form::text('per_bid_price_raise',null,['class'=>'form-control', 'placeholder' =>trans('label.per_bid_price_raise')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                    <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('per_bid_count_raise', trans('label.per_bid_count_raise')) }}*
                                      {!! Form::text('per_bid_count_raise',null,['class'=>'form-control','placeholder' =>trans('label.per_bid_count_raise')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                 <!--   <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('per_bid_count_descrease', trans('label.per_bid_count_descrease')) }}
                                      {!! Form::text('per_bid_count_descrease',null,['class'=>'form-control','placeholder' =>trans('label.per_bid_count_descrease')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div> -->

                                    <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('discount_per_bid_price', trans('label.discount_per_bid_price')) }}*
                                      {!! Form::text('discount_per_bid_price',null,['class'=>'form-control', 'placeholder' =>trans('label.discount_per_bid_price')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('bonus_bid_day_count', trans('label.bonus_bid_day_count')) }}*
                                      {!! Form::text('bonus_bid_day_count',null,['class'=>'form-control','placeholder' =>trans('label.bonus_bid_day_count')]) !!}
                                        <span class="error" style="color:red"></span>
                                       </div>

                                        <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('instant_purchase_expire_day', trans('label.instant_purchase_expire_day')) }}*
                                      {!! Form::text('instant_purchase_expire_day',null,['class'=>'form-control','placeholder' =>trans('label.instant_purchase_expire_day')]) !!}
                                        <span class="error" style="color:red"></span>
                                       </div>

                                        <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('break_start_time', trans('label.break_start_time')) }}*
                                      {!! Form::text('break_start_time',null,['class'=>'form-control start_time','placeholder' =>trans('label.break_start_time')]) !!}
                                        <span class="error" style="color:red"></span>
                                       </div>

                                        <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('break_end_time', trans('label.break_end_time')) }}*
                                      {!! Form::text('break_end_time',null,['class'=>'form-control start_time','placeholder' =>trans('label.break_end_time')]) !!}
                                        <span class="error" style="color:red"></span>
                                       </div>

                               </div>
                             </div>

                               <div class="row">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <div class="text-center custom-pro-edt-ds">
                                        <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#setting_form')">{{ $setting->id ? trans('label.update') : trans('label.save') }}
                                        </button>
                                        <input type='hidden' name='setting_id' value='{{ $setting->id ? $setting->id : 0 }}' >
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
<script src="{{ asset('js/admin/jquery.datetimepicker.full.js') }}"></script>

<script type="text/javascript">
function saveform(formId){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);

  $.ajax({
    url:"{{ route('setting_update') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('add_update_setting') }}";   
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
        }); 
        
        jQuery('html, body').animate({
              scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
        }, 500);  
        }  
    }
  });
}

jQuery(document).on("click",".img-1",function(){ 
    var name = $(this).attr("data-name");
    jQuery("#footer_form input[name='"+name+"']").trigger("click"); 
  });
    
    jQuery("#footer_form input[type='file']").change(function(){
      var name = $(this).attr("name");
      console.log(name);
      readURL(this,"."+name); 
      //jQuery("#productimageform").trigger('submit'); 
    });
     function readURL(input,imgClass) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(imgClass).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('.start_time').datetimepicker({
          datepicker:false,
          format:'H:i:s',
          minDate : 0,
          step:60,
          dateFormat: '',
          formatTime:'H:i',
      });

</script>

@endsection