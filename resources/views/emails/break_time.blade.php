@component('mail::message')
<p>{{ trans('email_template.hello') }} {{ $user }},</p>
<p>{{ trans('email_template.reminder_text')}} {{ $startTime }} {{ trans('email_template.text_after_time')}}</p>
<p>{{ trans('email_template.good_luck_text')}}</p>
<p>{{ trans('email_template.your_team') }}</p>

@endcomponent