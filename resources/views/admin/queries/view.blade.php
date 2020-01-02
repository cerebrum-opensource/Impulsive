@extends('layouts.adminapp')

@section('content')

@php
  $name = trans('label.contact_us');
  
@endphp
@include('admin.breadcome', ['name' => $name])
  
 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
 <!-- <link rel="stylesheet" href="{{ asset('modal_files/css/bootstrap.min.css') }}"> -->
<div class="single-product-tab-area mg-b-30">
   <!-- Single pro tab review Start-->
   <div class="single-pro-review-area">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="review-tab-pro-inner">
                  <ul id="myTab3" class="tab-review-design">
                     <li class="active"><a href="#description">{{ trans('label.contact_us') }} </a></li>
                  </ul>
                  <div class="row">
                     <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="review-content-section">
                           <p> {{ trans('label.name') }} : <span> {{ $detail->name != ' ' ? $detail->name  : 'N/A'}} </span></p>
                           <p> {{ trans('label.email') }} : <span> {{ $detail->email ? : 'N/A'}} </span></p>
                           <p> {{ trans('label.username') }} : <span> {{ $detail->username ? : 'N/A'}} </span></p>
                           <p> {{ trans('label.subject') }}  : <span> {{ $detail->subject ? : 'N/A'}} </span></p>
                           <p> {{ trans('label.message') }}  : <span> {{ $detail->message ? : 'N/A'}} </span></p>
                        </div>
                     </div>
                   
                  </div>
                  @if($detail->status != 2)
                  <a href=""><button data-toggle="tooltip" title="{{ trans('label.reply') }}" class="pd-setting-ed reply_btn" style="background-color: #434c5e;padding: 6px 20px;
    color: #fff;border-radius: 4px;border: 1px solid #434c5e;text-align: center;">Reply</button></a>
                  @endif
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="paymentModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
     
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">{{ trans('label.reply') }}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <form id="replyMailForm" action="javascript:;" method="post">
              <label>Reply Message:</label><br />
              <input type="hidden" name="email" value="{{ $detail->email ? : 'N/A'}}">
              <input type="hidden" name="id" value="{{ $detail->id ? : 'N/A'}}">
              <textarea rows="3" columns="6" name="reply_message" required style="width:100%;"></textarea>
              <button onClick="javascript:sendMail('#replyMailForm')" style="background-color: #434c5e;padding: 6px 20px;
    color: #fff;border-radius: 4px;border: 1px solid #434c5e;text-align: center;">Send</button>
           </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color: #434c5e;padding: 6px 20px;
    color: #fff;border-radius: 4px;border: 1px solid #434c5e;text-align: center;">Close</button>
         </div>
     </div>
 </div>
</div>
@endsection
@section('javascript')

  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->
  <script src="{{ asset('modal_files/js/bootstrap.min.js') }}"></script>
<script type="text/javascript">
   
$(".reply_btn").on("click",(function(e){
             e.preventDefault();
             $('#paymentModal').modal('show');
       }));
function sendMail(formId,tab){
   var formData = new FormData($(formId)[0]);
   console.log(formData);
   $.ajax({
         url:"{{ route('reply_mail') }}",
         data:formData,
         type:"POST",
         processData: false,
         contentType: false,
         dataType: "json",
         success:function(response){
            console.log(response);
            $("#paymentModal .close").click()
         },
         error:function(error){
            alert(error.responseJSON.errors.reply_message);
         },
      });
}
</script>

@endsection
