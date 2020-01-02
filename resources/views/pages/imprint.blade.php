@extends('layouts.app')
@section('title')
 Impressum
@endsection
@section('content')

@if($data)
<div class="live-deals winis impressum-container">
     {!! $data->textarea1 !!}
</div>
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