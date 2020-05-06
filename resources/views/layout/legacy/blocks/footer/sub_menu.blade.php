@php( isset( $isHome ) OR $isHome = \Illuminate\Support\Facades\Request::is('/') )
<nav id="sub-menu">
    <ul>
        @if( $isHome )
            <li><a href="{{ url( '/teachers/' ) }}" rel="nofollow">@lang( 'pages.menu.teacher' )</a></li>
            <li><a href="{{ url( '/jobs.php' ) }}" rel="nofollow">@lang( 'pages.menu.jobs' )</a></li>
        @endif

        <li><a href="{{ url( '/terms-conditions.php' ) }}" rel="nofollow">@lang( 'pages.menu.terms' )</a></li>
        <li><a href="{{ url( '/privacy-policy.php' ) }}" rel="nofollow">@lang( 'pages.menu.policy' )</a></li>

		@if( $isHome )
        <li><a href="{{ url( '/es/' ) }}" title="@lang( 'pages.title.es' )">@lang( 'pages.menu.es' )</a></li>
        @endif
    </ul>
</nav>