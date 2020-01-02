@inject('staticService','App\Services\StaticContent')
@php
$slider = $staticService->getSliderList();

@endphp

@if(count($slider))
<div class="banner">
   <!--banner start-->  
   <div id="demo" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner carousel-inner2">

         @foreach($slider as $key => $slide)

         @php
            $active = '';
               if($key == 0)
               $active = 'active';
         @endphp

         <div class="carousel-item {{ $active }}">
            <img src="{{ asset('/images/admin/sliders/').'/'.$slide->id.'/'.$slide->image }}" class="box-img2" alt="banner"/>
            <div class="banner-caption {{ $slide->header_text_position }}">
               <h4 class="{{ $slide->header_text_position }}" style='background-color: {{ $slide->background_color  }};' >{{ $slide->heading }}</h4>
               <h3 class="{{ $slide->header_text_position }}" style='background-color: {{ $slide->background_color  }};'>{{ $slide->heading_detail }}</h3>
               @if (!Auth::guest())

                  <div class="button-otr {{ $slide->header_text_position }}">
                     <a href="{{ $slide->button_link }}">
                        <input type="button" value="{{ $slide->button_text }}">
                     </a>
                     <div class="flower">
                        <img src="{{ asset('images/flower.png') }}" alt="flower" title="flower">
                        <span class="flower2">{{ $slide->other_text }}</span>
                     </div>
                  </div>
               @else
               <div class="button-otr {{ $slide->header_text_position }}">
                     <a href="{{ $slide->button_link }}">
                        <input type="button" value="{{ $slide->button_text }}">
                     </a>
                     <div class="flower">
                        <img src="{{ asset('images/flower.png') }}" alt="flower" title="flower">
                        <span class="flower2">{{ $slide->other_text }}</span>
                     </div>
               </div>
               @endif
            </div>
         </div>

         @endforeach
      </div>
      <!-- Left and right controls -->
      <a class="carousel-control-prev" href="#demo" data-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      </a>
      <a class="carousel-control-next" href="#demo" data-slide="next">
      <span class="carousel-control-next-icon"></span>
      </a>
   </div>
</div>

@endif
<!--bannerend-->