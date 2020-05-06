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
        @component('mail::footer_ll')
            @lang( 'emails.copyright', [ 'year' => date('Y'), 'company' => config( 'legacy.globals.businessName' ) ] )
        @endcomponent
    @endslot
@endcomponent
