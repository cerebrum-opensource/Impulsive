@extends('layouts.adminapp')
@section('css')
<style>    
   
</style> 
@endsection
@section('content')
@php
  $name = trans('label.add_new_faq');
  if($faq->id)
  $name = trans('label.update_faq');
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
                                    <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> {{ trans('label.faq') }} </a></li>
                                  <!--  <li><a href="#reviews"><i class="icon nalika-picture" aria-hidden="true"></i> Pictures</a></li> -->
                                </ul>
                                
                        {!! Form::model($faq, ['class'=>'form-horizontal','id'=>'faq_form']) !!}
                        <div class="row">
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                   <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('question', trans('label.question')) }}
                                      {!! Form::textarea('question',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.question')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>

                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('answer', trans('label.answer')) }}
                                      {!! Form::textarea('answer',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40,'placeholder' =>trans('label.answer')]) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>
                                      <div class="input-group mg-b-pro-edt">
                                       {{ Form::label('type', trans('label.faq_type')) }}
                                       {!! Form::select('type', $faq_type,null,array("class" => "form-control")) !!}
                                        <span class="error" style="color:red"></span>
                                     </div>


                               </div>
                             </div>

                               <div class="row">
                                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <div class="text-center custom-pro-edt-ds">
                                        <button type="button" class="btn blue-btn btn-ctl-bt waves-effect waves-light m-r-10 model_box_save" onClick="javascript:saveform('#faq_form')">{{ $faq->id ? trans('label.update') : trans('label.save') }}
                                        </button>
                                        <input type='hidden' name='faq_id' value='{{ $faq->id ? $faq->id : 0 }}' >
                                       
                                         <button type="button" onClick="javascript:disCardForm('{{ route('faqs') }}')" class="btn blue-btn btn-ctl-bt waves-effect waves-light">{{ trans('label.discard') }}
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
    url:"{{ route('faq-add') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('faqs') }}";   
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