<header class="navbar dashboard mb-lg">
    <div class="container">

        @include('layout.zedalabs.blocks.dashboard_logo')

        <div class="navbar-section centered">
            <nav class="hide-lg">
                <ul>
                    @foreach (config('navmenus.student', []) as $alias => $item)
                        <li><a href="{{ route('students', ['controller' => $alias]) }}" class="{{ ( $tplConfig->current_menu == $alias ) ? 'active' : '' }}">@lang( $item )</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <div class="navbar-section">
            <nav>
                <ul>
                    <li><a href="{{ route('students', ['controller' => 'purchase']) }}" class="button light">@lang('navs.purchase_classes')</a></li>
                    <li><a href="{{ route('students', ['controller' => 'profile']) }}" class="avatar"><img src="{!! asset( $user->profileImage ) !!}" alt=""></a></li>
                    <li><a href="#mobile-menu" class="fal fa-bars show-lg"></a></li>
                </ul>
            </nav>
        </div>

    </div>
</header>