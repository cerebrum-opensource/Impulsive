@extends('layouts.app')
@section('title')
 {{ trans('label.auctions') }}
@endsection
@section('content')

 <!--slider view-->  
@include('slider')

 <!--slider bottom view-->  
@include('slider_bottom')

@if (!Auth::guest())
   <input type="hidden" name="user_id" value="{{ encrypt_decrypt('encrypt', Auth::id()) }}" data-value="{{Auth::id()}}"> 
@else
   <input type="hidden" name="user_id" value="0">
@endif


<div class="live-deals kommende-deals-section">    <!--live-deals BEENDETE start-->  
   <div class="container">
      <h3 class="sec-heading sec-heading2"><span>{{ trans('label.coming') }} </span> {{ trans('label.deals') }}</h3>
       <ul class="expired-deals-ul">
         @include('user.auctions.queue_auctions')
      </ul>
   </div>
</div> <!--live-deals BEENDETE end-->  

<!--<div class="KEINE-WINIMI">
   <div class="container">
      <h3 class="sec-heading sec-heading3">KEINE WINIMI-DEALS VERPASEN <span>– JETZT EINTRAGEN</span></h3>
      <div class="kinni-inner">
         <div class="kinni-lt">
            <p>keine deals verpassen.<span>jetzt eintragen.</span></p>
            <h6>Gorgeous monthly collections of lingerie you'll love, at prices you'll adore!</h6>
         </div>
         <div class="kinni-rt">
            <p>NEUSTEN DEALS</p>
            <h6>Bei Anmeldung xx WINIS kostenlos <span>erhalten</span></h6>
            <input type="text" value="Deine E-Mail Adresse" class="input-box">
            <input type="button" value="Anmelden" class="btn-amendon">
         </div>
      </div>
   </div>
</div>-->
@include('news_letter')
@include('support_widget')
 <!--
<div class="service-grid">
  
   <div class="container">
      <ul>
         <li>
            <span class="service-img"><img src="{{ asset('images/versand.png') }}" alt="versand" title="versand"></span>
            <span class="service-text">Versand</span>
         </li>
         <li>
            <span class="service-img service-img2"><img src="{{ asset('images/auktion.png') }}" alt="auktion" title="auktion"></span>
            <span class="service-text service-text2">Auktionen laufen <span>im 10 & 15 Sek. takt</span></span>
         </li>
         <li>
            <span class="service-img service-img2 service-img2a"><img src="{{ asset('images/email.png') }}" alt="email" title="email"></span>
            <span class="service-text service-text2 service-img2a">EMAIL SUPPORT <span>im 10 & 15 Sek. takt</span></span>
         </li>
      </ul>
   </div>
</div> -->


<!--  Pass the constant as per page type-->
@include('seo_page',['page_type' =>COMMING_AUCTION])
@endsection


@section('javascript')

@include('layouts.route_name')

<script src="{{ asset('js/auction/auction.js') }}" type="text/javascript"></script>
<script type="text/javascript">
@if(old('is_frontend'))
      openLoginNav()
@endif
</script>

@endsection