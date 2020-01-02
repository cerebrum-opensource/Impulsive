<a href="{{ route('edit_user', encrypt_decrypt('encrypt',$id))}}">
	<button data-toggle="tooltip" title="Edit" class="pd-setting-ed"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
</a>
<a href="{{ route('user_detail', encrypt_decrypt('encrypt',$id))}}">
	<button data-toggle="tooltip" title="View" class="pd-setting-ed"> <i class="fa fa-eye" aria-hidden="true"></i></button>
</a>
	<button title="Delete" class="pd-setting-ed"><i class="fa fa-remove " aria-hidden="true" onclick="user_delete({{ $id }})"></i></button> 