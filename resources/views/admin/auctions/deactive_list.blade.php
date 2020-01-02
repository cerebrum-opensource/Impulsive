@extends('layouts.adminapp')
@section('content')
@php
$name = trans('label.de_active');
@endphp
@include('admin.breadcome', ['name' => $name])
<div class="product-status mg-b-30">
   <div class="container-fluid"> 
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-status-wrap">
               <h4>{{ trans('label.de_active') }}</h4>
              <button style="float: right;"href="#" class="btn btn-primary basic-btn export_record" id="deactive_export"><b> {{trans('label.export')}} </b></button>
               <table class="datatable">
                  <thead>
                     <tr>
                        <th>{{ trans('label.serial_number_short_form') }}</th>
                        <th>{{ trans('label.product_title') }} </th>
                        <th>{{ trans('label.counter') }} </th>
                        <!-- <th>{{ trans('label.end_date_time') }} </th> -->
                        <th>{{ trans('label.regular_price') }} </th>
                        <th>{{ trans('label.bid_price') }} </th>
                        <th>{{ trans('label.bid_count') }} </th>
                         <th>{{ trans('label.winner') }} </th>
                        <th>{{ trans('label.show_details') }} </th>
                     </tr> 
                  </thead>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
   jQuery(document).ready(function() {
        $.ajaxSetup({
           type:"POST",
           headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
           beforeSend:function(){
               $('.product-status .container-fluid').waitMe();
           },
           complete:function(){
               $('.product-status .container-fluid').waitMe('hide');
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
       
      
       $('.datatable').DataTable({
           processing: true,
           serverSide: true,
           bSort: false,
           ajax: "{{ url('admin/deactiveAuctionList') }}",
           language: {
             searchPlaceholder: "{{trans('label.search_by_product_title')}}",
           },
           columns: [
               {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
               {data: 'product.product_title', name: 'product.product_title',orderable: false},
               {data: 'duration', name: 'duration',orderable: false, searchable: false},
               // {data: 'end_time', name: 'end_time',orderable: false, searchable: false},
               {data: 'product_price', name: 'product_price',orderable: false},
               {data: 'bid_price', name: 'bid_price',orderable: false, searchable: false},
               {data: 'bid_count', name: 'bid_count',orderable: false, searchable: false},
               {data: 'winner', name: 'winner',orderable: false, searchable: false},
               {data: 'setting', name: 'setting',orderable: false, searchable: false},
           ]
       });
       
   });
   $('body').on('click', '.export_record', function(e) {
      var url = "{{ route('admin_deactiveAuction_export') }}";
      window.location = url;
   });
</script>
@endsection