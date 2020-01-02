@extends('layouts.app')
@section('title')
 {{ trans('label.faq') }}
@endsection

@section('content')

<div class="FUNKTIONIERTS"><!--live-deals start--> 
      <div class="container">
         <h3 class="sec-heading"><span>{{ trans('label.faq') }} </span>– {{ trans('label.frequently_asked_questions') }}</h3>

         @php
            $keyvalue = 0;
         @endphp
         @foreach($faqTypes as $faqType)
            <div class="faq-content">
            <h3> <span> {{ $faqType->name }}</span></h3>

            <div id="accordion" class="faq-accordion">

               @foreach($faqType->faqs as $key=> $loginFaq)

                  @php
                     $Loginshow = '';
                     $collapsed = 'collapsed';
                     if($key == 0){
                        $Loginshow = 'show';
                        $collapsed = '';
                     }
                  @endphp
                  <div class="card">
                  <div class="card-header" id="headingOne">
                     <h5 class="mb-0">
                        <button class="btn btn-link faq-btn rotate-img {{ $collapsed }}" data-toggle="collapse" data-target="#collapseOne{{$keyvalue}}" aria-expanded="true" aria-controls="collapseOne{{$keyvalue}}">
                        <img src="{{ asset('images/collapse-icon.png') }}">   <span class="faq_question"> {{ $loginFaq->question }} </span>
                        </button>
                     </h5>
                  </div>

                  <div id="collapseOne{{$keyvalue}}" class="collapse {{$Loginshow}}" aria-labelledby="headingOne" data-parent="#accordion">
                     <div class="card-body">
                       {{ $loginFaq->answer }}
                     </div>
                  </div>
               </div>   
               @php
                  $keyvalue++;
               @endphp            
               @endforeach
            </div>
         </div>


         @endforeach

   
      </div>
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