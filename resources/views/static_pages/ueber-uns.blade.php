@extends('layouts.app')
@section('title')
 {{ trans('label.about_us') }}
@endsection
@section('content')

<style>
    .live-deals{
        display: block !important;
    }
</style>
 <!--slider view-->  
@include('slider')

 <!--slider bottom view-->  
@include('slider_bottom')


@if($data)
<div class="live-deals winis winis my-0">
     {!! $data->textarea1 !!}
</div>
@endif



<!--live-deals end-->   
@if (!Auth::guest())
<input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::id()) }}" data-value="{{Auth::id()}}"> 
@else
<input type="hidden" name="user_id" value="0">
@endif
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