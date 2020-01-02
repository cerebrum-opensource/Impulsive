@extends('layouts.app')
@section('title')
    @if(app('request')->input('type') && app('request')->input('type') == 'set')
        Set Password
    @else
        Reset Password
    @endif
@endsection
@section('content')

@include('auth.login_popup')

<span style="font-size:30px;cursor:pointer" class="open-nav" onclick="openLoginNav()"><img class="menu-bar" src="{{ asset('images/menu-bar.png') }}"></span>
<div class="live-deals live-deals2"><!--live-deals start--> 
<div class="container">
<h3 class="sec-heading"><span> @if(app('request')->input('type') && app('request')->input('type') == 'set')
                        Set Password
                    @else
                        Reset Password
                    @endif</span></h3>
<div class="free-otr">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
<div class="row">
<div class="col-md-7">
    <div class="free-bar free-bar2">

        <form method="POST" action="{{ route('password.email') }}">
        
        {{ csrf_field() }}
            
        <div class="mt-4">
            <div class="form-group">
              <label for="first_name" class="label-text">{{ trans('label.email') }}</label>            
              <input id="email"placeholder="{{ trans('label.email_placeholder') }}"  type="text" class="form-control form-control2 {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif

            </div>
            <input type="submit" value="{{ trans('label.sendEmail') }}" class="btn-form btn-form2 mt-3">
        </div>
         </form>
    </div>

</div>


</div>
</div>
</div><!--live-deals end--> 
</div>


@endsection
