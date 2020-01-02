@if($is_complete == 1)
	<span > {{ trans('label.complete') }} </span>
@elseif($is_complete == 0)
	<span > {{ trans('label.incomplete') }} </span>
@else
	<span >  </span>
@endif