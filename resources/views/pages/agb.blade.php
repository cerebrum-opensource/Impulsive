@extends('layouts.app')
@section('title')
 {{ trans('label.term_&_condition') }}
@endsection
@section('content')

@if($data)
<div class="live-deals winis agb">
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