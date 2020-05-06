@php
    $lang = app()->getLocale();
    isset( $ajax_url ) OR $ajax_url = request()->url();
@endphp

{{--
    These are basic stylesheets. More stylesheets can be added in "head" section if need be.
    This is to preserve backward compatibility with legacy design that uses $tplConfig->styles container.
    REVIEW: Consider moving legacy code to AdminController since the legacy design remains there only.
--}}
@push('styles')
    @php( print new \App\Custom\Style( $tplConfig->getStyle( 'framework' ) ) )
    @php( print new \App\Custom\Style( $tplConfig->getStyle( 'typekit' ) ) )
@endpush

@push('scripts')
    @foreach ( $tplConfig->scripts as $script )
        @php( print new \App\Custom\Script( $tplConfig->getScript( $script ) ) )
    @endforeach
@endpush

<!doctype html>
<html lang="{{ $lang }}">
<head>
    @include('layout.zedalabs.blocks.head_meta')

    @if ( $tplConfig->gapi_client_id )
        <meta name="google-signin-client_id" content="{{ $tplConfig->gapi_client_id }}">
    @endif

    <!-- Favicons -->
    @include('layout.zedalabs.blocks.head_favicons')

    @stack('styles')

    <script>
        var ajax_url = '{{ url( $ajax_url ) }}';
    </script>

    @yield('head')

    @if( $tplConfig->include_analytics )
        @include('layout.legacy.blocks.head_analytics')
    @endif

</head>
<body>

@if( $tplConfig->include_analytics )
    @include('layout.legacy.blocks.noscript')
@endif

<div id="fb-root"></div>
@isset( $translation )
<input type="hidden" id="translation" value="{{ json_encode( __( $translation )) }}">
@endisset

@yield('top', '')

@yield('main', '')

@yield('footer', '')

@stack('scripts')

@yield('extra', '')

</body>
</html>