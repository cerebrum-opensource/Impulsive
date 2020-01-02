@extends('layouts.adminapp')
@section('css')
 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/js/bootstrap-colorpicker.min.js"></script>  
<style>    
  
</style> 
@endsection
@section('content')
@php
  $name = trans('label.add_new_slider');
  if($slider->id)
  $name = trans('label.update_slider');


  $slider_text_position = slider_text_position();
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
                                    <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.sliders') }} </a></li>
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>
                                
                        {!! Form::model($slider, ['class'=>'form-horizontal','id'=>'slider_form']) !!}
                        <div class="row">
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                      <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('heading', trans('label.header')) }}*
                                        {!! Form::textarea('heading',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.header')]) !!}
                                        <span class="error" style="color:red"></span>
                                      </div>

                                      <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('header_text_position', trans('label.position')) }}*
                                        {!! Form::select('header_text_position', $slider_text_position,null,array("class" => "form-control")) !!}
                                        <span class="error" style="color:red"></span>
                                      </div>
                                      <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('background_color', trans('label.background_color')) }}*
                                        {!! Form::textarea('background_color', null,['class'=>'form-control','id'=>'color', 'rows' => 2, 'cols' => 40,]) !!}
                                       <!-- <input class="form-control" type="text" name="background_color" id="color"/> -->
                                        <script>
                                          $('#color').colorpicker({});
                                        </script>
                                        <span class="error" style="color:red"></span>
                                      </div>

                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('heading_detail', trans('label.header_detail')) }}*
                                      {!! Form::textarea('heading_detail',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.header_detail')]) !!}
                                        <span class="error" style="color:red"></span>
                                      </div>

                                  <!--    <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('header_detail_text_position', trans('label.header_detail_position')) }}*
                                        {!! Form::select('header_detail_text_position', $slider_text_position,null,array("class" => "form-control")) !!}
                                        <span class="error" style="color:red"></span>
                                      </div> -->
                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('button_text', trans('label.button_text')) }}*
                                      {!! Form::textarea('button_text',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.button_text')]) !!}
                                        <span class="error" style="color:red"></span>
                                      </div>
                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('button_link', trans('label.link')) }}*
                                      {!! Form::text('button_link',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.link')]) !!}
                                        <span class="error" style="color:red"></span>
                                      </div>
                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('other_text', trans('label.other_text')) }}*
                                      {!! Form::textarea('other_text',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.other_text')]) !!}
                                        <span class="error" style="color:red"></span>
                                      </div>

                                      <div class="input-group mg-b-pro-edt">
                                        {{ Form::label('image', trans('label.slider_image')) }}*
                                        <div class="card card-body product-images">
                                         <input type="file" name="image">
                                        <a href="#">
                                            <img src="@if($slider->image) {{ asset('/images/admin/sliders/').'/'.$slider->id.'/'.$slider->image }} @else {{ asset('images/admin/img/notification/4.jpg') }} @endif" class="rounded float-left img-1 image" alt="" data-name="image">                   
                                        </a>
                                        <span class="error" id="image" style="color:red"></span>
                                      </div>       
                                     </div>

                               </div>
                             </div>

                               <div class="row">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <div class="text-center custom-pro-edt-ds">
                                        <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#slider_form')">{{ $slider->id ? trans('label.update') : trans('label.save') }}
                                        </button>
                                        <input type='hidden' name='slider_id' value='{{ $slider->id ? $slider->id : 0 }}' >
                                       
                                         <button type="button" onClick="javascript:disCardForm('{{ route('sliders') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">{{ trans('label.discard') }}
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
    url:"{{ route('slider-add') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('sliders') }}";   
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

          if(key == 'image'){
            $('#'+key).html(value).addClass('active').show();
          }

        }); 
        
        jQuery('html, body').animate({
              scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
        }, 500);  
        }  
    }
  });
}
//$('#colorpickerHolder').ColorPicker({flat: true});
jQuery(document).on("click",".img-1",function(){ 
    var name = $(this).attr("data-name");
    jQuery("#slider_form input[name='"+name+"']").trigger("click"); 
  });
    
    jQuery("#slider_form input[type='file']").change(function(){
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