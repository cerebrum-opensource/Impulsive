@if($status == 1)
	<a href="javascript:changeStatus('user',0,{{ $id }},'disable')"><span class="label label-sm label-danger"> {{ trans('label.disable') }} </span></a>
@elseif($status == 0)
	<a href="javascript:changeStatus('user',1,{{ $id }},'activate')"><span class="label label-sm label-success"> {{ trans('label.Activate') }} </span></a>
@else
	<span class="label label-sm label-warning"> N/A </span>
@endif