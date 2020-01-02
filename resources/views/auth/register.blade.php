@extends('layouts.app')
@section('title')
    {{ trans('label.register_now') }}
@endsection
@section('content')

    @php
        $token = @$_GET['token'];
    @endphp

    {{--    user nav left side--}}
    <span style="font-size:30px;cursor:pointer" class="open-nav" onclick="openLoginNav()"><img class="menu-bar" src="{{ asset('images/menu-bar.png') }}"></span>

    {{--    Login/Register--}}

    <section id="user--login-register">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header w-100">
                        <h3 class="yellow">{{ trans('label.register_now') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="container--login-register-inner">
                <div class="row">

                    {{--LOGIN--}}
                    <div class="col-12 col-lg-5">
                        <h4>{{trans('label.login_title')}}</h4>
                        <form method="POST" onsubmit="return LoginUserId()" id="login_form_id">
                            @csrf
                            <div class="mt-4">
                                <div class="form-group">
                                    <label for="usr_id" class="label-text">{{ trans('label.username-login-form') }} </label>
                                    <input type="text" class="form-control" required id="log_usr_id" name="email_id" value="{{ old('email') }}">
                                    <span class="error email_id" style="color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="pass_id" class="label-text">{{ trans('label.password') }} </label>
                                    <input type="password" class="form-control" required id="log_pass_id" name="password_id">
                                    <span class="error password_id" style="color:red"></span>
                                </div>

                                <div class="login-form--forgot-pass">
                                    <a href="{{ route('password.request') }}">{{ trans('label.forgot_password') }}</a>
                                </div>

                                <div class="form-group">
                                    <input type="submit" value="{{ trans('label.login') }}" class="btn btn-winimi-y">
                                </div>
                            </div>
                        </form>
                    </div>

                    {{--REGISTER--}}
                    <div class="col-12 col-lg-7">
                        <h4>{{ trans('label.registration_title') }} </h4>

                        <form class="form-horizontal" method="POST" action="{{ route('register') }}">

                            {{ csrf_field() }}
                            <input type="hidden" name="invite_token" value="{{ $token ? $token : ''}}">

                            {{--google reCAPTCHA--}}
                            {!! htmlScriptTagJsApi([
                                    'action' => 'homepage',
                                     'callback_then' => 'callbackThen',
                                      'callback_catch' => 'callbackCatch'
                             ]) !!}

                            <div class="mt-4">
                                @if ($errors->has('invite_token'))
                                    <span class="help-block"><strong>{{ $errors->first('invite_token') }}</strong></span>
                                @endif

                                {{--username--}}
                                <div class="form-group">
                                    <label for="username" class="label-text"> {{ trans('label.username') }} </label>
                                    <input type="text" name="username" class="form-control @if ($errors->has('username')) is-invalid @endif" id="username" value="{{ old('username') }}" required autofocus>
                                    @if ($errors->has('username'))
                                        <div class="invalid-feedback">{{$errors->first('username')}}</div>
                                    @endif
                                </div>

                                {{--DOB--}}
                                <div class="form-row">
                                    <div class="col"><label class="label-text" for="">{{trans('label.dob')}}</label><br></div>
                                </div>
                                {{--birthday - day--}}
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group">
                                            <select class="custom-select @if ($errors->has('dob')) is-invalid @endif" name="b-day" id="b-day" required>
                                                <option selected>Tag</option>
                                                @for ($day = 1; $day <= 31; $day++)

                                                    @if($day < 10)
                                                        <option value="{{'0' . $day}}">{{'0' . $day}}</option>
                                                    @else
                                                        <option value="{{$day}}">{{$day}}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                        </div>

                                    </div>

                                    {{--birthday - month--}}
                                    <div class="col">
                                        <div class="form-group">
                                            <select class="custom-select @if ($errors->has('dob')) is-invalid @endif" name="b-month" id="b-month">
                                                <option selected>Monat</option>
                                                @for ($month = 1; $month <= 12; $month++)
                                                    @if($month < 10)
                                                        <option value="{{'0' . $month}}">{{'0' . $month}}</option>
                                                    @else
                                                        <option value="{{$month}}">{{$month}}</option>
                                                    @endif
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    {{--birthday - year--}}
                                    <div class="col">
                                        <div class="form-group">
                                            <select class="custom-select @if ($errors->has('dob')) is-invalid @endif" name="b-year" id="b-year">
                                                <option selected>Jahr</option>
                                                @for ($year = (now()->year) - 16; $year >= 1950 ; $year--)
                                                    <option value="{{$year}}">{{$year}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    {{-- dob error--}}
                                    <div class="col-12">
                                        {{--                                        <div class="invalid-feedback" style="display: block">Lorem ipsum dolor sit amet, consectetur</div>--}}
                                        @if ($errors->has('dob'))
                                            <div class="invalid-feedback" style="display: block">{{ $errors->first('dob') }}</div>
                                        @endif
                                    </div>

                                </div>

                                {{--email--}}
                                <div class="form-group">
                                    <label for="email" class="label-text">{{ trans('label.email') }} </label>
                                    <input type="text" name="email" class="form-control @if ($errors->has('email')) is-invalid @endif" id="email" value="{{ old('email') }}" required>
                                    @if ($errors->has('email') && !old('is_frontend'))
                                        <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                    @endif
                                </div>

                                {{--password--}}
                                <div class="form-group">
                                    <label for="password" class="label-text">{{ trans('label.password') }} </label>
                                    <input type="password" name="password" class="form-control  @if ($errors->has('password')) is-invalid @endif" id="password" required>
                                    @if ($errors->has('password'))
                                        <div class="invalid-feedback">{{$errors->first('password')}}</div>
                                    @endif
                                </div>

                                {{--confirm password--}}
                                <div class="form-group">
                                    <label for="password_confirmation" class="label-text">{{ trans('label.confirm_password')}} </label>
                                    <input type="password" name="password_confirmation" class="form-control @if ($errors->has('password')) is-invalid @endif" id="password_confirmation" required>
                                </div>

                                {{--country--}}

                                <div class="form-group">
                                    <label for="country" class="label-text">Land</label>
                                    {{--todo:rewrite--}}
                                    <select class="custom-select @if ($errors->has('country')) is-invalid @endif" name="country" id="country" required>
                                        <option selected>Land</option>
                                        <option value="Deutschland">Deutschland</option>
                                        <option value="Österreich">Österreich</option>
                                    </select>
                                    @if ($errors->has('country'))
                                        <div class="invalid-feedback">{{$errors->first('country')}}</div>
                                    @endif
                                </div>

                                {{--policy--}}
                                <div class="form-group">
                                    <input type="checkbox" name="accept" value="1"> {{ trans('label.policy_checkbox')}}

                                    @if ($errors->has('accept'))
                                        <div class="invalid-feedback">{{$errors->first('accept')}}</div>
                                    @endif
                                </div>


                                {{--google recaptcha--}}
                                <div class="recaptcha">
                                    {!! htmlFormSnippet() !!}
                                    @if ($errors->has('g-recaptcha-response'))
                                        <div class="invalid-feedback" style="display: block">{{$errors->first('g-recaptcha-response')}}</div>
                                    @endif
                                </div>
                                <input type="submit" value="{{ trans('label.register') }}" class="btn btn-winimi-y">
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--    Login/Register end--}}

    @include('news_letter')

@endsection
@section('javascript')
    <script type="text/javascript">
        @if(old('is_frontend'))
        openLoginNav()
            @endif
        var date = new Date();
        var currentDay = date.getDate();
        $('.datePicker_dob').datepicker({
            autoclose: true,
            dateFormat: 'dd.mm.yy',
            maxDate: '-18y',
            minDate: '-100y',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0",
            onChangeMonthYear: function (year, month, inst) {
                $(this).val(currentDay + "." + month + "." + year);
            }
        });


        function LoginUserId() {
            $(".email_id").html('');
            $(".password_id").html('');
            $('.model_box_save').attr("disabled", "disabled");
            var token = $("input[name=_token]").val();
            var email = $("input[name=email_id]").val();
            var password = $("input[name=password_id]").val();
            var data = {
                email: email,
                password: password
            };

            if (email == '') {
                $(".email_id").html("{{ trans('message.email_validation_message') }}");
            }
            if (password == '') {
                $(".password_id").html("{{ trans('message.password_validation_message') }}");
            }

            if (email == '' || password == '') {
                return false;
            }
            // Ajax Post
            $.ajax({
                type: "post",
                url: "{{ route('login') }}",
                data: data,
                cache: false,
                beforeSend: function () {
                    $('.live-deals2').waitMe();
                },
                success: function (data) {


                    console.log('login data: ' + data)

                    if (data.auth) {
                        window.location.href = "/" + data.intended;
                    }
                    $('.model_box_save').removeAttr("disabled");
                },
                complete: function () {
                    $('.live-deals2').waitMe('hide');
                },
                error: function (error) {
                    $('.model_box_save').removeAttr("disabled");

                    if (error.responseJSON.email) {
                        $('input[name="email_id"]').parent().find('span.error').html(error.responseJSON.email).addClass('active').show();
                    }

                    $.each(error.responseJSON.errors, function (key, value) {
                        key = key + '_id';
                        $('input[name="' + key + '"]').parent().find('span.error').html(value).addClass('active').show();
                    });

                    jQuery('html, body').animate({
                        scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
                    }, 500);
                }
            });
            return false;
        }
    </script>
@endsection
