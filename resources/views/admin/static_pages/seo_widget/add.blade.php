@extends('layouts.adminapp')
@section('css')
<style>    
   
</style> 
@endsection
@section('content')
@php
  $name = trans('label.add_new_widget');
  if($widget->id)
  $name = trans('label.update_widget');

  $page_type = page_type();
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
                                    <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.seo_widget') }} </a></li>
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>
                                
                        {!! Form::model($widget, ['class'=>'form-horizontal','id'=>'widget_form']) !!}
                        <div class="row">
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                    <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('page_type', trans('label.page_type')) }}*
                                        {!! Form::select('page_type',$page_type,null,array("class" => "form-control", 'placeholder' => trans('label.please_select'))) !!}
                                        <span class="error" style="color:red"></span>
                                    </div>

                                    <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('header1', trans('label.header_1')) }}*
                                      {!! Form::textarea('header1',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.header_1')]) !!}
                                        <span class="error" style="color:red"></span>
                                    </div>

                                    <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('header2', trans('label.header_2')) }}*
                                      {!! Form::textarea('header2',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.header_2')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('paragraph_1', trans('label.paragraph_1')) }}*
                                      {!! Form::textarea('paragraph_1',null,['class'=>'form-control textarea', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.paragraph_1')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('paragraph_2', trans('label.paragraph_2')) }}*
                                      {!! Form::textarea('paragraph_2',null,['class'=>'form-control textarea', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.paragraph_2')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     
                               </div>
                             </div>

                               <div class="row">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <div class="text-center custom-pro-edt-ds">
                                        <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#widget_form')">{{ $widget->id ? trans('label.update') : trans('label.save') }}
                                        </button>
                                        <input type='hidden' name='id' value='{{ $widget->id ? $widget->id : 0 }}' >
                                       
                                         <button type="button" onClick="javascript:disCardForm('{{ route('seo_widgets') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">{{ trans('label.discard') }}
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
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/adapters/jquery.js') }}"></script>

<script type="text/javascript">
  $('.textarea').ckeditor({
      allowedContent:true
  });
function saveform(formId){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  for (instance in CKEDITOR.instances) {
    CKEDITOR.instances[instance].updateElement();
  }
  var formData = new FormData($(formId)[0]);

  $.ajax({
    url:"{{ route('seo_widget-add') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('seo_widgets') }}";   
    },
    error:function(error){
        console.log(error.status);
        if(error.status == 500){
            alert('please reload');
        }
        else {
        $('.model_box_save').removeAttr("disabled");
        $.each(error.responseJSON.errors,function(key,value){  
          $('textarea[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
          $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
          $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();

          if(key == 'logo'){
            $('#'+key).html(value).addClass('active').show();
          }

        }); 
        
        jQuery('html, body').animate({
              scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
        },500);  
        }  
    }
  });
}

jQuery(document).on("click",".img-1",function(){ 
    var name = $(this).attr("data-name");
    jQuery("#widget_form input[name='"+name+"']").trigger("click"); 
  });
    
    jQuery("#widget_form input[type='file']").change(function(){
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

</script>

@endsection