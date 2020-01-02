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
</style>
@endsection
@section('content')

<div class="product-otr">
   
   <div class="live-deals">
      <div class="container">
         <h3 class="sec-heading"><span>{{ trans('label.recall_list') }}</span> </h3>
         <ul class="planned-deals-ul">
            @include('user.auctions.recall_auctions')
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
@include('news_letter')
@include('support_widget')
<!--live-deals end-->   



<!--  Pass the constant as per page type

@include('seo_page',['page_type' =>RECALL_LIST])
-->
@endsection
@section('javascript')
<script type="text/javascript">

   $.ajaxSetup({
    type:"POST",
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    beforeSend:function(){
     //   $('body').waitMe();
    },
    complete:function(){
      //  $('body').waitMe('hide');
    },
    error:function(error){
        

    }
});
  
</script>
@endsection