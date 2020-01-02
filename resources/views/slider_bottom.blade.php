@inject('staticService','App\Services\StaticContent')
@php
$widgets = $staticService->getWidgetList();
@endphp
<div class="banner-bottom">
   <!--banner-bottom start--> 
   <div class="container">
      <ul>
         @foreach($widgets as $key => $widget)
         <li>
            <span class="text1">{{ $widget->header1 }}</span>
            <span class="text1 text2">{{ $widget->header2 }}</span> 
            <!--<span class="text1 text3">{{ $widget->header3 }}</span>-->
            <span class="text1 text4"><a style="color:#fff"href="{{ $widget->header4 }}"> {{ $widget->header3 }}  </a></span>
         </li>
         @endforeach
         <!-- 
            <li>
               <span class="text1">Anmelden</span>
               <span class="text1 text2">und winis kaufens</span> 
               <span class="text1 text3">auf Produkt bieten</span>
               <span class="text1 text4">> So funktioniert es</span>
            </li>
            <li>
               <span class="text1">zu der</span>
               <span class="text1 text2">winis übersicht</span> 
               <span class="text1 text3">auf Produkt bieten</span>
               <span class="text1 text4">> WINIS Übersicht</span>
            </li>
            
            -->
      </ul>
   </div>
</div>
<!--banner-bottom end-->