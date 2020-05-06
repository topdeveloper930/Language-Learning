@component('mail::legacy')
    {{-- Header --}}
    @slot('header')
        @component( 'mail::header_ll' )
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
        @component('mail::footer_coordinator')
            <b>Skype:</b> <a href="skype:{{ config('legacy.globals.skype') }}">{{ config('legacy.globals.skype') }}</a><br>
            <u>@lang('emails.office_hours')</u><br>
            <i>@lang('emails.working_hours')</i><br>
            <u>@lang('emails.classes_available')</u><br>
            <i>@lang('emails.7_24')</i>
        @endcomponent
    @endslot
@endcomponent
