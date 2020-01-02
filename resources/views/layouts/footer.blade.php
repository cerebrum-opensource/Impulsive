@inject('staticService','App\Services\StaticContent')
@php
    $footer = $staticService->getFooter();

    $image = asset('images/footer-logo.png');
    if(!empty($footer['footer_sidebar'])){
        if($footer['footer_sidebar']->logo)
        $image = asset('/images/admin/footer/').'/'.$footer['footer_sidebar']->id.'/'.$footer['footer_sidebar']->logo;
        $timing = $footer['footer_sidebar']->timing;
    }
    //if($footer['footer_sidebar']->logo)
    //    $image = asset('/images/admin/footer/').'/'.$footer['footer_sidebar']->id.'/'.$footer['footer_sidebar']->logo;


    

   // $time = explode(",",$timing);
    //echo count($time);
@endphp

@if($footer['footer_sidebar'])
    <footer class="section footer-classic context-dark bg-image">
        <div class="container">
            <div class="row row-30">
                <div class="col-md-4">
                    <div class="pr-xl-4">
                        <a class="brand" href="/">
                            <img class="brand-logo-light" src="{{ $image }}" alt="-Winimi - Fairplay-Auktionsplattform" title="Winimi - Fairplay-Auktionsplattform">
                        </a>
                        <p>{{ @$footer['footer_sidebar']->heading }}</p>
                        <!-- Rights-->

                    </div>
                </div>
                <div class="col-md-4">
                    <h5 class="sub-heading">{{ trans('label.store_information') }}</h5>
                <!--<dl class="contact-list">
            		<dt> <i class="fa fa-map-marker" aria-hidden="true"></i>{{ @$footer['footer_sidebar']->address }} </dt>
		        </dl> -->
                    <dl class="contact-list">
                        <dt><i class="fa fa-envelope" aria-hidden="true"></i>{{ @$footer['footer_sidebar']->email }} </dt>
                    </dl>
                    @if($timing)
                        <dl class="contact-list">
                            <dt><i class="fa fa-clock-o" aria-hidden="true"></i>
                                <span class="contact-list-img">
 <span><span class="yelow">{{ $timing }}</span></span>

							@php
                               /* if(count($time)){
                                    foreach($time as $timeFrame){
                                            $timeData = explode(":",$timeFrame);
                                        echo '<span><span class="yelow">'.@$timeData[0].'</span> '.@$timeData[1].'</span>';
                                    }
                                }*/
                            @endphp

                            <!--<span><span class="yelow">Montag - Freitag:</span> 08.00-22.00 Uhr</span>
						<span><span class="yelow">Montag - Freitag:</span> 08.00-22.00 Uhr</span>
						<span><span class="yelow">Montag - Freitag:</span> 08.00-22.00 Uhr</span>-->
						</span>
                            </dt>
                        </dl>
                    @endif
                </div>
                <div class="col-md-2">
                    <h5>{{ trans('label.links') }}</h5>
                    <ul class="nav-list">

                        @if($footer['footer_sidebar_link'] && count($footer['footer_sidebar_link']))
                            @foreach($footer['footer_sidebar_link'] as $key => $footerLink)
                                <li><a href="{{ $footerLink->link_url }}">{{ $footerLink->name }}</a></li>
                                @php
                                    $keyy= $key+1;
                                    if($keyy % 5 == 0){
                                        echo '</ul> </div> <div class="col-md-2 "> <ul class="nav-list footer-layout-div">';
                                    }
                                @endphp
                            @endforeach

                        @endif
                    </ul>
                </div>

            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row">

                    {{-- footer social icons --}}
                    <div class="col-12 col-md-2 footer--mb30">
                        <div class="social-icons">
                            <h3>{{ trans('label.follow_us') }}</h3>
                            <a href="{{ $footer['footer_sidebar']->insta_link ? $footer['footer_sidebar']->insta_link : '#' }}" target="_blank"><img src="{{ asset('images/instagram-icon.png') }}" alt="" title=""/></a>
                            <a href="{{ $footer['footer_sidebar']->fb_link ? $footer['footer_sidebar']->fb_link : '#' }}" target="_blank"><img src="{{ asset('images/facebook-icon.png') }} " alt="" title=""/></a>
                        </div>
                    </div>

                    {{-- footer payment icons --}}
                    <div class="col-12 col-md-6 footer--mb30">
                        <div class="payment-icons">
                            <h3>{{ trans('label.payment_methods') }}</h3>


                                {{--                                <div class="col-12">--}}
                                <div class="footer--payment-icon"><img src="{{ asset('images/icons/payment/paypal.png') }}" alt="winimi paypal"></div>
                                <div class="footer--payment-icon"><img src="{{ asset('images/icons/payment/mastercard.png') }}" alt="winimi paypal"></div>
                                <div class="footer--payment-icon"><img src="{{ asset('images/icons/payment/sepa.png') }}" alt="winimi paypal"></div>
                                <div class="footer--payment-icon"><img src="{{ asset('images/icons/payment/sofort.png') }}" alt="winimi paypal"></div>
                                <div class="footer--payment-icon"><img src="{{ asset('images/icons/payment/visa.png') }}" alt="winimi paypal"></div>
                                {{--                                </div>--}}


                        </div>
                    </div>

                    {{-- footer ekomi widget --}}
                    <div class="col-12 col-md-4 footer--mb30">
                        <h3>{{ trans('label.certificates') }}</h3>
                        <div id="widget-container" class="ekomi-widget-container ekomi-widget-sf1308775d3f04e084dd2"></div>
                        <script type="text/javascript">
                            (function (w) {
                                w['_ekomiWidgetsServerUrl'] = 'https://widgets.ekomi.com';
                                w['_customerId'] = 130877;
                                w['_ekomiDraftMode'] = true;
                                w['_language'] = 'de';

                                if (typeof (w['_ekomiWidgetTokens']) !== 'undefined') {
                                    w['_ekomiWidgetTokens'][w['_ekomiWidgetTokens'].length] = 'sf1308775d3f04e084dd2';
                                } else {
                                    w['_ekomiWidgetTokens'] = new Array('sf1308775d3f04e084dd2');
                                }

                                if (typeof (ekomiWidgetJs) == 'undefined') {
                                    ekomiWidgetJs = true;

                                    var scr = document.createElement('script');
                                    scr.src = 'https://sw-assets.ekomiapps.de/static_resources/widget.js';
                                    var head = document.getElementsByTagName('head')[0];
                                    head.appendChild(scr);
                                }
                            })(window);
                        </script>
                    </div>

                </div>
            </div>
        </div>

        {{-- end footer bottom--}}

        <div class="footer"><!--footer start-->
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="copyright">
                            <span>© {{ now()->year }} Impulsive Projects</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </footer>


    {{-- scroll to top button--}}
    <div class="scroll-to-top--button">
        <a href="#"><i class="fas fa-arrow-circle-up"></i></a>
    </div>


@endif
@section('javascript')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/frontend.js') }}"></script>
    <script src="{{ asset('js/backend.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/wow.js') }}"></script>
    <script src="{{ asset('js/waitMe.js') }}"></script>
    <script src="{{ asset('js/bootbox.js') }}"></script>
    <script src="{{ asset('js/jstz.min.js') }}" type="text/javascript"></script>

    {{--  changed IMP --}}
    <script src="{{ asset('js/frontend/scroll-to-top.js')}}"></script>

    <script>
        // new WOW().init();

        $(document).ready(function () {
            // $('body').append('<div id="toTop" class="btn btn-info"><span class="glyphicon glyphicon-chevron-up"></span> Back to Top</div>');
            // $(window).scroll(function () {
            //     if ($(this).scrollTop() != 0) {
            //         $('#toTop').fadeIn();
            //     } else {
            //         $('#toTop').fadeOut();
            //     }
            // });
            // $('#toTop').click(function () {
            //     $("html, body").animate({scrollTop: 0}, 600);
            //     return false;
            // });

            $('.count').each(function () {
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 4000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });

        });


        (function ($) {
            $.fn.countTo = function (options) {
                options = options || {};

                return $(this).each(function () {
                    // set options for current element
                    var settings = $.extend({}, $.fn.countTo.defaults, {
                        from: $(this).data('from'),
                        to: $(this).data('to'),
                        speed: $(this).data('speed'),
                        refreshInterval: $(this).data('refresh-interval'),
                        decimals: $(this).data('decimals')
                    }, options);

                    // how many times to update the value, and how much to increment the value on each update
                    var loops = Math.ceil(settings.speed / settings.refreshInterval),
                        increment = (settings.to - settings.from) / loops;

                    // references & variables that will change with each update
                    var self = this,
                        $self = $(this),
                        loopCount = 0,
                        value = settings.from,
                        data = $self.data('countTo') || {};

                    $self.data('countTo', data);

                    // if an existing interval can be found, clear it first
                    if (data.interval) {
                        clearInterval(data.interval);
                    }
                    data.interval = setInterval(updateTimer, settings.refreshInterval);

                    // initialize the element with the starting value
                    render(value);

                    function updateTimer() {
                        value += increment;
                        loopCount++;

                        render(value);

                        if (typeof (settings.onUpdate) == 'function') {
                            settings.onUpdate.call(self, value);
                        }

                        if (loopCount >= loops) {
                            // remove the interval
                            $self.removeData('countTo');
                            clearInterval(data.interval);
                            value = settings.to;

                            if (typeof (settings.onComplete) == 'function') {
                                settings.onComplete.call(self, value);
                            }
                        }
                    }

                    function render(value) {
                        var formattedValue = settings.formatter.call(self, value, settings);
                        $self.html(formattedValue);
                    }
                });
            };

            $.fn.countTo.defaults = {
                from: 0,               // the number the element should start at
                to: 0,                 // the number the element should end at
                speed: 1000,           // how long it should take to count between the target numbers
                refreshInterval: 100,  // how often the element should be updated
                decimals: 0,           // the number of decimal places to show
                formatter: formatter,  // handler for formatting the value before rendering
                onUpdate: null,        // callback method for every time the element is updated
                onComplete: null       // callback method for when the element finishes updating
            };

            function formatter(value, settings) {
                return value.toFixed(settings.decimals);
            }
        }(jQuery));

        jQuery(function ($) {
            $('.count-number').data('countToOptions', {
                formatter: function (value, options) {
                    return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
                }
            });

            $('.timer').each(count);

            function count(options) {
                var $this = $(this);
                options = $.extend({}, options || {}, $this.data('countToOptions') || {});
                $this.countTo(options);
            }
        });


    </script>
    <script type="text/javascript">
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        function openLoginNav() {
            document.getElementsByClassName("sidenavlogin")[0].style.width = "471px";
        }

        function closeLoginNav() {
            document.getElementsByClassName("sidenavlogin")[0].style.width = "0";
        }


        $.ajaxSetup({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('body').waitMe();
            },
            complete: function () {
                $('body').waitMe('hide');
            },
            error: function (error) {
                //console.log(error);
                if (error.responseJSON) {
                    $.each(error.responseJSON.errors, function (key, value) {
                        $('input[name="' + key + '"]').parent().find('span.error').html(value).addClass('active').show();
                        $('select[name="' + key + '"]').parent().find('span.error').html(value).addClass('active').show();
                    });
                    jQuery('html, body').animate({
                        scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
                    }, 500);
                }


            }
        });


        // hide all validation error after 5 sec
        function fadeOutAlertMessages() {
            setTimeout(function () {
                $(document).find('.alert-success').slideUp(500);
                $(document).find(".alert-error").slideUp(500);
                $(document).find(".alert-danger").slideUp(500);
                $(document).find(".alert-warning").slideUp(500);
            }, 3000);
        }

        jQuery(document).ready(function () {
            fadeOutAlertMessages();
        });


        $.each(document.cookie.split(/; */), function () {

            document.cookie = 'name=client_timezone; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            var splitCookie = this.split('=');
            if (this.indexOf('client_timezone') == 0) {
                document.cookie = splitCookie[0] + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            }

        });
        var tz = jstz.determine(); // Determines the time zone of the browser client
        var tz_cookie = tz.name();
        document.cookie = 'client_timezone=' + tz_cookie;
    </script>
    @yield('javascript')

