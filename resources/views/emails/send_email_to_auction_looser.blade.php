@component('mail::message')
<p>{{ trans('email_template.hello') }} {{ $user }},</p>

<p>{{ trans('email_template.loss_auction_text_line1') }}</p>
<p>{{ trans('email_template.loss_auction_text_line2') }}</p>
<p>{{ trans('email_template.loss_auction_text_line3') }}</p>
<a href="{{ route('frontend_loss_auction',[$link]) }}">{{ trans('message.click_here') }}</a>
<p>{{ trans('email_template.continue_enjoy') }}</p>
<p>{{ trans('email_template.your_team') }}</p>


<p>{{ trans('email_template.Dieses_Kaufangebot_ist') }}</p>
@endcomponent
