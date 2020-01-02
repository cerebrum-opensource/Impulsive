@extends('layouts.app')
@section('title')
Gewinnspiel
@endsection
@section('css')
@section('content')
<div class="live-deals gewinnspiel-sub-container">
    
<iframe class="uvembed78132" frameborder="0" src="https://static.upviral.com/loader.html" ></iframe>
<script>
window.UpviralConfig = {
camp: "SRR$8$",
widget_style:'iframe',
width:"500px"}
</script>
<script language="javascript" src="https://snippet.upviral.com/upviral.js"></script>

</div>


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