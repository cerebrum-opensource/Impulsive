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
    .product-images .rounded {
    width: 150px;
    height: 150px;
    padding: 20px;
}
 </style> 

<link rel="stylesheet" href="{{ asset('css/admin/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/jquery.datetimepicker.css') }}">

@endsection
@section('content')

@php
  $name = trans('label.add_promotional_package');
  if($package->id)
  $name = trans('label.update_promotional_package');
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
                                    <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.promotional_packages') }} </a></li>
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>
                                
                        {!! Form::model($package, ['class'=>'form-horizontal','id'=>'package_form','files'=>true]) !!}
                        <div class="row">
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                  <div class="review-content-section">
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('name', trans('label.name')) }}*
                                        {{ Form::text('name', null, array('class' => 'form-control','placeholder' =>trans('label.name'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('price', trans('label.price')) }}*
                                        {{ Form::text('price', null, array('class' => 'form-control','placeholder' =>trans('label.price'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('bid', trans('label.number_of_bid')) }}*
                                       {{ Form::text('bid', null, array('class' => 'form-control','placeholder' =>trans('label.number_of_bid'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                      <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('bonus', trans('label.bonus_bid')) }}
                                       {{ Form::text('bonus', null, array('class' => 'form-control','placeholder' =>trans('label.bonus_bid'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                      <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('description', trans('label.description')) }}*
                                        {!! Form::textarea('description',null,['class'=>'form-control', 'rows' => 4, 'cols' => 40,'placeholder' =>trans('label.description')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                      <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('image', trans('label.image')) }}*
                                        <div class="card card-body product-images">
                                         <input type="file" name="image">
                                        <a href="#">
                                          <img src="@if($package->image) {{ asset('/images/admin/packages/').'/'.$package->id.'/'.$package->image }} @else {{ asset('images/admin/img/notification/4.jpg') }} @endif" class="rounded float-left img-1 image" alt="" data-name="image">                   
                                        </a>
                                        <span class="error" style="color:red"></span>
                                      </div>       
                                     </div>

                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('start_date', trans('label.start_date')) }}*
                                       {{ Form::text('start_date', null, array('class' => 'form-control start_datepicker','placeholder' =>trans('label.start_date'))) }}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                     <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('end_date', trans('label.end_date')) }}*
                                       {{ Form::text('end_date', null, array('class' => 'form-control end_datepicker','placeholder' =>trans('label.end_date'))) }}
                                        <span class="error" style="color:red"></span> 
                                     </div>
                                  </div>
                               </div>
                               
                             </div>

                               <div class="row">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <div class="text-center custom-pro-edt-ds">
                                        <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#package_form')">{{ $package->id ? trans('label.update') : trans('label.save') }}
                                        </button>
                                        <input type='hidden' name='package_id' value='{{ $package->id ? $package->id : 0 }}' >
                                        <button type="button" onClick="javascript:disCardForm('{{ route('promo_packages') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">{{ trans('label.discard') }}
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
<script src="{{ asset('js/admin/jquery.datetimepicker.full.js') }}"></script>


<script type="text/javascript">
function saveform(formId){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);

  $.ajax({
    url:"{{ route('promo_package_save') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('promo_packages') }}";   
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
             $('textarea[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
             $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
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
    jQuery("#package_form input[name='"+name+"']").trigger("click"); 
  });
    
    jQuery("#package_form input[type='file']").change(function(){
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

/*
  $(document).ready(function() {
    $('.start_datepicker').datepicker({
        autoclose: true,
        dateFormat: 'mm-dd-yy',
        minDate: '-0d',
        onSelect: function(selected) {
            var minDate = new Date(selected.date.valueOf());
            console.log(minDate);
            $(".end_datepicker").datepicker("setminDate", minDate);
       }
    })

    $(".end_datepicker").datepicker({
            autoclose: true,
            format: "mm-dd-yyyy",
        })
        .on("changeDate", function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $(".start_datepicker").datepicker("setEndDate", minDate);
    });
});

  */

  $(document).ready(function() {


        var checkPastTime = function(inputDateTime) {
        if (typeof(inputDateTime) != "undefined" && inputDateTime !== null) {
            var current = new Date();
            if (inputDateTime.getFullYear() < current.getFullYear()) {
                $('.datetimepicker').datetimepicker('reset');
                alert("Sorry! Past date time not allow.");
            } else if ((inputDateTime.getFullYear() == current.getFullYear()) && (inputDateTime.getMonth() < current.getMonth())) {
                $('.datetimepicker').datetimepicker('reset');
                alert("Sorry! Past date time not allow.");
            }

            if (inputDateTime.getDate() == current.getDate()) {
                if (inputDateTime.getHours() < current.getHours()) {
                    $('.datetimepicker').datetimepicker('reset');
                }
                this.setOptions({
                    minTime: current.getHours() + ':'+current.getMinutes() //here pass current time hour
                });
            } else {
                this.setOptions({
                    minTime: false
                });    
            }    
                var mi = parseInt(inputDateTime.getMinutes());
                var ho = parseInt(inputDateTime.getHours());
                if(inputDateTime.getMinutes() < 50){
                  var mi = parseInt(inputDateTime.getMinutes())+parseInt(10);
                }
                else {
                  var ho = parseInt(inputDateTime.getHours())+parseInt(1);
                  var mi = "00";
                }
                if(inputDateTime.getHours() == 23 && inputDateTime.getMinutes() >= 50){
                  var ho = "00";
                  var mi = "00";
                  var d = inputDateTime.getDate();
                  var m = inputDateTime.getMonth();
                  var y = inputDateTime.getFullYear();
                  var minDate = new Date(y, m, d + 1);
                }
                else {
                  var d = inputDateTime.getDate();
                  var m = inputDateTime.getMonth();
                  var y = inputDateTime.getFullYear();
                  var minDate = new Date(y, m, d);
                }
                var maxtime =  ho + ':'+mi;
                $('.end_datepicker').datetimepicker({
                    format:'Y-m-d H:i',
                    minDate : minDate,
                    yearStart : currentYear.getFullYear(), // Start value for current Year selector
                    step:10,
                    formatTime:'h:i a',
                    minTime:maxtime
                });
        }
      };
      
      var checkFutureTime = function(inputDateTime) {
        if (typeof(inputDateTime) != "undefined" && inputDateTime !== null) {
            var current = new Date();
            if (inputDateTime.getFullYear() < current.getFullYear()) {
                $('.start_datepicker').datetimepicker('reset');
                alert("Sorry! Past date time not allow.");
            } else if ((inputDateTime.getFullYear() == current.getFullYear()) && (inputDateTime.getMonth() < current.getMonth())) {
                $('.start_datepicker').datetimepicker('reset');
                alert("Sorry! Past date time not allow.");
            }

            if (inputDateTime.getDate() == current.getDate()) {
                if (inputDateTime.getHours() < current.getHours()) {
                    $('.start_datepicker').datetimepicker('reset');
                }
                this.setOptions({
                    minTime: current.getHours() + ':'+current.getMinutes() //here pass current time hour
                });
            } else {
                this.setOptions({
                    minTime: false
                });    
            }    
                var mi = parseInt(inputDateTime.getMinutes());
                var ho = parseInt(inputDateTime.getHours());
                /*if(inputDateTime.getMinutes() < 50){
                  var mi = parseInt(inputDateTime.getMinutes())+parseInt(10);
                }
                else {
                  var ho = parseInt(inputDateTime.getHours())+parseInt(1);
                  var mi = "00";
                }
                if(inputDateTime.getHours() == 23 && inputDateTime.getMinutes() >= 50){
                  var ho = "00";
                  var mi = "00";
                  var d = inputDateTime.getDate();
                  var m = inputDateTime.getMonth();
                  var y = inputDateTime.getFullYear();
                  var minDate = new Date(y, m, d + 1);
                }
                else {
                  var d = inputDateTime.getDate();
                  var m = inputDateTime.getMonth();
                  var y = inputDateTime.getFullYear();
                  var minDate = new Date(y, m, d);
                }*/
                var d = inputDateTime.getDate();
                var m = inputDateTime.getMonth();
                var y = inputDateTime.getFullYear();
                var maxtime =  ho + ':'+mi;
                var minDate = new Date(y, m, d);
                $('.start_datepicker').datetimepicker({
                    format:'Y-m-d H:i',
                    maxDate : minDate,
                    yearStart : currentYear.getFullYear(), // Start value for current Year selector
                    step:10,
                    formatTime:'h:i a',
                    maxTime:maxtime
                });
        }
      };


      var currentYear = new Date();
      $('.start_datepicker').datetimepicker({
          format:'Y-m-d H:i',
          minDate : 0,
          yearStart : currentYear.getFullYear(), // Start value for current Year selector
          onChangeDateTime:checkPastTime,
          onShow:checkPastTime,
          step:10,
          formatTime:'h:i a',
      });

       $('.end_datepicker').datetimepicker({
          format:'Y-m-d H:i',
          minDate : 0,
          yearStart : currentYear.getFullYear(), // Start value for current Year selector
          onChangeDateTime:checkFutureTime,
          onShow:checkFutureTime,
          step:10,
          formatTime:'h:i a',
      });


   /* $(".start_datepicker").datepicker({
        autoclose: true,
        dateFormat: "mm-dd-yy",
        minDate: '0d',
        onSelect: function() {
            //- get date from another datepicker without language dependencies
            var minDate = $('.start_datepicker').datepicker('getDate');
            var d = minDate.getDate();
            var m = minDate.getMonth();
            var y = minDate.getFullYear();
            var minDate = new Date(y, m, d + 1);

            $(".end_datepicker").datepicker("change", { minDate: minDate });
        }
    });*/

  /*  $(".end_datepicker").datepicker({
        autoclose: true,
        dateFormat: "mm-dd-yy",
        minDate: '1d',
        onSelect: function() {
            //- get date from another datepicker without language dependencies
            var maxDate = $('.end_datepicker').datepicker('getDate');

            var d = maxDate.getDate();
            var m = maxDate.getMonth();
            var y = maxDate.getFullYear();
            var maxDate = new Date(y, m, d - 1);
            $(".start_datepicker").datepicker("change", { maxDate: maxDate });
        }
    });*/
});
</script>

@endsection