<!-- Footer Ends -->

<!-- Jquery Starts -->

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/backend.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('js/admin/wow.min.js') }}"></script>
<script src="{{ asset('js/admin/jquery-price-slider.js') }}"></script>
<script src="{{ asset('js/admin/jquery.meanmenu.js') }}"></script>
<script src="{{ asset('js/admin/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/admin/jquery.sticky.js') }}"></script>
<script src="{{ asset('js/admin/jquery.scrollUp.min.js') }}"></script>
<script src="{{ asset('js/admin/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ asset('js/admin/scrollbar/mCustomScrollbar-active.js') }}"></script>
<script src="{{ asset('js/admin/metisMenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('js/admin/metisMenu/metisMenu-active.js') }}"></script>
<script src="{{ asset('js/admin/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('js/admin/sparkline/jquery.charts-sparkline.js') }}"></script>
<script src="{{ asset('js/admin/calendar/moment.min.js') }}"></script>
<script src="{{ asset('js/admin/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/admin/calendar/fullcalendar-active.js') }}"></script>
<script src="{{ asset('js/admin/tab.js') }}"></script>
<script src="{{ asset('js/admin/plugins.js') }}"></script>
<script src="{{ asset('js/admin/main.js') }}"></script>
<script src="{{ asset('js/waitMe.js') }}"></script>
<script src="{{ asset('js/bootbox.js') }}"></script>
<script src="{{ asset('js/bootbox.locales.js') }}"></script>
<script src="{{ asset('js/jstz.min.js') }}" type="text/javascript"></script> 
@yield('javascript')

<script type="text/javascript">
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


      function disCardForm(url){
        window.location.href = url;
      }


</script>