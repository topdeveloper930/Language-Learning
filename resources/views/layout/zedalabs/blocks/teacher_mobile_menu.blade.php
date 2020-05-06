<div class="hidden-menu left" id="mobile-menu">
    <nav class="main">
        <ul>
            @foreach (config('navmenus.teacher', []) as $alias => $item)
                <li><a href="{{ route('teachers', ['controller' => $alias ]) }}" class="{{ ( $tplConfig->current_menu == $alias ) ? 'active' : '' }}">@lang( $item )</a></li>
            @endforeach
            <li><a href="{{ route('teachers', ['controller' => 'calendar']) }}" class="button primary">@lang('navs.schedule_class')</a></li>
        </ul>
    </nav>
</div>