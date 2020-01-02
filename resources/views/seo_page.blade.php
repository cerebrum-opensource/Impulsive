@inject('staticService','App\Services\StaticContent')
@php
$type = 1;
  if(isset($page_type))
    $type = $page_type;

$widget = $staticService->getSeoWidget($type);
@endphp

<div class="das-wird">
<div class="container">
  @if($widget)
   <div class="row">
      <div class="col-md-12">
         <div class="das">
            <h2>{{ $widget->header1 }} <span>{{ $widget->header2 }}</span></h2>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-6">
         <div class="bt-lt">
            {!! $widget->paragraph_1 !!} 
         </div>
      </div>
      <div class="col-md-6">
         <div class="bt-lt">
            {!! $widget->paragraph_2 !!} 
         </div>
      </div>
   </div>
   @endif
</div>
</div>


