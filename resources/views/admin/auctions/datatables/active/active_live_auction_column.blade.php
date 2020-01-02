@if($auction['live_auction'] == 1)
	<span class="label label-sm label-info"> {{ trans('label.yes') }} </span>
@elseif($auction['live_auction'] == 0)
	<span class="label label-sm label-info"> {{ trans('label.no') }} </span>
@else
	<span class="label label-sm label-warning"> '' </span>
@endif