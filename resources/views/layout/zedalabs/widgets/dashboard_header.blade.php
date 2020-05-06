<header class="dashboard-header mb-md">
    <h1 class="dashboard-title">{{ $dashboard_header }}</h1>
    <div class="dashboard-actions">
        @stack( 'extra_item_before')
        <a href="{{ route( 'logout', [ 'role' => $role ] ) }}" class="button">@lang('navs.logout')</a>
        @stack( 'extra_item_after')
    </div>
    @stack( 'dashboard_subtitle')
</header>