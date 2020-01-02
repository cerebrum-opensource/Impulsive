@php
$message = '';
	if(Session::get('success')){
     $message = Session::get('success');
     Session::forget('success');
}
if(Session::get('error')){
    $message = Session::get('error');
    Session::forget('error');
} 
@endphp
<div class="tab-pane {{ $message != '' ? 'show active':'' }}" id="completepayment" role="tabpanel" aria-labelledby="home-tab">
	@if($message)
 	 <div class="w3-panel w3-red w3-display-container">
            <span onclick="this.parentElement.style.display='none'"
            class="w3-button w3-red w3-large w3-display-topright">&times;</span>
            <p>{!! $message !!}</p>
    </div>
 	@endif
   
</div>