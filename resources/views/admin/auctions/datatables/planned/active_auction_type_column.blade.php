@if($auction_type == 0)
	<span class="label label-sm label-info"> {{ trans('label.live') }} </span>
@elseif($auction_type == 1)
	<span class="label label-sm label-info"> {{ trans('label.queue') }} </span>
@elseif($auction_type == 2)
	<span class="label label-sm label-warning"> {{ trans('label.planned') }} </span>
@endif