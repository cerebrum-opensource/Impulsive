@component('mail::message')

<p>{{ trans('email_template.hello') }} {{$user->username}}</p>
<p>{{ trans('email_template.thank_you_text') }}</p>
<p>{{ trans('email_template.text_before_bid_count') }} {{ $bid_count }} {{ trans('email_template.text_after_bid_count') }}</p>
<p>{{ trans('email_template.buy_package_text') }}</p>
<p>{{ trans('email_template.try_it') }}</p>
<p>{{ trans('email_template.we_look_forward') }}</p>
<p>{{ trans('email_template.your_team') }}</p>

@endcomponent