@if(Auth::user())
@include('user.sidebar_popup')  
<span style="font-size:30px;cursor:pointer" class="open-nav" onclick="openNav()">&#9776;</span>
@else
@include('auth.login_popup')  
<span style="font-size:30px;cursor:pointer" class="open-nav" onclick="openLoginNav()">&#9776;</span>
@endif


@if(Auth::user() && Auth::user()->user_type != '0')
<div class="top-bar top-bar2">
    <div class="container">
        <h4>Öffnungszeiten <span>9-23 Uhr</span></h4>
        <ul>
            <li><span class="white-sec">WINIS gesamt <span class="yellow">200</span></span></li>
            <li><span class="white-sec">Gratis-Winis <span class="yellow">50</span></span></li>
            <li><span class="white-sec"> Kauf-Winis  <span class="yellow">150</span></span></li>
            <li><span class="white-sec"> Aktive Deals <span class="yellow">05</span></span></li>
            <li>
                <ul class="nav-pills">
                    <li class="dropdownmenu">
                        <a href="javascript::void(0)" class="dropdown-toggle selct-top-bar">api <b class="caret"></b></a>
                        <ul class="dropdown-menu" id="menu1">
                            <li> <a href="{{ URL::to('/') }}/MyAuctionAccount/">Kontoübersicht</option></a></li>
                            <li> <a href="{{ URL::to('/') }}/Token/"><strong>Token kaufen</strong></option></a></li>
                            <li> <a href="{{ URL::to('/') }}/MyToken/">Auflistung Token</option></a></li>
                            <li> <a href="{{ URL::to('/') }}/MyAuctions/">Meine Deals heute</option></a></li>
                            <li> <a href="{{ URL::to('/') }}/MyAuctionsOld/">Meine Deals zuletzt</option></a></li>
                            <li> <a href="{{ URL::to('/') }}/Settings/">Meine Einstellungen</option></a></li>
                            <li> <a href="{{ URL::to('/') }}/Information/">Fragen?</option></a></li>
                            <li class="divider"></li>
                            <li> <a href="{{ URL::to('/') }}"><strong>Zu den Deals</strong></option></a></li>
                            <li class="divider"></li>
                            <li><a href="#logout" class="logout-btn">Logout</option></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
@else
    <div class="top-bar">
        <div class="container">
            <h4>Öffnungszeiten <span>9-23 Uhr</span></h4>
        </div>
    </div>
@endif
<div class="header"><!--header start-->
    <div class="navbar-otr"><!--navbar-otr start-->
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="{{ env('DOMAIN_URL') }}">
                    <img src="{{ asset('images/Logo.png') }}" alt="company logo" title="company logo"/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto ">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('homepage') }}">Home<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#services">LAUFENDE DEALS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#portfolio">KOMMENDE DEALS </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#Testimonials">So Funktioniert es</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contactus">FAQ</a>
                        </li>
                    </ul>
                    @if (Auth::guest())
                        <a href="{{ route('login') }}" class="login">Login</a>
                    @else
                         <a href="{{ route('logout') }}" class="login" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
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