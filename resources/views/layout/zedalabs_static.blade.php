@php
    isset($user) OR $user = null;
    $tplConfig = App\Services\Config\TemplateConfig::instance(
    	config('styles'),
    	config('scripts'),
    	[ 'scripts'   => [ 'slideout_menu' ] ]
    );

    if(empty($language)) {
    	$language = request()->route('language');
        $language OR $language = 'spanish';
        $isDefault = $language == 'spanish';
    }
@endphp
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
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">

    <meta name="title" content="" />
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">

    <title>{{ $meta_title }}</title>

    <!-- Favicons -->
    @include('layout.zedalabs.blocks.head_favicons')

    @stack('styles')

</head>
<body>

@include('layout.zedalabs.blocks.guest_mobile_menu')
<div class="hidden-menu-overlay"></div>

@include( 'layout.zedalabs.blocks.guest_language_menu' )

@include('layout.zedalabs.blocks.header_navbar')

@yield('main', '')

@include('layout.zedalabs.blocks.footer_navbar')

@stack('scripts')

</body>
</html>