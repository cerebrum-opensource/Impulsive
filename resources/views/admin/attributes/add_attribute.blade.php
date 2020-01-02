@extends('layouts.adminapp')
@section('content')
@php
$name = trans('label.add_attribute');
if($attribute->id)
$name = trans('label.update_attribute');
$attribute_type = attribute_type();
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
                     <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.attributes') }} </a></li>
                     <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                  </ul>
                  <div id="myTabContent" class="tab-content custom-product-edit">
                     <div class="product-tab-list tab-pane fade active in" id="description">
                        {!! Form::model($attribute,['id' => 'attribute-form', 'files' => false]) !!}
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 inc">
                              <div class="add-category-section">
                                 <div class="input-group mg-b-pro-edt">
                                    <label> {{ trans('label.attribute_type') }} </label>
                                    {!! Form::select('type', array('' => trans('label.select_type')) + $attribute_type,null,array("class" => "form-control attribute_type")) !!} 
                                 </div>
                                 <span class="error" style="color:red"></span>
                              </div>
                              <div class="add-category-section">
                                 <div class="input-group mg-b-pro-edt">
                                    <label> {{ trans('label.attribute_name') }} </label>
                                    {!! Form::text('name',null,array('maxlength' => 100,'placeholder'=>trans('label.enter_attribute_name'),'class'=>'form-control')) !!}
                                 </div>
                                 <span class="error" style="color:red"></span>
                              </div>
                              <div class="add-category-section">
                                 <div class="input-group mg-b-pro-edt">
                                    <label> {{ trans('label.attribute_placeholder') }} </label>
                                    {!! Form::text('place_holder',null,array('maxlength' => 100,'placeholder'=>trans('label.enter_attribute_placeholder'),'class'=>'form-control')) !!}
                                 </div>
                                 <span class="error" style="color:red"></span>
                              </div>
                              <div class="add-category-section other_values"  style="{{ $attribute->type == '1'  ? '': 'display:none' }}">
                                 @if($attribute->id && $attribute->type == '1')
                                 <label> {{ trans('label.options') }}</label>
                                 @foreach($attribute->other_value as $value)
                                 <div class="input-group mg-b-pro-edt">
                                    <input maxlength="100" placeholder="Options" class="form-control other_data" name="other_value[]" type="text" value="{{$value}}">
                                 </div>
                                 @endforeach
                                 @else
                                 <div class="input-group mg-b-pro-edt">
                                    <label> {{ trans('label.options') }}</label>
                                    {!! Form::text('other_value[]','',array('maxlength' => 100,'placeholder'=>'Options','class'=>'form-control other_data')) !!} 
                                 </div>
                                 @endif                
                                 <span class="error" style="color:red"></span>
                                 <button style="margin-left: 10px" class="btn btn-info"  id="append" name="append">
                                {{ trans('label.add_options') }}  </button>     
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="text-center custom-pro-edt-ds">
                                 <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#attribute-form')">{{ $attribute->id ? trans('label.update') : trans('label.save') }}
                                 </button>
                                 <input type='hidden' name='attribute_id' value='{{ $attribute->id ? $attribute->id : 0 }}' >
                                <button type="button" onClick="javascript:disCardForm('{{ route('attribute_list') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">{{ trans('label.discard') }}
                                                        </button>
                              </div>
                           </div>
                        </div>
                        <!-- Close form here to save data -->
                        {!! Form::close() !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
   $(document).ready(function() {
       $("select option:first").attr("disabled", "true");
       $(".category_id option:first").attr("disabled", "true");
       $(".attribute_type").change(function() {
           var value = $('option:selected', this).val();
           if(value == '1' )
               $('.other_values').show();
           else
               $('.other_values').hide();
       });
   })
   
   function saveform(formId){
   $('.model_box_save').attr("disabled", "disabled");
   $('span.error').hide().removeClass('active');
   var formData = new FormData($(formId)[0]);
   $.ajax({
   url:"{{ route('attribute_save') }}",
   data:formData,
   processData: false,
   contentType: false,
   dataType: "json",
   success:function(data){
     
     $('.model_box_save').removeAttr("disabled");
   window.location.href="{{ route('attribute_list') }}";
     
   },
   error:function(error){
       $('.model_box_save').removeAttr("disabled");
      
       $.each(error.responseJSON.errors,function(key,value){
           
            $('input[name="'+key+'"]').parent().parent().find('span.error').html(value).addClass('active').show();
            $('select[name="'+key+'"]').parent().parent().find('span.error').html(value).addClass('active').show();
   
           if(!key.indexOf("other_value"))
           {
           $('.other_data').parent().parent().find('span.error').html(value).addClass('active').show();
             //$(".after-add-more").last().find('span.error').html(value).addClass('active').show();
           }
       }); 
       
       jQuery('html, body').animate({
             scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
         }, 500);    
   }
   });
   }
   
   
   jQuery(document).ready( function () {
       $("#append").click( function(e) {
         e.preventDefault();
       $(".inc").append('<div class="input-group mg-b-pro-edt">\
               \
               <input class="form-control" type="text" name="other_value[]" placeholder="Options">\
               <br>\
               <br><a href="#" class="remove_this btn btn-danger">remove</a>\
           </div>');
       return false;
       });
   
   jQuery(document).on('click', '.remove_this', function() {
       jQuery(this).parent().remove();
       return false;
       });
   
   });
</script>
@endsection