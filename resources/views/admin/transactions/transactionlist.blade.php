@extends('layouts.adminapp')
@section('content')
@php
$name = trans('label.transaction_list');
@endphp
@include('admin.breadcome', ['name' => $name])
<div class="product-status mg-b-30">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-status-wrap">
               <h4>{{ $name }} </h4>
               <div style="margin-bottom: 15px">
                  <form method="POST" id="order-search-form" class="form-inline" role="form">
                     <div class="form-group">
                        <input type="text" class="form-control product_string" name="product_title"  id="name" placeholder="{{ trans('label.transaction_search') }}" value="{{ $filter_string }}">
                        <input type="text" class="form-control product_string" name="customer_name"  id="customer_name" placeholder="{{ trans('label.customer_name') }}" value="{{ $filter_string }}">
                        <input type="text" class="form-control product_string" name="email"  id="email" placeholder="{{ trans('label.order_email') }}" value="{{ $filter_string }}">
                     </div>
                   <!--  <div class="form-group">
                        {!! Form::select('status', [''=>trans('label.status_label'),'0'=>trans('label.pending'),'1'=>trans('label.complete'),'2'=>trans('label.failed')], $filter_status, ['class' => 'status_filter form-control pro-edt-select form-control-primary']) !!}
                     </div> -->
                     <div class="form-group">
                        {!! Form::select('payment_method',array('' => trans('label.payment_method')) + $typeList,null,array("class" => "form-control category_id")) !!}
                     </div>
                    <a href="#" class="reset_all_filter"><b> {{trans('label.reset')}} </b></a>
                    <button style="float: right; margin-right: 10px"href="#" class="btn btn-primary basic-btn export_record" ><b> {{trans('label.export')}} </b></button>
                    <button style="float: right; margin-right: 10px; "href="#" class="btn btn-primary basic-btn export_invoice_record" ><b> {{trans('label.export_invoice')}} </b></button>
                     <span class="error alert-error" id="export" style="color:red;float: right;"></span>
                  </form>
               </div>
               <div class="table-responsive referral_table">
                  @include('admin.transactions.table')
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
   $(document).ready(function(){
   
       $("select").on("change", function() {
            ajaxListTable();
       });
       $("#order-search-form").on("keyup", ".product_string",function () {
           searchword = $(this).val();
           if ((searchword.length) >= 3 || (searchword.length) == 0) {
             ajaxListTable();
           }
       });
       
       $('body').on('click', '.pagination a', function(e) {
       e.preventDefault();
        var url = $(this).attr('href');  
        var data = $("#order-search-form").serialize();
           getOrders(url,data);
       });
       $('body').on('click', '.reset_all_filter', function(e) {
            resetCheckbox();
           e.preventDefault();
           $('.product_string').val('');
           $('.status_filter').val('');
           $('.category_id').val('');
           var url = "{{ route('admin_transactionr_list') }}";  
           var data = '';
           getOrders(url,data);
       });
   
   }); 
   var ajaxReq = null;
   function ajaxListTable(){
        resetCheckbox();
       if (ajaxReq != null) ajaxReq.abort();
               ajaxReq = $.ajax({
               url:"{{ route('admin_transactionr_list') }}",
               data:$("#order-search-form").serialize(),
               type:"GET",
               processData: false,
               contentType: false,
               dataType: "html",
               success:function(data){
                   $(".referral_table").html(data);
                   checkboxs();
                   renderCheckbox();
               },
               error:function(data){
                   $(".referral_table").html(data);
                   checkboxs();
                   renderCheckbox();
               }
        });
   }
   function getOrders(url,data) {
        var isSelectAll = 0;
        if($('.checked_all:checked').length){
            isSelectAll = 1;
        }
       $.ajax({
           url:url,
           type:"GET",
           data:data,
           processData: false,
           contentType: false,
           dataType: "html",
           success:function(data){
               $(".referral_table").html(data);
               checkboxs();
               renderCheckbox();
               if(isSelectAll) {
                //window.order_id_list = [];
                  $('.checkbox').prop('checked', true);            
                  selectAll();
                  $('.checked_all').prop('checked',true);
                }
                else{
                    $('.checked_all').prop('checked',false);
                }
               //console.log();
           },
           error:function(data){
               $(".referral_table").html(data);
               checkboxs();
               renderCheckbox();
           }
       });
   }
   
   
     
    $('body').on('click', '.export_record', function(e) {
       $('#export').html("{{ trans('message.export_validation_message')}}").removeClass('active').hide();
       e.preventDefault();
       if(!$('.checkbox:checked').length)
       {   
           $('#export').html("{{ trans('message.export_validation_message')}}").addClass('active').show();
          // alert('please select some record');
       }
       else{
           var selectAll = false;
          // var ids =  $('.checkbox:checked').map(function(){return $(this).val();}).get();
   
          var ids  = window.order_id_list;
            if($('.checkbox:checked').length == $('.checkbox').length){
              selectAll = true
              ids = [];
            }
   
           var query = {
               select_all: selectAll,
               order_id: ids,
               status: $('.status_filter').val(),
               payment_method: $('.category_id').val(),
               product_title: $('.product_string').val(),
           }
           var url = "{{ route('admin_transaction_export') }}?" + $.param(query)
           window.location = url;
           $('.checkbox').prop('checked', false);      
           resetCheckbox();
       }
       fadeOutAlertMessages();
   });
   
    // function for checkboxes
    function checkboxs(){
       $('.checked_all').on('change', function() {     
           $('.checkbox').prop('checked', $(this).prop("checked"));            
            selectAll();
       });
       //deselect "checked all", if one of the listed checkbox product is unchecked amd select "checked all" if all of the listed checkbox product is checked
       $('.checkbox').change(function(){ //".checkbox" change 
         //  console.log(11);
            if(typeof window.order_id_list == 'undefined') {
                window.order_id_list = [];
            }
            if($(this).prop('checked')) {
                if(window.order_id_list.indexOf($(this).val()) == -1)
                    window.order_id_list.push($(this).val());
               // window.order_id_list.push($(this).val());
            }
            else {
                 var value = $(this).val();
                 window.order_id_list = window.order_id_list.filter(function(item) { 
                    return item !== value;
                })
            }
          //  console.log(window.order_id_list);
           if($('.checkbox:checked').length == $('.checkbox').length){
                  $('.checked_all').prop('checked',true);
           }else{
                  $('.checked_all').prop('checked',false);
           }
       });
   
    }
   
   
   
   function renderCheckbox(){
      if(typeof window.order_id_list !== 'undefined') {
          $.each(window.order_id_list, function(key,value) {
              $('input[value="'+value+'"]').prop('checked',true);
          });  
      }
   
      if($('.checkbox:checked').length == $('.checkbox').length && $('.checkbox').length !=0 ){
          $('.checked_all').prop('checked',true);
      }
      else{
          $('.checked_all').prop('checked',false);
      }
   }
   
   
   function selectAll(){
      $(".checkbox").each(function() {
          if(typeof window.order_id_list == 'undefined') {
              window.order_id_list = [];
          }
          if($(this).prop('checked')) {
              if(window.order_id_list.indexOf($(this).val()) == -1)
                  window.order_id_list.push($(this).val());
          }
          else {
               var value = $(this).val();
               window.order_id_list = window.order_id_list.filter(function(item) { 
                  return item !== value;
              })
          }                 
          // ...
      });
   }
   
   function resetCheckbox(){
    $('.checked_all').prop('checked',false);
    $('.checked').prop('checked',false);
    delete(window.order_id_list);
    window.order_id_list = undefined;
   }
   
   checkboxs();
   renderCheckbox();

   $('body').on('click', '.export_invoice_record', function(e) {
       $('#export').html("{{ trans('message.export_validation_message')}}").removeClass('active').hide();
       e.preventDefault();
       if(!$('.checkbox:checked').length)
       {   
           $('#export').html("{{ trans('message.export_validation_message')}}").addClass('active').show();
          // alert('please select some record');
       }
       else{
           var selectAll = false;
          // var ids =  $('.checkbox:checked').map(function(){return $(this).val();}).get();
   
          var ids  = window.order_id_list;
            if($('.checkbox:checked').length == $('.checkbox').length){
              selectAll = true
              ids = [];
            }
   
           var query = {
               select_all: selectAll,
               order_id: ids,
               status: $('.status_filter').val(),
               payment_method: $('.category_id').val(),
               product_title: $('.product_string').val(),
           }
           var url = "{{ route('admin_transaction_invoice_export') }}?" + $.param(query)
           window.location = url;
           $('.checkbox').prop('checked', false);      
           resetCheckbox();
       }
       fadeOutAlertMessages();
   });
</script> 
@endsection