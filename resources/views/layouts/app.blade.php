<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="siteaddress" content="{{ env('DOMAIN_URL') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WINIMI.de | @yield('title')</title>


    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}"/>
    <link rel="icon" type="image/png" href="{{ e(asset('images/favicon.ico')) }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}"/>
    {{--    <link rel="stylesheet" href="{{ asset('css/demo-page.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('css/hover.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/waitMe.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/imp.css?v=3') }}"/>
    <link rel="stylesheet" href="{{asset('css/fontawesome.5.11.2.all.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/imp-style-sass.css')}}">

    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-149788077-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-149788077-1');
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '688395655000623');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=688395655000623&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
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
