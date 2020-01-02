<div id="mySidenav" class="sidenavlogin sidenav sidenav2">
   <p> < {{ trans('label.close_menu') }} </p>
   <h3> {{ trans('label.login_now') }} </h3>
   <div class="table-otr table3">
      @if ($errors->has('email') && old('is_frontend'))
      <span class="help-block">
      <strong>{{ $errors->first('email') }}</strong>
      </span>
      @endif
      <form method="POST" onsubmit="return LoginUser()" id="login_form">
         @csrf
         <table class="table">
            <tr>
               <td  colspan="2" class="text-center bg-color">{{ trans('label.login') }} </td>
            </tr>
            <tr>
               <td class="konstand-otr" colspan="2">
                  <input type="hidden" name="user_type" value="1">
                  <div class="form-group">
                     <label for="usr" class="label-text">{{ trans('label.username') }} </label>
                     <input type="text" class="form-control form-control2" id="log_usr" name="email" value="{{ old('email') }}">
                     <span class="error" style="color:red"></span>
                  </div>
               </td>
            </tr>
            <tr>
               <td colspan="2">
                  <div class="form-group">
                     <label for="pass" class="label-text">{{ trans('label.password') }} </label>
                     <input type="password" class="form-control form-control2" id="log_pass" name="password">
                     <span class="error" style="color:red"></span>
                  </div>
               </td>
               <input type="hidden" name="is_frontend" value="1" >
            </tr>
            <tr>
               <td><input type="submit" value="{{ trans('label.login') }} " class="btn-form btn-form2 mt-3"></td>
               <td><a href="{{ route('password.request') }}"class="btn-form btn-form2 mt-3"> {{ trans('label.forgot_password') }} </td>
            </tr>
         </table>
      </form>
   </div>
   <a href="javascript:void(0)" class="closebtn" onclick="closeLoginNav()">&times;</a>
</div>
<script type="text/javascript">
function LoginUser()
{
  $('.model_box_save').attr("disabled", "disabled");
  var token    = $("input[name=_token]").val();
  var email    = $("input[name=email]").val();
  var password = $("input[name=password]").val();
  var data = {
      email:email,
      password:password
  };

  if(email == '' || password == ''){
      return false;
  }
  // Ajax Post 
  $.ajax({
      type: "post",
      url: "{{ route('login') }}",
      data: data,
      cache: false,
      beforeSend:function(){
        $('.sidenavlogin').waitMe();
      },
      success: function (data)
      {    
         if(data.auth){
            window.location.href="/"+data.intended;  
         }
         $('.model_box_save').removeAttr("disabled");
      },
      complete:function(){
        $('.sidenavlogin').waitMe('hide');
      },
      error: function (error){
         $('.model_box_save').removeAttr("disabled");

         if(error.responseJSON.email){
           $('input[name="email"]').parent().find('span.error').html(error.responseJSON.email).addClass('active').show();
         }

         $.each(error.responseJSON.errors,function(key,value){  
            $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
         }); 
          
         jQuery('html, body').animate({
                scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
         }, 500); 
      }
  });
  return false;
}
</script>