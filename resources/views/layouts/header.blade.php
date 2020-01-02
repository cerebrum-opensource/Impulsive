@if(Auth::user())
    @include('user.sidebar_popup')
    <!--<span onclick="openNav()">
<img src="{{ asset('images/menu.png') }}" class="navigation-img" alt="company logo" title="company logo"/>
</span>-->


    <span style="font-size:30px;cursor:pointer" class="open-nav" onclick="openNav()"><img class="menu-bar" src="{{ asset('images/menu-bar.png') }}"></span>
@else
    @include('auth.login_popup')
    <!--<span onclick="openNav()">
<span onclick="openLoginNav()">
<img src="{{ asset('images/menu.png') }}" class="navigation-img" alt="company logo" title="company logo"/>
</span> -->
    <span style="font-size:30px;cursor:pointer" class="open-nav" onclick="openLoginNav()"><img class="menu-bar" src="{{ asset('images/menu-bar.png') }}"></span>
@endif

<style type="text/css">
    .navbar-otr ul {
        padding: 0px 20px 0 0;
    }

</style>

@if(Auth::user() && Auth::user()->user_type != '0')


    <div class="top-bar top-bar2">
        @include('layouts.top_header')
    </div>
@else
    <div class="top-bar">
        <div class="container">
            <h4>Öffnungszeiten <span>9:00-23:00 Uhr</span></h4>
        </div>
    </div>
@endif
<div class="header"><!--header start-->
    <div class="navbar-otr"><!--navbar-otr start-->
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('images/Logo.png') }}" alt="Winimi - Fairplay-Auktionsplattform" title="Winimi - Fairplay-Auktionsplattform"/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto ">
                        <li class="nav-item {{ (request()->is('/')) ? 'active' : '' }} ">
                            <a class="nav-link" href="{{ route('homepage') }}">{{ trans('label.home') }}<span class="sr-only"></span></a>
                        </li>
                        <li class="nav-item {{ (request()->is('auctions') || request()->is('auctions/*')) ? 'active' : '' }} ">
                            <a class="nav-link" href="{{ route('frontend_live_auctions') }}"> {{ trans('label.running_deals') }}</a>
                        </li>
                        <li class="nav-item {{ (request()->is('coming_auctions')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('frontend_coming_auctions') }}">{{ trans('label.coming_deals') }} </a>
                        </li>
                        <li class="nav-item {{ (request()->is('auction_overview')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('auction_overview') }}">{{ trans('label.auction_overview') }} </a>
                        </li>
                        <li class="nav-item {{ (request()->is('pages/how_it_works')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('pages_route',['how_it_works']) }}">{{ trans('label.how_its_work') }} </a>
                        </li>
                        <li class="nav-item {{ (request()->is('pages/faq')) ? 'active' : '' }} ">
                            <a class="nav-link" href="{{ route('page_faq') }}">{{ trans('label.faq') }}</a>
                        </li>
                    </ul>
                    @if (Auth::guest())
                        {{--                        <a href="{{ route('login') }}" class="login">{{ trans('label.login') }}</a>--}}

                        <a href="{{ route('login') }}" class="user-login-link">{{ trans('label.login') }} <i class="fas fa-user"></i></a>
                    @else
                        <a href="{{ route('logout') }}" class="login" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ trans('label.logout') }}</a>
                @endif
                <!-- <input type="button" value="" class="login Login2"> -->
                    <form id="logout-form" action="{{URL::to('/logout')}}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>
        </div>
    </div><!--navbar-otr end-->
</div><!--header end-->
