@extends('layouts.adminapp')
@section('content')
@php
$name = trans('label.add_product_amount_category');
if($product_amount->id)
$name = trans('label.update_product_amount_category');
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
                     <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.product_amount_categories') }} </a></li>
                     <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                  </ul>
                  <div id="myTabContent" class="tab-content custom-product-edit">
                     <div class="product-tab-list tab-pane fade active in" id="description">
                        {!! Form::model($product_amount,['id' => 'category-form', 'files' => false]) !!}
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="add-category-section">
                                 <div class="input-group mg-b-pro-edt">
                                    <span class="input-group-addon"><i class="icon nalika-edit" aria-hidden="true"></i></span>
                                    {!! Form::text('name',null,array('maxlength' => 100,'placeholder'=>'Enter Category Name','class'=>'form-control')) !!}
                                    <span class="error" style="color:red"></span>
                                 </div>
                                 
                                <!--  <div class="input-group mg-b-pro-edt">
                                    <span class="input-group-addon"><i class="icon nalika-edit" aria-hidden="true"></i></span>
                                    {!! Form::text('minimum',null,array('maxlength' => 100,'placeholder'=>'Enter Minimum','class'=>'form-control')) !!}
                                    <span class="error" style="color:red"></span>
                                 </div> -->
                                 
                                 <!-- <div class="input-group mg-b-pro-edt">
                                    <span class="input-group-addon"><i class="icon nalika-edit" aria-hidden="true"></i></span>
                                    {!! Form::text('maximum',null,array('maxlength' => 100,'placeholder'=>'Enter Maximum','class'=>'form-control')) !!}
                                    <span class="error" style="color:red"></span>
                                 </div> -->
                                 
                              </div>
                              
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="text-center custom-pro-edt-ds">
                                 <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#category-form')">{{ $product_amount->id ? trans('label.update') : trans('label.save') }}
                                 </button>
                                 <input type='hidden' name='category_id' value='{{ $product_amount->id ? $product_amount->id : 0 }}' >
                                 <button type="button" onClick="javascript:disCardForm('{{ route('admin_product_amount_categories') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">{{ trans('label.discard') }}
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
   function saveform(formId){
   $('.model_box_save').attr("disabled", "disabled");
   $('span.error').hide().removeClass('active');
   var formData = new FormData($(formId)[0]);
   $.ajax({
   url:"{{ route('product-amount-add') }}",
   data:formData,
   processData: false,
   contentType: false,
   dataType: "json",
   success:function(data){
     
     $('.model_box_save').removeAttr("disabled");
   window.location.href="{{ route('admin_product_amount_categories') }}";
     
   },
   error:function(error){
       $('.model_box_save').removeAttr("disabled");
      
       $.each(error.responseJSON.errors,function(key,value){
            $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
            $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
       }); 
       
       jQuery('html, body').animate({
             scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
         }, 500);    
   }
   });
   }
</script>
@endsection