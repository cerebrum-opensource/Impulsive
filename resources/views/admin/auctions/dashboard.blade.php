@extends('layouts.adminapp')

@section('css')

<style>
.mt_row{
  margin-top:20px;
}
</style>

@endsection

@section('content')
@php
$name = trans('label.auction_list');
@endphp
@include('admin.breadcome', ['name' => $name])
<div class="product-status mg-b-30">
   <div class="container-fluid"> 
      <div class="row mt_row">
         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="product-status-wrap">
               <h4>{{ trans('label.active') }}</h4>
              
               <table class="datatable" id="live_auction_table">
                  <thead>
                     <tr>
                        <th>{{ trans('label.serial_number_short_form') }}</th>
                        <th>{{ trans('label.product_title') }} </th>
                        <th>{{ trans('label.live_auction') }} </th>
                        <th>{{ trans('label.counter') }} </th>
                     </tr>
                  </thead>
                  <tbody id="live_auction_tbody">
                    @include('admin.auctions.live_auction_table')
                  </tbody>

               </table>
            </div>
         </div>
         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="product-status-wrap">
               <h4>{{ trans('label.queue') }}</h4>
               
               <table class="datatable">
                  <thead>
                     <tr>
                        <th>{{ trans('label.serial_number_short_form') }}</th>
                        <th>{{ trans('label.product_title') }} </th>
                        <th>{{ trans('label.start_date_time') }} </th>
                        <th>{{ trans('label.counter') }} </th>
                     </tr>
                  </thead>
                   <tbody>
                     @if(count($queueAuctions))   
                     @foreach($queueAuctions as $key => $queueAuction)
                     <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $queueAuction->product->product_title }} </td>
                        <td>{{ $queueAuction->start_date_time }} </td>
                        <td>{{ $queueAuction->duration }} </td>
                     </tr>
                     @endforeach             
                     @endif
                  </tbody>
               </table>
            </div>
         </div>
        </div>
        <div class="row mt_row">
         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="product-status-wrap">
               <h4>{{ trans('label.planned') }}</h4>
               <table class="datatable">
                  <thead>
                     <tr>
                        <th>{{ trans('label.serial_number_short_form') }}</th>
                        <th>{{ trans('label.product_title') }} </th>
                        <th>{{ trans('label.start_date_time') }} </th>
                        <th>{{ trans('label.counter') }} </th>
                     </tr>
                  </thead>
                   <tbody>
                     @if(count($plannedAuctions))   
                     @foreach($plannedAuctions as $key => $plannedAuction)
                     <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $plannedAuction->product->product_title }} </td>
                        <td>{{ $plannedAuction->start_time }} </td>
                        <td>{{ $plannedAuction->duration }} </td>
                     </tr>
                     @endforeach             
                     @endif
                  </tbody>
               </table>
            </div>
         </div>
         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="product-status-wrap">
               <h4>{{ trans('label.de_active') }}</h4>
              
               <table class="datatable">
                  <thead>
                     <tr>
                        <th>{{ trans('label.serial_number_short_form') }}</th>
                        <th>{{ trans('label.product_title') }} </th>
                        <!-- <th>{{ trans('label.end_date_time') }} </th> -->
                        <th>{{ trans('label.end_price') }} </th>
                     </tr>
                  </thead>
                   <tbody>
                     @if(count($expiredAuctions))   
                     @foreach($expiredAuctions as $key => $expiredAuction)
                     <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $expiredAuction->product->product_title }} </td>
                       <!--  <td>{{ $expiredAuction->end_time }} </td> -->
                        <td>{{ $expiredAuction->bid_price }} </td>
                     </tr>
                     @endforeach             
                     @endif
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
   $(document).ready(function () {
   
 
   // run the ajax every 10 seconds for new live auctions and stop if any error 
   var timeOut = setInterval(function()
      { 
      
      $.ajax({
         url:"{{ route('auctions_dashboard') }}",
         type:"GET",
         
       //  dataType: "json",
         beforeSend:function(){
         //   $('#live_auction_table').waitMe();
         },
         complete:function(){
          //  $('#live_auction_table').waitMe('hide');
         },
         success:function(data){
            $("#live_auction_tbody").html(data);
         },
         error:function(data){
            // $("#live_auction_table").html(data.html);
         }
      });

     

      }, 1000);
   //} 


});

</script>
@endsection