@if($status == 1)
	<span class="label label-sm label-success"> {{ trans('label.active') }} </span>
@elseif($status == 0)
	<span class="label label-sm label-danger"> {{ trans('label.de_active') }} </span>
@else
	<span class="label label-sm label-warning"> Password not generated </span>
@endif