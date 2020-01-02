@extends('layouts.adminapp')
@section('content')

@php
  $name = trans('label.transaction_detail');
@endphp
@include('admin.breadcome', ['name' => $name])

<div class="single-product-tab-area mg-b-30">
   <!-- Single pro tab review Start-->
   <div class="single-pro-review-area">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="review-tab-pro-inner">
                  <ul id="myTab3" class="tab-review-design">
                     <li class="active"><a href="#description">{{ $name }} </a></li>
                  </ul>
                  <div class="row">
                     <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="review-content-section">
                           <p> {{ trans('label.customer_name') }} : <span> {{ $detail->user->fullname != '' ? $detail->user->fullname : 'N/A'  }} </span></p>
                          <p> {{ trans('label.invoice_number') }}  : <span> {{ $detail->id ? trans('label.invoice').$detail->id  : 'N/A'  }} </span></p>
                           <p> {{ trans('label.payment_method') }}  : <span> {!! $detail->getPaymentMethodnameAttribute() !!} </span></p>
                           <p> {{ trans('label.transaction_type') }} : <span>  {!! $detail->getTypenameAttribute() !!}  </span></p>
                           <p> {{ trans('label.status') }} : <span>  {!! $detail->getStatusNameAttribute() !!} </span></p> 
                            <p> {{ trans('label.total_amount') }}  : <span> {!! CURRENCY_ICON.($detail->amount) !!} </span></p>

                            <a href="#" class="export_invoice_record"> {{ trans('label.download_invoice') }}</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('javascript')

<script type="text/javascript">
  $('body').on('click', '.export_invoice_record', function(e) { 
    e.preventDefault();
    var url = "{{ route('admin_transaction_detail_invoice_export',[encrypt_decrypt('encrypt',$detail->id)]) }}"
    window.location = url;
  });
</script>

@endsection
