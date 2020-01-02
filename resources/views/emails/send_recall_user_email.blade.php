
@component('mail::message')
<p>{{ trans('email_template.hello') }} {{ $user }},</p>
<p>{{ trans('email_template.Wie_gewünscht_möchten_wir_dich_an_dein_Wunschartikel_erinnern') }}</p>
<p>{{ trans('email_template.Ab_diesem_Moment_ist_der_Artikel_in_den_Live_Auktionen') }}</p>
<p>{{ trans('email_template.good_luck_text')}}</p>
<p>{{ trans('email_template.your_team') }}</p>

@endcomponent