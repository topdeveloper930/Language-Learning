<ul class="nav nav-pills nav-top">

    @if ( $user )

        <li>
            <a href="{{ url( $tplConfig->area . '/' . $dashboard ) }}">
                <i class="icon-globe" style="position:relative;top:-1px;"></i>{{ $user->fullName() }}
            </a>
        </li>

        <li>
            <a href="{{ route( 'logout', [ 'role' => $tplConfig->area ] ) }}">
                <i class="icon-signout" style="position:relative;top:-1px;"></i>@lang( 'pages.menu.logout' )</a>
        </li>

    @else

        <li>
            <a href="{{ route( 'login', [ 'role' => $tplConfig->area ] ) }}">
                <i class="icon-signin" style="position:relative;top:-1px;"></i>@lang( 'pages.menu.login' )
            </a>
        </li>

    @endif

</ul>