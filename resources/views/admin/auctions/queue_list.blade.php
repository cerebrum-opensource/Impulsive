@extends('layouts.adminapp')

@section('css')

<style>

</style>

@endsection

@section('content')
@php
$name = trans('label.queue_list');
@endphp
@include('admin.breadcome', ['name' => $name])
<div class="product-status mg-b-30">
   <div class="container-fluid"> 
      <div class="row">
         
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-status-wrap">
               <h4>{{ trans('label.queue_list') }}</h4>
               
               <table class="datatable" >
                  <thead>
                     <tr>
                       <!-- <th>{{ trans('label.serial_number_short_form') }}</th> --> 
                        <th>{{ trans('label.priority') }} </th>
                        <th>{{ trans('label.product_title') }} </th>
                        <th>{{ trans('label.counter') }} </th>
                        <th>Settings</th>
                     </tr>
                  </thead>
                   <tbody id="auction_list">
                     @if(count($queueAuctions))   
                     @foreach($queueAuctions as $key => $queueAuction)
                     <tr data-auction-id="{{ $queueAuction->id }}">
                    <!--    <td>{{ $key+1 }}</td> -->
                        <td class="priority_td">{{ $queueAuction->priority }} </td>
                        <td>{{ $queueAuction->product->product_title }} </td>                     
                        <td>{{ $queueAuction->duration }} </td>

                        <td><a href="{{ route('add_auction', encrypt_decrypt('encrypt', $queueAuction->id))}}"><button data-toggle="tooltip" title="Edit" class="pd-setting-ed"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a> <button data-toggle="tooltip" title="Trash" class="pd-setting-ed" id="product_delete_btn" onclick="queue_auction_delete({{$queueAuction->id}})"><i class="fa fa-remove " aria-hidden="true"></i></button></td>
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
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
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


     });

   function queue_auction_delete(queue_auction_id){

        var confrm = confirm("{{ trans('message.are_you_sure_delete_auction') }}");
        console.log(confrm);
        if(confrm == true)
        {
            $.ajax({
                url:"{{route('queue-auction-delete')}}",
                type:"POST",
                data:{id:queue_auction_id},
                // processData: false,
                // contentType: false,
                dataType: "json",
                success:function(data){
                   alert(data.message);
                   location.reload();
                },
            });
        }
    }
   $("#auction_list").sortable({
   placeholder : "ui-state-highlight",
   update  : function(event, ui)
   {
      var auction_priority_ids = new Array();
      $('#auction_list tr').each(function(){
         auction_priority_ids.push($(this).data("auction-id"));
      });
      $.ajax({
       url:"{{ route('update_priority') }}",
       data:{auction_priority_ids:auction_priority_ids},
       dataType: "json",
       success:function(data){ 
            $('#auction_list tr').each(function(){
                  var id = $(this).data("auction-id");
                  $(this).find('.priority_td').text(data.data[id]);
             });
       },
       error:function(error){
          // console.log(error.status);
           if(error.status == 500){
               alert('please reload');
           }
           else {
    
           }  
       }
     });
   }
});

   $(document).ready(function() {
    $('.datatable').DataTable({
        "dom": 'ftip'
    });
} );
</script>
@endsection