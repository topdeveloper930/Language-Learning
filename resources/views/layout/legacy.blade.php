@php
    $lang = app()->getLocale();
    !empty( $copyright_span ) OR $copyright_span = 'span7';
    !empty( $bottom_menu_span ) OR $bottom_menu_span = 'span5';
    !empty( $left_col_cls ) OR $left_col_cls = 'span3';
    !empty( $center_col_cls ) OR $center_col_cls = 'span9';
    !empty( $right_col_cls ) OR $right_col_cls = '';

    !empty( $dashboard ) OR $dashboard = ( 'admin' == $tplConfig->area )
                                        ? 'admin-dashboard.php'
                                        : 'dashboard.php';
@endphp

<!doctype html>
<!--[if IE 8]>
<html class="ie ie8" lang="{{ $lang }}"> <![endif]-->
<!--[if IE 9]>
<html class="ie ie9" lang="{{ $lang }}"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="{{ $lang }}"> <!--<![endif]-->
<head>
    @include('layout.legacy.blocks.head_meta')
    @yield('head')

    @if ( $tplConfig->gapi_client_id )
        <meta name="google-signin-client_id" content="{{ $tplConfig->gapi_client_id }}">
    @endif
    <!-- Favicons -->
    @include('layout.legacy.blocks.head_favicons')

    @foreach ( $tplConfig->styles as $style )
        @php( print new \App\Custom\Style( $tplConfig->getStyle( $style ) ) )
    @endforeach

    <! Head Libs -->
    @foreach ( $tplConfig->head_js as $script )
        @php( print new \App\Custom\Script( $tplConfig->getScript( $script ), true ) )
    @endforeach

    <!--[if IE]>
    <link rel="stylesheet" href="/css/ie.css">
    <![endif]-->

    @if( $tplConfig->include_analytics )
        @include('layout.legacy.blocks.head_analytics')
    @endif

    <!--[if lte IE 8]>
    <script src="{{ asset( '/schoolfinder/vendor/respond.js' ) }}"></script>
    <![endif]-->

</head>
<body>
@if( $tplConfig->include_analytics )
    @include('layout.legacy.blocks.noscript')
@endif

@include('layout.legacy.blocks.spinner')

<div id="fb-root"></div>
<div class="body">

    <header>
        <div class="container">
            <h1 class="logo">
                <a href="{{ url( '/' ) }}" alt="{{ $tplConfig->globals[ 'businessName' ] }}">
                    @if ( 'schoolfinder' == strtolower( $tplConfig->area ) )
                        <img alt="{{ $tplConfig->globals[ 'businessName' ] }} | {{ $tplConfig->globals[ 'directoryName' ] }} Logo" src="{{ $tplConfig->globals[ 'pageHeaderLogo' ] }}">
                    @else
                        <img alt="{{ $tplConfig->globals[ 'businessName' ] }} Logo" src="{{ $tplConfig->globals[ 'rootPageHeaderLogo' ] }}">
                    @endif
                </a>
            </h1>

            <nav>
                @include('layout.legacy.blocks.top-bar')
            </nav>
            <div class="social-icons"></div>
            <nav>
                @yield('mainmenu')
            </nav>
        </div>
    </header>

<div role="main" class="main">

    @yield('top_block')

    <div class="container">
        <div class="row">
            @if($left_col_cls)
                <div class="{{ $left_col_cls }}">
                    @yield('left_col')
                </div>
            @endif
            <div class="{{ $center_col_cls }}">
                @yield('center_col')
            </div>
            @if($right_col_cls)
                <div class="{{ $right_col_cls }}">
                    @yield('right_col')
                </div>
            @endif
        </div>
    </div>

</div>

@yield('modals')
</div>

<div  itemscope itemtype="https://schema.org/EducationalOrganization">
    <footer>
        <div class="container">
            <div class="row">
                <div class="footer-ribon">
                    <span>@lang( 'pages.footer_ribon', [ 'company' => $tplConfig->globals[ 'businessName' ] ] )</span>
                </div>
                @yield('footer', '')
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container">
                <div class="row">
                    <div class="{{ $copyright_span }}">
                        <p>@lang( 'pages.copyright', [ 'date' => $tplConfig->globals[ 'copyrightYear' ], 'company' => $tplConfig->globals[ 'businessName' ] ] )</p>
                    </div>
                    <div class="{{ $bottom_menu_span }}">
                        @yield('bottom_menu', '')
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>


<!-- Libs -->
@yield('js_libs', '')

@foreach ( $tplConfig->scripts as $script )
    @php( print new \App\Custom\Script( $tplConfig->getScript( $script ) ) )
@endforeach

@if ( $tplConfig->footer_script )
<script>
    {!! $tplConfig->footer_script !!}
</script>
@endif

@yield('xtras', '')

</body>
</html>