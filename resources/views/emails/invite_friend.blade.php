@component('mail::message')
<p>{{ trans('email_template.hello') }} {{ @$friendInviteData['name'] }},</p>
<p>{{ trans('email_template.your_friend')}} {{ @$user->username }} {{ trans('email_template.invite_text')}}</p>
<p>{{ trans('email_template.registration_request_text')}} {{ $invite_bids->invite_bid_amount }} {{ trans('email_template.free_bids_text')}}</p>

<a href="{{ route('login') }}?token={{$token}}">{{ trans('message.click_here') }}</a>
<p>{{ trans('email_template.look_forward') }}</p>
<p>{{ trans('email_template.your_team') }}</p>

@endcomponent