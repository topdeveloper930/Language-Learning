<div class="hidden-menu left" id="mobile-menu">
    <nav class="main">
        <ul>
            @foreach (config('navmenus.student', []) as $alias => $item)
                <li><a href="{{ route('students', ['controller' => $alias]) }}" class="{{ ( $tplConfig->current_menu == $alias ) ? 'active' : '' }}">@lang( $item )</a></li>
            @endforeach
            <li><a href="{{ route('students', ['controller' => 'schedule-class']) }}" class="button primary">@lang('navs.schedule_class')</a></li>
        </ul>
    </nav>
</div>