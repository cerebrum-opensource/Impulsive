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
<style>
    .alert-success{
            bottom: inherit !important;
    }
</style>
<body>
    <div id="wrapper">
@if(Request::route()->getName() == 'verification.verify')
    <div class="banner">
        @if(session()->has('message.level'))
                <div class="alert alert-{{ session('message.level') }} alert-dismissible"> 
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! session('message.content') !!}
                </div>
            @endif
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>
                        Oops!</h1>
                   <h2 style="text-align: center;">
                         {{ trans('label.verification_link_expired') }}</h2>
                    <div class="error-details">
                        <center>
                       <a href="javascript:resendVerificationMail('{{ encrypt_decrypt('encrypt', Request::route('id')) }}')"><span class="label label-sm label-danger"> {{ trans('label.send_verify_link') }} </span></a>
                        </center>
                        
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
@else
    <div class="banner">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>
                        Oops!</h1>
                    <h2>
                        403 ACCESS DENIED</h2>
                    <div class="error-details">
                        ACCESS DENIED
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
@endif
</div>
</body>
</html>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/waitMe.js') }}"></script>
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
function resendVerificationMail(id){
      
    $.ajax({
        url:"{{ route('resend-verification-email') }}",
        data:{id:id},
        success:function(data){
            window.location.reload();
        }
    });
        
}

</script>

