@if($status == 1)
	<span class="label label-sm label-success"> Active </span>
@elseif($status == 0)
	<span class="label label-sm label-danger"> Inactive </span>
@else
	<span class="label label-sm label-warning"> Password not generated </span>
@endif