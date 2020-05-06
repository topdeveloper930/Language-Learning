<div class="language-top-menu hide-xs">
    <div class="container">
        <nav><ul>
            @foreach( config('legacy.top_languages_menu') as $alias )
            <li>
                <a href="{{ route('page', ['controller' => ('spanish' == $alias) ? '' : $alias]) }}"
                   title="@lang('navs.top_languages_menu_titles.' . $alias)"
                   class="{{ ( $language == $alias ) ? 'active' : '' }}"
                >@lang('navs.top_languages_menu.' . $alias)</a>
            </li>
            @endforeach
        </ul></nav>
    </div>
</div>