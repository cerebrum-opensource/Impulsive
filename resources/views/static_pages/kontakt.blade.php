@extends('layouts.app')
@section('title')
 {{ trans('label.contact_us') }}
@endsection
@section('css')
   <style>
      #exampleFormControlTextarea1{
         min-height: 180px;
      }
   </style>
@endsection

@section('content')
<div class="live-deals live-deals2">
   <!--live-deals start--> 
   <div class="container">
      <h3 class="sec-heading"><span>{{ trans('label.contact_us') }} </span></h3>
      <div class="free-otr">
         <div class="row">
            <div class="col-md-12">

                @if(session()->has('message.level'))
                  <div class="alert alert-{{ session('message.level') }} alert-dismissible"> 
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! session('message.content') !!}
                   </div>
               @endif
               <div class="free-bar free-bar2">
                  <h4>{{ trans('label.contact_support') }} </h4>
                  <div class="mt-4">
                      {!! Form::Open(['id' => 'query-form']) !!}
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="usr" class="label-text">{{ trans('label.name') }}* </label>
                              <input type="text" name="name" class="form-control form-control2" id="usr">
                              <span class="error" style="color:red"></span>
                           </div>
                           <div class="form-group">
                              <label for="usr" class="label-text">{{ trans('label.email_placeholder') }}*   </label>
                              <input type="text" name="email" class="form-control form-control2" id="usr">
                              <span class="error" style="color:red"></span>
                           </div>
                           <div class="form-group">
                              <label for="usr" class="label-text">{{ trans('label.username_if') }}    </label>
                              <input type="text" name="username" class="form-control form-control2" id="usr">
                              <span class="error" style="color:red"></span>
                           </div>
                           <div class="form-check">
                              <input type="checkbox" name="is_checked" class="form-check-input" id="exampleCheck1">
                              <label class="form-check-label label-text" for="exampleCheck1">{{ trans('label.check_me_out') }} </label>
                              <span class="error" style="color:red"></span>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="usr" class="label-text">  {{ trans('label.subject') }}*  </label>
                              <input type="text" name="subject" class="form-control form-control2" id="usr">
                              <span class="error" style="color:red"></span>
                           </div>
                           <div class="form-group">
                              <label class="label-text" for="exampleFormControlTextarea1"> {{ trans('label.message') }} </label>
                              <textarea class="form-control form-control2 form-textarea" name="message" id="exampleFormControlTextarea1" rows="2"></textarea>
                              <span class="error" style="color:red"></span>
                           </div>
                        </div>
                     </div>
                    
                      <button type="button" class="btn-form btn-form2 mt-3 model_box_save"  onClick="javascript:saveform('#query-form')" >{{ trans('label.send') }}</button>
                     {!! Form::close() !!}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--live-deals end-->   
</div>

@include('news_letter')
@endsection
@section('javascript')

<script type="text/javascript">

   $.ajaxSetup({
    type:"POST",
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    beforeSend:function(){
      //  $('body').waitMe();
    },
    complete:function(){
      //  $('body').waitMe('hide');
    },
    error:function(error){
        

    }
});
function saveform(formId){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');
  var formData = new FormData($(formId)[0]);

  $.ajax({
    url:"{{ route('frontend_add_to_query') }}",
    data:formData,
    processData: false,
    contentType: false,
    dataType: "json",
    beforeSend:function(){
      $('.free-bar').waitMe();
    },
    complete:function(){
        $('.free-bar').waitMe('hide');
    },
    success:function(data){
        $('.model_box_save').removeAttr("disabled");
        window.location.href="{{ route('pages_route',['contact_us']) }}";    
    },
    error:function(error){
       // console.log(error.status);
        if(error.status == 500){
            alert('please reload');
        }
        else {
        $('.model_box_save').removeAttr("disabled");
        $.each(error.responseJSON.errors,function(key,value){  
          $('textarea[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
          $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
          $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
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