@php
    $area = ( !empty( $user ) AND $user instanceof \App\Services\Auth\UserType )
            ? $user->getArea()
            : null;

    isset( $extra_class ) OR $extra_class = '';
@endphp
<div class="footer-secondary {{ $extra_class }}">
    <div class="container">
        <div class="columns">
            <div class="column col-6 col-12-md">
                <p>@lang('navs.copyright')</p>
            </div>
            <div class="column col-6 col-12-md text-align-right">
                <nav>
                    <a href="{{ route('page', ['controller' => 'privacy-policy']) }}">@lang('navs.privacy')</a>
                    <a href="{{ route('page', ['controller' => 'terms-conditions']) }}">@lang('navs.terms')</a>
                    @if( $area )
                        <a href="{{ route( 'logout', [ 'role' => $area ] ) }}">@lang('navs.logout')</a>
                    @endif
                </nav>
            </div>
        </div>
    </div>
</div>