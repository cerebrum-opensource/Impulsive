<footer class="section footer-classic context-dark bg-image">
    <div class="container">
      	<div class="row row-30">
        	<div class="col-md-4">
          		<div class="pr-xl-4">
          			<a class="brand" href="index.html">
          				<img class="brand-logo-light" src="images/footer-logo.png" alt="-footer-logo" title="footer-logo">
          			</a>
            		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            		<!-- Rights-->
          			<ul class="list-unstyled list-inline social text-center">
						<li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-facebook"></i></a></li>
						<li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-instagram"></i></a></li>
					</ul>
        		</div>
        	</div>
        	<div class="col-md-4">
          		<h5 class="sub-heading">STORE INFORMATION</h5>
          		<dl class="contact-list">
            		<dt> <i class="fa fa-map-marker" aria-hidden="true"></i>Musterstraße, 74074 Heilbronn</dt>
		        </dl>
           		<dl class="contact-list">
            		<dt> <i class="fa fa-envelope" aria-hidden="true"></i>info@winimi.de</dt>
	         	</dl>
          		<dl class="contact-list">
            		<dt> <i class="fa fa-clock-o" aria-hidden="true"></i>
						<span class="contact-list-img">
						<span><span class="yelow">Montag - Freitag:</span> 08.00-22.00 Uhr</span>
						<span><span class="yelow">Montag - Freitag:</span> 08.00-22.00 Uhr</span>
						<span><span class="yelow">Montag - Freitag:</span> 08.00-22.00 Uhr</span>
						</span>
					</dt>
          		</dl>
        	</div>
        	<div class="col-md-2">
          		<h5>Links</h5>
		        <ul class="nav-list">
		            <li><a href="#">Mein Login</a></li>
		            <li><a href="#">Kontakt</a></li>
		            <li><a href="#">Über uns</a></li>
		            <li><a href="#">Neue Deals</a></li>
		            <li><a href="#">Kommende Deals</a></li>
		        </ul>
        	</div>
		 	<div class="col-md-2 ">
	          	<h5></h5>
		        <ul class="nav-list">
		            <li><a href="#">Datenschutz</a></li>
		            <li><a href="#">AGB’s</a></li>
		            <li><a href="#">Versand und Zahlungen</a></li>
		            <li><a href="#">Winis kaufens</a></li>
		      	</ul>
        	</div>
      	</div>
    </div>
   	<div class="footer"><!--footer start-->
   		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="copyright">
			 			<span>© 2019 Impulsive</span>
			 		</div>
			 	</div>
			</div>
		</div>
	</div>
</footer>
@section('javascript')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/frontend.js') }}"></script>
<script src="{{ asset('js/backend.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('js/wow.js') }}"></script>
<script src="{{ asset('js/waitMe.js') }}"></script>
<script src="{{ asset('js/bootbox.js') }}"></script>
<script src="{{ asset('js/jstz.min.js') }}" type="text/javascript"></script> 
<script>
new WOW().init();

$(function() {
    $(window).on("scroll", function() {
        if($(window).scrollTop() > 50) {
            $(".navbar-otr").addClass("active");
        } else {
           $(".navbar-otr").removeClass("active");
        }
    });
});

$(document).ready(function(){
    $('body').append('<div id="toTop" class="btn btn-info"><span class="glyphicon glyphicon-chevron-up"></span> Back to Top</div>');
	$(window).scroll(function () {
		if ($(this).scrollTop() != 0) {
			$('#toTop').fadeIn();
		} else {
			$('#toTop').fadeOut();
		}
	}); 
    $('#toTop').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

    $('.count').each(function () {
	    $(this).prop('Counter',0).animate({
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
				from:            $(this).data('from'),
				to:              $(this).data('to'),
				speed:           $(this).data('speed'),
				refreshInterval: $(this).data('refresh-interval'),
				decimals:        $(this).data('decimals')
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
				
				if (typeof(settings.onUpdate) == 'function') {
					settings.onUpdate.call(self, value);
				}
				
				if (loopCount >= loops) {
					// remove the interval
					$self.removeData('countTo');
					clearInterval(data.interval);
					value = settings.to;
					
					if (typeof(settings.onComplete) == 'function') {
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

!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^https:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-11991680-4', 'ianlunn.github.io');
ga('send', 'pageview');
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
        type:"POST",
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        beforeSend:function(){
            $('body').waitMe();
        },
        complete:function(){
            $('body').waitMe('hide');
        },
        error:function(error){
            $.each(error.responseJSON.errors,function(key,value){
                $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
                $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
            });

            jQuery('html, body').animate({
                scrollTop: jQuery(document).find('.error.active:first').parent().offset().top
            }, 500);

        }
    });


        // hide all validation error after 5 sec
    function fadeOutAlertMessages(){
      setTimeout(function(){
           $(document).find('.alert-success').slideUp(500);
           $(document).find(".alert-error").slideUp(500);
           $(document).find(".alert-danger").slideUp(500);
       },3000);
    }

    jQuery(document).ready(function(){
      console.log(1);
      fadeOutAlertMessages();
    });  

 $.each(document.cookie.split(/; */), function()  {
          
      document.cookie = 'name=client_timezone; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        var splitCookie = this.split('=');
        if (this.indexOf('client_timezone') == 0) {
          document.cookie = splitCookie[0] + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }

      });     
      var tz = jstz.determine(); // Determines the time zone of the browser client
      var tz_cookie =  tz.name();
      document.cookie = 'client_timezone='+tz_cookie;
</script>
@yield('javascript')

