@extends('layouts.app')
@section('title')
 {{ trans('label.auctions') }}
@endsection
@section('css')
<style>  
   .is_queue{
   background: none;
   }
   .align_right{
   float:right;
   margin: 10px;
   }
   .text1a.text5a {
   background: #fece0a;
   width: 162px;
   padding: 0;
   text-align: center; 
   }
</style>
@endsection
@section('content')
<div class="product-otr">

    @if(Session::get('success'))
      <div class="alert alert-success alert-dismissible"> 
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {!! Session::get('success') !!}
      </div>
    @endif 
    @if(Session::get('error'))
     <div class="alert alert-danger alert-dismissible"> 
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {!! Session::get('error') !!}
      </div>
    @endif 

    @php
      Session::forget('success');
      Session::forget('error');

    @endphp
   <div class="live-deals" id="win-deal-pag">
      <div class="container">
         <h3 class="sec-heading"><span>{{ trans('label.win_list') }}</span> </h3>
         <ul class="planned-deals-ul" id="win-deal-ul">
            @include('user.auctions.win_auctions')
         </ul>
      </div>
   </div>
   <div class="live-deals kommende" id="lose-deal-pag">
      <div class="container">
         <h3 class="sec-heading"><span>{{ trans('label.loss_list') }}</span> </h3>
         <ul class="expired-deals-ul" id="lose-deal-ul">
            @include('user.auctions.loss_auctions')
         </ul>
      </div>
   </div>
   <!--live-deals end-->   
   @if (!Auth::guest())
   <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::id()) }}" data-value="{{Auth::id()}}"> 
   @else
   <input type="hidden" name="user_id" value="0">
   @endif
   <!--live-deals end-->    
</div>

<!--live-deals end-->   
@include('news_letter')
@include('support_widget')
<!--  Pass the constant as per page type-->
@include('seo_page',['page_type' =>WIN_LIST])


<!-- Modal -->
<div id="paymentModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">{{ trans('label.payment') }}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
          <div id="ppplus">

          </div>
          <div id="formbody">

            <form action="javascript:;" method="post" id="choose_payment_form">
               <h4>{{ trans('label.set_payment_method') }}</h4>
               <div class="paypal">
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="payment_method" checked value="paypal">
                     <div class="sec1">
                     <img src="{{ asset('images/paypal.png') }}" alt="paypal" title="paypal">
                     </label>
                     <p>{{ trans('label.payment_with_paypal') }} </p>
                     </div>
                       <div class="sec1">
                        <img src="{{ asset('images/bank-logo.png') }}" alt="{{ trans('label.payment_with_debit_card') }}" title="{{ trans('label.payment_with_debit_card') }}">
                       
                        <p>{{ trans('label.payment_with_debit_card') }} </p>
                        </div>
                         <div class="sec1">
                        <img src="{{ asset('images/cc-logo.png') }}" alt="{{ trans('label.payment_with_credit_card') }}" title="{{ trans('label.payment_with_credit_card') }}">
                        
                        <p>{{ trans('label.payment_with_credit_card') }} </p>
                        </div>
                  </div>
               </div>
               
                <div class="paypal">
                 <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="payment_method" value="softor" >
                    <img src="{{ asset('images/softor.png') }}" alt="softor" title="softor" style="width: 100px">
                    </label>
                    <p>{{ trans('label.payment_with_softor') }} </p>
                 </div>
                </div>

               <span class="error" id="payment_method" style="color:red"></span>
               <input type="hidden" name="amount" value="">
               <input type="hidden" name="data_type" value="">
               <input type="hidden" name="win_id" value="">
               <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::user()->id) }}">
               <span class="error" id="errorId" style="color:red"></span>
               <div class="">
                  <div class="buttonsbottom">
                     <button id="ppplus-imp" type="button" class="btn-form btn-form2 mt-3 model_box_save">{{trans('label.pay')}}</button>
                  </div>
               </div>
            </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
@endsection
@section('javascript')

<script src="https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js" type="text/javascript"></script>

<script type="text/javascript">
   //enable allergies listing
    $('body').on('click', '#win-deal-pag .pagination a', function(e) {
     e.preventDefault();
     var url = $(this).attr('href');  
     $.ajax({
         url:url,
         type:"GET",
         data:{type:'wins'},
         dataType: "json",
         success:function(data){
           console.log(data);
           $('#win-deal-ul').html(data.html);
         },
         error:function(data){
           alert('error');
         }
       });
    });  
   
    $('body').on('click', '#lose-deal-pag .pagination a', function(e) {
     e.preventDefault();
     var url = $(this).attr('href');  
     $.ajax({
         url:url,
         type:"GET",
         data:{type:'lose'},
         dataType: "json",
         success:function(data){
           console.log(data);
           $('#lose-deal-ul').html(data.html);
         },
         error:function(data){
           alert('error');
         }
       });
    }); 
   
    $(document).ready(function () {
   
    // Attach Button click event listener 
       $(".buy_now_btn").click(function(){
   
            $('input[name=win_id]').val($(this).data('id'));
            $('input[name=amount]').val($(this).data('amount'));
            $('input[name=data_type]').val($(this).data('type'));
            // show Modal
            $('#paymentModal').modal('show');
       });

       $('#ppplus-imp').on('click', function () {
           savePayment('#choose_payment_form');
       });


   });
   
   
    function savePayment(formId,tab){
       $('.model_box_save').attr("disabled", "disabled");
       $('span.error').hide().removeClass('active');
       var formData = new FormData($(formId)[0]);
   
       $.ajax({
         url:"{{ route('product-payment') }}",
         data:formData,
         type:"POST",
         processData: false,
         contentType: false,
         dataType: "json",
         success:function(response){
          
           console.log(response);
           $('.model_box_save').removeAttr("disabled");
           $('input,textarea,select').removeClass('changed-input');
           
           if(response.type && response.type=='klarna'){
                $('body').html(response.redirct_url);
             }
             else if(response.type && response.type=='paypal'){
                   // console.log('I am here');
/*                    $('#ppplus').show();
                    $('#formbody').hide();
                    var ppp = PAYPAL.apps.PPP({
                      "approvalUrl": response.redirct_url,
                      "placeholder": "ppplus",
                      "mode": "live",
                      "country": "DE"
                      });

*/
                 // $('body').html(response.redirct_url);

 window.location.href = response.redirct_url;

            }
           else {
             if(response.redirct_url){
              window.location.href = response.redirct_url;
             }
           }
           
         },
         error:function(error){
             $('.model_box_save').removeAttr("disabled");
            
             $.each(error.responseJSON.errors,function(key,value){
                   if(key == 'package_id'){
                     $('#package_error').html(value).addClass('active').show();
                   }
                   else if(key == 'payment_method'){
                     $('#payment_method').html(value).addClass('active').show();
                   }
                   else {
                     $('#errorId').html(value).addClass('active').show();
                   }
             }); 
         },
         complete:function(data){
                 console.log(data.responseJSON.message);
                 if(data.status == 403){
                    alert(data.responseJSON.message);
                 }
                 else if(data.status == 500){
                    location.reload();
                 }
                 else {
                   // alert('not allowed');
                 }
                $('body').waitMe('hide');
               // $('#paymentModal').modal('hide');
          },
       });
   }

   $("#paymentModal").on('hide.bs.modal', function () {
        $('#formbody').show();
        $('#ppplus').hide();
    });
</script>
@endsection
