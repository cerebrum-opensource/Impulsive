@component('mail::message')

<p>{{ trans('email_template.hello') }} {{ $user->username }},</p>
<p>{{ trans('email_template.newly_purchased_bid_text') }} </p>
<p>{{ trans('email_template.invoice_attached_text') }} </p>


@if($package_detail->type == 'promotional')
	<p>{{ trans('email_template.additionally_recieved') }} {{ $package_detail->bonus }} {{ trans('email_template.free_bids') }} {{ trans('email_template.valid_for') }} {{ $bid_day_count }}  {{ trans('email_template.days') }} </p>
@endif

<p>{{ trans('email_template.good_luck_text') }} </p>
<p>{{ trans('email_template.your_team') }} </p>

@endcomponent