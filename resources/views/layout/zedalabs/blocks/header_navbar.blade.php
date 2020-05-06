@php
    use Illuminate\Support\Facades\Auth;

    if( !isset( $user ) AND request()->hasSession() ) {
    	if( Auth::guard('student')->check() )
            $user = Auth::guard('student')->user();
        elseif( Auth::guard('teacher')->check() )
            $user = Auth::guard('teacher')->user();
        elseif( Auth::guard('admin')->check() )
            $user = Auth::guard('admin')->user();
        else
            $user = null;
    }
@endphp

<header class="navbar">
    <div class="container">
        <div class="navbar-section">
            <a href="{{ url('/') }}" class="logo">
                <img src="{!! asset('/public/images/logo.svg') !!}" alt="{{ config('legacy.globals.businessName') }}">
            </a>
        </div>
        <div class="navbar-section centered">
            <nav class="hide-lg">
                <ul>
                    @foreach (config('navmenus.guest', []) as $alias => $item)
                        <li><a href="{{ route('language', ['language' => ('our-story' == $alias OR $isDefault) ? '' : $language, 'controller' => $alias]) }}" class="{{ ( $tplConfig->current_menu == $alias ) ? 'active' : '' }}">@lang($item)</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
        <div class="navbar-section">
            <nav class="hide-lg">
                <ul>
                    @if(!$user)
                        <li><a href="{{ route('login', [ 'role' => 'auth' ]) }}">@lang('navs.login')</a></li>
                        <li><a href="{{ route('page', ['controller' => 'trial-lesson']) }}" class="button primary">@lang('navs.get_started')</a></li>
                    @else
                        <li><a href="{{ route($user->getArea(), [ 'controller' => 'dashboard' ]) }}">@lang('navs.dashboard')</a></li>
                        <li><a href="{{ route('logout', ['role' => $user->getArea()]) }}" class="button secondary">@lang('navs.logout')</a></li>
                    @endif
                </ul>
            </nav>

            <a href="#mobile-menu" class="fal fa-bars show-lg"></a>
        </div>
    </div>
</header>