

@inject('staticService','App\Services\StaticContent')
@php
$widget = $staticService->getNewsLettertList();
@endphp
<div class="KEINE-WINIMI">
   <div class="container">
      @if($widget)
      @php
      $headerString = explode('–',$widget->header1);
      @endphp
      <h3 class="sec-heading sec-heading3">{{ @$headerString[0] }} <span> {{ @$headerString[1] ? '–'.$headerString[1] : '' }}</span></h3>
	<div class="newsletter-container">
		<div class="newsletter-container-right">
			<img src="https://winimi.de/images/newsletter2.jpg">
		</div>	
		<div class="newsletter-container-left">
	  <iframe class="uvembed78132" frameborder="0" src="https://static.upviral.com/loader.html" ></iframe>
		<script>
		window.UpviralConfig = {
		camp: "SRR$8$",
		widget_style:'iframe',
		width:"500px"}
		</script>
		<script language="javascript" src="https://snippet.upviral.com/upviral.js"></script>
		</div>	</div>
      @endif
   </div>
</div>