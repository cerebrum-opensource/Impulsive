<div class="tab-pane" id="choosepayment" role="tabpanel" aria-labelledby="home-tab">
   <div class="stelwn" id="ppplus">
      <form action="javascript:;" method="post" id="choose_payment_form">
      <h4>{{ trans('label.set_payment_method') }}</h4>
      <div class="paypal">
         <div class="form-check">
            <label class="form-check-label">
            <input type="radio" class="form-check-input" name="payment_method" checked value="paypal">
            <div class="sec1">
            <img src="{{ asset('images/paypal-logo-100x26.png') }}" alt="{{ trans('label.payment_with_paypal') }}" title="{{ trans('label.payment_with_paypal') }}">
            
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
            </label>
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
      <input type="checkbox" name="terms_conditions" value="terms_conditions" /> 
         {{ trans('label.terms_conditions1') }} <a href="https://www.winimi.de/datenschutz">https://www.winimi.de/datenschutz</a> {{ trans('label.terms_conditions2') }} <a href="https://www.winimi.de/agb">https://www.winimi.de/agb</a> {{ trans('label.terms_conditions3') }} <br/>
       <span class="error" id="payment_method" style="color:red"></span>
      <input type="hidden" name="plan_id" value="">
         <input type="hidden" name="transaction_id" value="">
         <input type="hidden" name="step_number" value="2">
         <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
         
         <!-- <span class="error" id="payment_method" style="color:red"></span> -->
         <div class="">
            <div class="buttonsbottom">
               <button type="button" class="btn-form btn-form2 mt-3 model_box_save" onClick="javascript:savePayment('#choose_payment_form','3')">{{trans('label.pay')}}</button>
            </div>
         </div>
       </form>
   </div>
</div>