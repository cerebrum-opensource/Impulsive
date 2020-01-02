@extends('layouts.adminapp')
@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/jquery.datetimepicker.css') }}">
<style>
.custom-width{
  width:50px;
}
</style>

@endsection
@section('content')
@php
$name = trans('label.add_new_auction');
$hours = '';
$minutes = '';
$seconds = '';
if($auction->id)
{
  $name = trans('label.update_auction');

  $count = explode(":",$auction->duration);
  $hours = $count[0] !='00' ? (int)$count[0]  : '' ;
  $minutes = $count[1] !='00' ? (int)$count[1]  : '' ;
  $seconds = $count[2] !='00' ? (int)$count[2]  : '' ;
}



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
                     <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.auction')}} </a></li>
                     <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                  </ul>
                  {!! Form::model($auction, ['class'=>'form-horizontal','id'=>'auction_form']) !!}
                  <div class="row">
                     <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="review-content-section">
                           <div class="input-group mg-b-pro-edt">
                              {{ Form::label('product', trans('label.product')) }}*
                              {{ Form::text('product', $auction->id ? $auction->product->product_title : null, array('class' => 'form-control ','id'=>'product','placeholder' =>trans('label.product'))) }}
                              <span class="error" style="color:red"></span>
                               {!! Form::hidden('product_id', null,array("id" => "")) !!} 
                              <div id="suggesstion-box"></div>
                           </div>
                         
                           <div class="input-group mg-b-pro-edt">
                              {{ Form::label('duration', trans('label.counter')) }}*
                              Hours :  <input type="text" name="hours" id="hours" value ="{{ $hours }}" class=" custom-width" />  
                              Minutes: <input type="text" name="minutes" id="minutes" value ="{{ $minutes }}" class=" custom-width"/>
                              Second:  <input type="text" name="seconds" id="seconds" value ="{{ $seconds }}" class=" custom-width"/><br>
                              <input type="hidden" name="counter" />
                              <span class="error" id='counter' style="color:red"></span>
                           </div>

                           <div class="input-group mg-b-pro-edt">
                              {{ Form::label('final_countdown', trans('label.final_countdown')) }}*
                              {{ Form::text('final_countdown', null, array('class' => 'form-control','placeholder' =>trans('label.final_countdown'))) }}
                              <span class="error" style="color:red"></span>
                           </div>
                          <!-- <div class="input-group mg-b-pro-edt">
                              {{ Form::label('discount', trans('label.discount')) }}
                              {{ Form::text('discount', null, array('class' => 'form-control','placeholder' =>trans('label.discount'))) }}
                              <span class="error" style="color:red"></span>
                           </div> -->

                           <div class="input-group mg-b-pro-edt">
                              {{ Form::label('detail', trans('label.detail')) }}
                              <textarea placeholder="{{ trans('label.detail') }}" name="detail" class="form-control" rows="8">{{ @$auction->detail }}</textarea>
                              <span class="error" style="color:red"></span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="text-center custom-pro-edt-ds">
                           <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#auction_form')">{{ $auction->id ? trans('label.update') : trans('label.save') }}
                           </button>
                           <input type='hidden' name='auction_id' value='{{ $auction->id ? $auction->id : 0 }}' >
                           <button type="button" onClick="javascript:disCardForm('{{ route('auctions_dashboard') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">Discard
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
  $(function() {
    $.ajaxSetup({
        headers: {
            'X-XSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });
});
   function saveform(formId){
     $('.model_box_save').attr("disabled", "disabled");
     $('span.error').hide().removeClass('active');
     var formData = new FormData($(formId)[0]);
   
     $.ajax({
       url:"{{ route('add_auction') }}",
       data:formData,
       processData: false,
       contentType: false,
       dataType: "json",
       success:function(data){
           $('.model_box_save').removeAttr("disabled");
           window.location.href="{{ route('auctions_dashboard') }}";   
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
                //console.log($('select[name="'+key+'"]').parent());
                $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
           }); 
           
           jQuery('html, body').animate({
                 scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
           }, 500);  
           }  
       }
     });
   }
   
   
   /*// AJAX call for autocomplete 
   $(document).ready(function(){
     $("#product_id").keyup(function(){
       $.ajax({
       type: "GET",
       url: "{{ route('product_list_auto_complete') }}",
       data:'keyword='+$(this).val(),
       beforeSend: function(){
         $("#product_id").addClass("autocomplete-loading");
       },
       success: function(data){
         $("#suggesstion-box").show();
         $("#suggesstion-box").html(data);
         //$("#product_id").css("background","");
         $("#product_id").removeClass("autocomplete-loading");
       }
       });
     });
   });*/
   
   
   $('#product').autocomplete({
         minLength:1,
         source: function( request, response ) {
            
         var inputData = $('#product').val();
           jQuery.ajax({
           url: "{{ route('product_list_auto_complete') }}",
           data: {data: inputData},
           type:'POST',
           dataType: "json",
           beforeSend: function(){
             $('input[name=product_id]').val('');
             $("#product").addClass("autocomplete-loading");
   
           },
           success: function( data ) { 
              $("#product").removeClass("autocomplete-loading");
             
             response($.map(data.html, function (item) {
               return {
                   label: item.product_title,
                   id: item.id,
                   value: item.product_title
               };
             }));
           }
         });
         },
         select: function( event, ui ) {
   //        console.log(event);
            console.log(ui.item);
            $('input[name=product_id]').val(ui.item.id);
           //$("#product_id").val(ui.item.id);
           //$("#product_id").val(val);
         }
     });
   
   $(function () {
   var checkPastTime = function(inputDateTime) {
    if (typeof(inputDateTime) != "undefined" && inputDateTime !== null) {
        var current = new Date();
 
        //check past year and month
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
           // console.log(current.getHours() +parseInt(1));
          //  console.log(current.getHours() + ':'+current.getMinutes());
            this.setOptions({
                minTime: current.getHours() +parseInt(1) + ':00' //here pass current time hour
            });
        } else {
            this.setOptions({
                minTime: false
            });
        }
    }
  };
 
var currentYear = new Date();
$('.datetimepicker').datetimepicker({
    format:'Y-m-d, H',
    minDate : 0,
    yearStart : currentYear.getFullYear(), // Start value for current Year selector
    onChangeDateTime:checkPastTime,
    onShow:checkPastTime,
    step:60,
    formatTime:'H',
});
  

$('#live_auction').change(function() {
  if($(this).is(':checked')){
    $('.datetimepicker_div').hide();
  }
  else {
    $('.datetimepicker_div').show();
  }
}); 

}); 
$(document).ready(function(){
  if($('#live_auction').is(':checked')){
    $('.datetimepicker_div').hide();
  }
  else {
    $('.datetimepicker_div').show();
  }

});

</script>
@endsection