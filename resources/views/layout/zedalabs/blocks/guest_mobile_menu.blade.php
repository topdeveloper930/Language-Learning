<div class="hidden-menu left" id="mobile-menu">
    <nav class="main">
        <ul>
            @foreach (config('navmenus.guest', []) as $alias => $item)
                <li><a href="{{ route('language', ['language' => ('our-story' == $alias OR $isDefault) ? '' : $language, 'controller' => $alias]) }}" class="{{ ( $tplConfig->current_menu == $alias ) ? 'active' : '' }}">@lang($item)</a></li>
            @endforeach
            <li><a href="{{ route('login', ['role' => 'students']) }}">@lang('navs.login')</a></li>
            <li><a href="{{ route('page', ['controller' => 'trial-lesson']) }}" class="button primary">@lang('navs.free_try')</a></li>
        </ul>
    </nav>
</div>