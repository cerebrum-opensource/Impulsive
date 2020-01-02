@component('mail::message')

<p>{{ trans('email_template.hello') }} {{ $user -> username }},</p>
<p>{{ trans('email_template.opportunity_to_rate') }}</p>
<p>{{ trans('email_template.rate_on_link_text') }}</p>
<p>{{ trans('email_template.if_link_not_opens') }}</p>
<p>{{ trans('email_template.link') }} <a href="{{ $link }}">{{ $link }}</a></p>
<p>{{ trans('email_template.continue_enjoy') }}</p>
<p>{{ trans('email_template.your_team') }}</p>
 
@endcomponent