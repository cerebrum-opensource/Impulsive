@component('mail::message')
<p>{{ trans('email_template.hello') }} {{ $friend->username }},</p>
<p>{{ trans('email_template.your_friend')}} {{ $user->username }} {{ trans('email_template.invite_accept_text')}}</p>
<p>{{ trans('email_template.thank_you_text')}} {{ $invite_bids->invite_bid_amount }} {{ trans('email_template.free_bids_credited_text')}}</p>
<p>{{ trans('email_template.continue_enjoy') }}</p>
<p>{{ trans('email_template.your_team') }}</p>

@endcomponent