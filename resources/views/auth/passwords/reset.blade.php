@extends('layouts.app')
@section('title')
@if(app('request')->input('type') && app('request')->input('type') == 'set')
  {{ trans('label.set_password') }}
@else
{{ trans('label.forget_password') }}
@endif
@endsection
@section('content')

@include('auth.login_popup')  

<span style="font-size:30px;cursor:pointer" class="open-nav" onclick="openLoginNav()"><img class="menu-bar" src="{{ asset('images/menu-bar.png') }}"></span>
<div>
<div class="live-deals live-deals2">
   <!--live-deals start--> 
   <div class="container">
      <h3 class="sec-heading"><span>@if(app('request')->input('type') && app('request')->input('type') == 'set')
         {{ trans('label.set_password') }}
         @else
          {{ trans('label.forget_password') }}
         @endif</span>
      </h3>
      <div class="free-otr2">
         <div class="free-bar free-bar3">
            <h4>{{ trans('label.forget_password_message') }}</h4>
            <form method="POST" action="{{ route('password.update') }}">
               {{ csrf_field() }}
               <input type="hidden" name="token" value="{{ $token }}">
               <div class="mt-4">
                  <div class="form-group">
                     <label for="first_name" class="label-text">{{ trans('label.email_placeholder') }}</label>            
                     <input id="email" placeholder="{{ trans('label.email_placeholder') }}"  type="text" class="form-control form-control2 {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" autofocus>
                     @if ($errors->has('email'))
                     <span class="help-block">
                     <strong>{{ $errors->first('email') }}</strong>
                     </span>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="last_name" class="label-text">{{ trans('label.password') }}</label>
                     <input id="password" placeholder="{{ trans('label.password') }}" type="password" class="form-control form-control2 {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >
                     @if ($errors->has('password'))
                     <span class="help-block">
                     <strong>{{ $errors->first('password') }}</strong>
                     </span>
                     @endif
                  </div>
                  <div class="form-group">
                     <label for="password_confirmation" class="label-text">{{ trans('label.confirm_password') }} </label>
                     <input type="password" name="password_confirmation" class="form-control form-control2 {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" id="usr">
                     @if ($errors->has('password_confirmation'))
                     <span class="invalid-feedback" role="alert">
                     <strong>{{ $errors->first('password_confirmation') }}</strong>
                     </span>
                     @endif
                  </div>
                  <input type="submit" value="{{ trans('label.update') }} " class="btn-form btn-form2 mt-3">
            </form>
            </div>
         </div>

      </div>
   </div>
</div>
</div>

@include('news_letter')
@endsection