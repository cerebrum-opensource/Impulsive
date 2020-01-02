@extends('layouts.app')

@section('title')
 {{ trans('label.home') }}
@endsection
@section('content')



<div class="live-deals">
   <!--live-deals start-->  
   <div class="container">
      <h3 class="sec-heading"> {{ trans('label.afterplay') }}</h3>
      <ul class="live-deals-ul">
          @if(count($products))
         @foreach($products as $product)
         @php  
         if($product->feature_image_1)
         $image = $product->feature_image_1;
         if($product->feature_image_2)
         $image = $product->feature_image_2;
         if($product->feature_image_3)
         $image = $product->feature_image_3;
         if($product->feature_image_4)
         $image = $product->feature_image_4;
         $productSeller = $product->seller ? $product->seller->full_name : 'Winimi';

      //   echo $discount;
         $discountPrice = (($product->product_price + $product->shipping_price)-$discount);


         if($discountPrice <= 0){
            $discountPrice = $product->shipping_price;
          }

         $productId = encrypt_decrypt('encrypt', $product->id);
         @endphp
         <li id="auction">
            <span class="img-upper">
            </span>
             <a href="#">
              <span class="img-count">
            <img src="@if($image) {{ asset('/images/admin/products/').'/'.$product->id.'/'.$image }} @else {{ asset('images/tool.png') }} @endif"  class="box-img2" alt="{{ $product->product_title }}" title="{{ $product->product_title }}">
            </span>
             </a>
              <span class="text-lower ">
                <span class="text1a">{{ $product->product_title }}</span>
                @if($product->show_seller)
               <span class="text1a text2a">{{ $productSeller }} </span>
               @else
               <span class="text1a text2a">&nbsp </span>
               @endif
                 <span class="text1a text2a">{{ trans('label.regular_price') }}: {{ price_reflect_format($product->product_price) }} {{ CURRENCY_ICON }} </span>
                 <span class="text1a text2a">{{ trans('label.discount_price') }}: {{ price_reflect_format($discount) }} {{ CURRENCY_ICON }}</span>
                  <span class="text1a text2a">{{ trans('label.shiping_price') }}:  {{ $product->shipping_price ? price_reflect_format($product->shipping_price).' '.CURRENCY_ICON : '' }}</span>
                <span class="text1a text2a">{{ trans('label.buy') }}: {{ price_reflect_format($discountPrice) }} {{ CURRENCY_ICON }}</span>
                 <a class="text1a text5a buy_now_btn"  data-type="{{ encrypt_decrypt('encrypt', 'product') }}" data-id="{{ $productId }}" data-amount="{{ encrypt_decrypt('encrypt', $discountPrice) }}">{{ trans('label.buy') }}</a> 
            </span>
         </li>
         @endforeach
         @else
         <span id="no_live">{{ trans('label.no_live_auctions') }} </span>
         @endif
      </ul>
   </div>
</div>


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
                    <img src="{{ asset('images/softor.png') }}" alt="softor" title="softor" style="width: 200px">
                    </label>
                    <p>{{ trans('label.payment_with_softor') }} </p>
                 </div>
                </div>

               <span class="error" id="payment_method" style="color:red"></span>
               <input type="hidden" name="amount" value="">
               <input type="hidden" name="link" value="{{ $token }}">
               <input type="hidden" name="data_type" value="">
               <input type="hidden" name="win_id" value="">
               <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::user()->id) }}">
               <span class="error" id="errorId" style="color:red"></span>
               <div class="">
                  <div class="buttonsbottom">
                     <button type="button" class="btn-form btn-form2 mt-3 model_box_save" onClick="javascript:savePayment('#choose_payment_form')">{{trans('label.pay')}}</button>
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
<!--  End content section -->
@section('javascript')

<script src="{{ asset('js/ppplus.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
   $.ajaxSetup({
             type: "POST",
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             beforeSend: function () {
                // $('body').waitMe();
             },
             complete: function () {
               //  $('body').waitMe('hide');
             },
             error: function (error) {
                 //console.log(error);
             }
         });

   $(document).ready(function () {
   
    // Attach Button click event listener 
       $(".buy_now_btn").click(function(){
            $('input[name=win_id]').val($(this).data('id'));
            $('input[name=amount]').val($(this).data('amount'));
            $('input[name=data_type]').val($(this).data('type'));
            $('#paymentModal').modal('show');
       });
   });

   function savePayment(formId,tab){
       $('.model_box_save').attr("disabled", "disabled");
       $('span.error').hide().removeClass('active');
       var formData = new FormData($(formId)[0]);
   
       $.ajax({
         url:"{{ route('product-purchase') }}",
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

/*
                    $('#ppplus').show();
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
