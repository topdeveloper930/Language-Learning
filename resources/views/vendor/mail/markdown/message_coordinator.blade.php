@component('mail::legacy')
    {{-- Header --}}
    @slot('header')
        @component('mail::header_ll', ['url' => config('app.url')])
            @lang( 'emails.from', [ 'who' => config( 'legacy.globals.businessName' ) ] )
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
        @component('mail::footer_coordinator', ['url' => config('app.url')])
            <b>Skype:</b> <span style="color:blue">{{ config('legacy.globals.skype') }}</span><br>
            <u>@lang('email.office_hours')</u><br>
            <i>@lang('email.working_hours')</i><br>
            <u>@lang('email.classes_available')</u><br>
            <i>@lang('email.7_24')</i>
        @endcomponent
    @endslot
@endcomponent
