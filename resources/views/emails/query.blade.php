@component('mail::message')

<p>{{ trans('email_template.hello') }} {{ @$data['name'] }},</p>
<p>{{ trans('email_template.request_recieved') }}</p>
<p>{{ trans('email_template.processing_text') }}</p>
<p>{{ trans('email_template.afterwards') }}</p>
<p>{{ trans('email_template.continue_enjoy') }}</p>
<p>{{ trans('email_template.your_team') }}</p>

@endcomponent