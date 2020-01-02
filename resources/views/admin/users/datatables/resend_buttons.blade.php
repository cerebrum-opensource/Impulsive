

@if($email_verified_at == null)
	<a href="javascript:resendVerificationMail('{{ encrypt_decrypt('encrypt', $id)  }}')"><span class="label label-sm label-danger"> Send Verify Link </span></a>
@else
	<span class="label label-sm label-success"> Email Verified </span>
@endif