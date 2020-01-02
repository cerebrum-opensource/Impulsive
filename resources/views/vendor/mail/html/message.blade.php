@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{-- IMP: winimi logo--}}
            {{-- {{ config('app.name') }}--}}
            {{ asset('images/Logo.png') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')

        {{ trans('email_template.footer_text') }}  <br>
         {{ trans('email_template.footer_text1') }} <br>
         {{ trans('email_template.footer_text2') }} <br>
         {{ trans('email_template.email') }} : {{ trans('email_template.email_info') }} <br>
         {{ trans('email_template.tel') }} :  {{ trans('email_template.tel_number') }} <br>
         {{ trans('email_template.vat_idn') }} :  {{ trans('email_template.vat_number') }} <br>
         {{ trans('email_template.hrb') }} : {{ trans('email_template.number') }} <br>
         {{ trans('email_template.Amtsgericht_Stuttgart') }} <br>

        {{--    © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.') --}}
        @endcomponent
    @endslot
@endcomponent
