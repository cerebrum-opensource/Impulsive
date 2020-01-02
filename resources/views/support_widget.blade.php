@inject('staticService','App\Services\StaticContent')
@php
$widgets = $staticService->getSupportWidgetList();
@endphp




<div class="service-grid">
   <!--service-grid start-->  
   <div class="container">
      <ul>
          @foreach($widgets as $key => $widget)
          @php
            $imgClass = "";
            $textClass = "";
            if($key==1){
               $imgClass = "service-img2";
               $textClass = "service-text2";
            }
            if($key==2){
               $imgClass = "service-img2 service-img2a";
               $textClass = "service-text2 service-img2a";
            }
          @endphp
         <li>
            <span class="service-img {{ $imgClass }}"><img src="{{ asset('/images/admin/widget/').'/'.$widget->id.'/'.$widget->image }}" alt="{{ $widget->header1 }}" title="{{ $widget->header1 }}"></span>
            <span class="service-text {{ $textClass }}">{{ $widget->header1 }}
               @if($widget->header2)
                  <span>{{ $widget->header2 }}</span>
               @endif
            </span>
         </li>
         @endforeach
      </ul>
   </div>
   <!--service-grid end-->  
</div>
