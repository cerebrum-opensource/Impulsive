<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="siteaddress" content="{{ env('DOMAIN_URL') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Impulsive</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900">
    <link rel="stylesheet" href="{{ asset('css/admin/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/nalika-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/owl.transitions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/morrisjs/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/scrollbar/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/metisMenu/metisMenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/metisMenu/metisMenu-vertical.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/calendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/calendar/fullcalendar.print.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/waitMe.css') }}">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/admin/modernizr-2.8.3.min.js') }}"></script>
    @yield('css')
</head>

<body>
    <div id="wrapper">
        <input type="hidden" class="baseurl" value="{{ asset('/') }}"/>
        @include('layouts.adminheader')

        @yield('content')
    </div>

<div class="footer-copyright-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer-copy-right">
                        <p>Copyright © 2019 <a href="javascript::void(0);">Impulsive</a> All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<!-- Footer start -->
@include('layouts.adminfooter')
<!-- DataTables -->

<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script> 

@yield('common_script')

@stack('scripts')
</html>