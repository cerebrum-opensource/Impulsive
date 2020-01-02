@component('mail::message')
<p>{{ trans('email_template.hello') }} {{ $userData->username }},</p>
<p>{{ trans('email_template.congratulations_text') }}</p>
<p>{{ trans('email_template.delivery_information') }}</p>
<p>{{ trans('email_template.ask_complete_payment') }}</p>
<p>{{ trans('email_template.continue_enjoy') }}</p>
<p>{{ trans('email_template.your_team') }}</p>
<p>{{ trans('email_template.mandatory_condition') }}</p>

@endcomponent