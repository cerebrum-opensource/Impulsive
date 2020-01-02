<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="siteaddress" content="{{ env('DOMAIN_URL') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Impulsive</title>

    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"/>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <link rel="icon" type="image/png" href="{{ e(asset('images/favicon.ico')) }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/demo-page.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/hover.css') }}"/>
     <link rel="stylesheet" href="{{ asset('css/waitMe.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    @yield('css')
</head>

<body>
    <div id="wrapper">
        <input type="hidden" class="baseurl" value="{{ asset('/') }}"/>
        @include('layouts.header')

        @yield('content')
    </div>

<!-- Footer start -->
@include('layouts.footer')
<!-- Footer Ends -->

<!-- Jquery Starts -->



</body>
@yield('common_script')

@stack('scripts')
</html>
