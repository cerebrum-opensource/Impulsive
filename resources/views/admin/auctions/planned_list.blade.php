@extends('layouts.adminapp')
@section('content')
@php
$name = trans('label.planned');
@endphp
@include('admin.breadcome', ['name' => $name])
<div class="product-status mg-b-30">
   <div class="container-fluid"> 
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-status-wrap">
               <h4>{{ trans('label.planned') }}</h4>
              
               <table class="datatable">
                  <thead>
                     <tr>
                        <th>{{ trans('label.serial_number_short_form') }}</th>
                        <th>{{ trans('label.product_title') }} </th>
                        <th>{{ trans('label.counter') }} </th>
                        <th>{{ trans('label.start_date_time') }} </th>
                        <th>{{ trans('label.countdown') }} </th>
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
               $('container-fluid').waitMe();
           },
           complete:function(){
               $('container-fluid').waitMe('hide');
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
           ajax: "{{ url('admin/plannedAuctionList') }}",
           language: {
             searchPlaceholder: "{{trans('label.search_by_product_title')}}",
           },
           columns: [
               {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
               {data: 'product.product_title', name: 'product.product_title',orderable: false},
               {data: 'duration', name: 'duration',orderable: false, searchable: false},
               {data: 'start_time', name: 'start_time',orderable: false, searchable: false},
               {data: 'duration', name: 'duration',orderable: false, searchable: false},
               {data: 'setting', name: 'setting',orderable: false, searchable: false},
           ]
       });
       
   });

</script>
@endsection