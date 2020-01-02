@extends('layouts.adminapp')
@section('css')
<style>    
   
</style> 
@endsection
@section('content')
@php
  $name = trans('label.newsletter');
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
                                    <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.newsletter') }} </a></li>
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>
                                
                        {!! Form::model($news_letter, ['class'=>'form-horizontal','id'=>'footer_form']) !!}
                        <div class="row">
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('header1', trans('label.header_1')) }}
                                      {!! Form::textarea('header1',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.header_1')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                    <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('header2', trans('label.header_2')) }}
                                      {!! Form::textarea('header2',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.header_2')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('header3', trans('label.header_3')) }}
                                      {!! Form::textarea('header3',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.header_3')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('header4', trans('label.header_4')) }}
                                      {!! Form::textarea('header4',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.header_4')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('place_holder', trans('label.place_holder_text')) }}
                                      {!! Form::textarea('place_holder',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.place_holder_text')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('paragraph_1', trans('label.paragraph_1')) }}
                                      {!! Form::textarea('paragraph_1',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.paragraph_1')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                     
                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('paragraph_2', trans('label.paragraph_2')) }}
                                      {!! Form::textarea('paragraph_2',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.paragraph_2')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('btn_text', trans('label.button_text')) }}
                                      {!! Form::textarea('btn_text',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.button_text')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                               </div>
                             </div>

                               <div class="row">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <div class="text-center custom-pro-edt-ds">
                                        <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#footer_form')">{{ $news_letter->id ? trans('label.update') : trans('label.save') }}
                                        </button>
                                        <input type='hidden' name='widget_id' value='{{ $news_letter->id ? $news_letter->id : 0 }}' >
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
    url:"{{ route('news_letter-add') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('add_update_news_letter') }}";   
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



</script>

@endsection