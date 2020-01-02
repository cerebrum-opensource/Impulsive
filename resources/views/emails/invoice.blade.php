@component('mail::message')

<p>{{ trans('email_template.hello') }} {{ $user->username }},</p>
<p>{{ trans('email_template.payment_confirmed_text') }}</p>
<p>{{ trans('email_template.shipping_text') }}</p>
<p>{{ trans('email_template.invoice_attached_text') }}</p>
<p>{{ trans('email_template.continue_enjoy') }}</p>
<p>{{ trans('email_template.your_team') }}</p>
 
@endcomponent