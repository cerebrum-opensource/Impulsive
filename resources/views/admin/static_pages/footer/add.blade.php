@extends('layouts.adminapp')
@section('css')
<style>    
   
</style> 
@endsection
@section('content')
@php
  $name = trans('label.add_new_footer_link');
  if($footer_link->id)
  $name = trans('label.update_footer_link');
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
                                    <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.footer_links') }} </a></li>
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>
                                
                        {!! Form::model($footer_link, ['class'=>'form-horizontal','id'=>'footer_links_form']) !!}
                        <div class="row">
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                   <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('name', trans('label.name')) }}
                                      {!! Form::text('name',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.name')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('link_url', trans('label.link_url')) }}
                                      {!! Form::text('link_url',null,['class'=>'form-control','placeholder' =>trans('label.link_url')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                               </div>
                             </div>

                               <div class="row">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <div class="text-center custom-pro-edt-ds">
                                        <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#footer_links_form')">{{ $footer_link->id ? trans('label.update') : trans('label.save') }}
                                        </button>
                                        <input type='hidden' name='footer_link_id' value='{{ $footer_link->id ? $footer_link->id : 0 }}' >
                                       
                                         <button type="button" onClick="javascript:disCardForm('{{ route('footer_links') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">{{ trans('label.discard') }}
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
    url:"{{ route('footer_link-add') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('footer_links') }}";   
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