@extends('layouts.app')
@section('title')
 {{ trans('label.invite_friend') }}
@endsection

@section('content')
<div class="live-deals live-deals2"><!--live-deals start--> 
         <div class="container">
             @if(session()->has('message.level'))
         <div class="alert alert-{{ session('message.level') }} alert-dismissible"> 
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {!! session('message.content') !!}
         </div>
         @endif
            
         <h3 class="sec-heading"><span>{{ trans('label.invite_friend') }} </span></h3>
            <div class="free-bar">
               <h4>{{ trans('label.invite_friend_now') }} </h4>
         
               <div class="mt-4">
                   {!! Form::model($friendInvitation, ['class'=>'form-horizontal','id'=>'friend_invitation_form']) !!}
                  <div class="form-group">
                    <label for="usr" class="label-text"> {{ trans('label.name_of_friend') }}:</label>
                    {{ Form::text('name', null, array('class' => 'form-control  form-control2','placeholder' =>trans('label.name_of_friend'))) }}
                      <span class="error" style="color:red"></span>
                  </div>
                  <div class="form-group">
                    <label for="usr" class="label-text">{{ trans('label.email_of_friend') }}:</label>
   
                    {{ Form::text('email', null, array('class' => 'form-control  form-control2','placeholder' =>trans('label.email_of_friend'))) }}
                      <span class="error" style="color:red"></span>
                  </div>
                  <div class="form-group">
                    <label for="usr" class="label-text">{{ trans('label.invitation_text') }}:</label>
                   {!! Form::textarea('invite_text', null, ['class' => 'form-control', 'rows' => 5, 'cols' => 40,'maxlength' =>"10000"]) !!}
                     <span class="error" style="color:red"></span>
                  </div>
                  <input type='hidden' name='user_id' value="{{ encrypt_decrypt('encrypt', $user->id) }}" >
                  <input type="button" value="{{ trans('label.invite') }}"  onClick="javascript:saveform('#friend_invitation_form')" class="model_box_save btn-form">

                   {!! Form::close() !!}
               </div>
               
            </div>
            <div class="free-btm">
                  <h3>{{ trans('label.benefit_from_it') }} </h3>
                  <h5>{{ trans('label.reward_message_title') }} </h5>
                  <p> {{ trans('label.reward_message_detail') }} 
                 </p>
               </div>
         </div>
      </div>
@endsection
@section('javascript')
<script type="text/javascript">

  $.ajaxSetup({
    type:"POST",
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    beforeSend:function(){
    },
    complete:function(){

    }
  });


   function saveform(formId){
     $('.model_box_save').attr("disabled", "disabled");
     $('span.error').hide().removeClass('active');
     var formData = new FormData($(formId)[0]);
   
     $.ajax({
       url:"{{ route('send-invite-friend') }}",
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
           window.location.reload();   
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