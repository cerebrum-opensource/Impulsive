@extends('layouts.app')
@section('title')
 {{ trans('label.how_its_work') }}
@endsection
@section('content')


   <!--live-deals start--> 
@if($data)
<div class="FUNKTIONIERTS">
     {!! $data->textarea1 !!}
</div>
@endif
  <!-- <div class="container">
      <h3 class="sec-heading"><span>{{ trans('label.how') }}</span> {{ trans('label.it_works') }}</h3>
      <div class="row">
         <div class="col-md-4">
            <div class="list-type1">
               <ul>
                  <li>
                     <span class="sr-no">1</span>
                     <span class="lorem-text">lorem ipsum lorem ipsum 
                     lorem ipsum 
                     lorem ipsum lorem ipsum </span>
                  </li>
                  <li>
                     <span class="sr-no">2</span>
                     <span class="lorem-text">lorem ipsum lorem ipsum 
                     lorem ipsum 
                     lorem ipsum lorem ipsum </span>
                  </li>
                  <li>
                     <span class="sr-no">3</span>
                     <span class="lorem-text">lorem ipsum lorem ipsum 
                     lorem ipsum 
                     lorem ipsum lorem ipsum </span>
                  </li>
               </ul>
            </div>
         </div>
         <div class="col-md-8">
            <div class="img-otr">
               <div class="img1">
                  <img src="{{ asset('images/img1.png') }} " alt="img" title="img">
               </div>
               <div class="img1">
                  <img src="{{ asset('images/img1.png') }}" alt="img" title="img">
               </div>
            </div>
         </div>
      </div>
   </div>
</div> -->
<!--live-deals end-->   

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