@extends('layouts.app')

@section('title')
    FAIRPLAY-AUKTIONEN
@endsection
@section('content')
    <section id="after-register">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="imp-welcome">{{trans('label.after_register_text')}}</p>
                </div>
{{--                <div class="col-12">--}}
{{--                    <a class="imp-btn" href="{{route('login')}}" role="button">{{trans('label.back_to_login_button')}}</a>--}}
{{--                </div>--}}
            </div>
        </div>
    </section>
@endsection
