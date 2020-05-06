@php
    !empty( $left_col_cls ) OR $left_col_cls = 'span2';
    !empty( $center_col_cls ) OR $center_col_cls = 'span10';
@endphp

@extends('layout.legacy')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('mainmenu')
    @include('layout.legacy.blocks.widgets.admin_mainmenu')
@endsection

@section('top_block')
    @include('layout.legacy.blocks.page-top')
@endsection

@section('left_col')
    <aside class="sidebar">
        <h4>@lang( 'pages.Dashboard' )</h4>
        @include('layout.legacy.blocks.widgets.admin-sidebar')
        @yield('sidebar')
    </aside>
@endsection

@section('center_col')
    Nothing here. To override this section.
@endsection

@section('modals')
{{--    @include('layout.legacy.blocks.mod_errors')--}}
@endsection

@section('footer')
    {{-- Consider something more usefull for admin? --}}
    @include('layout.legacy.blocks.footer.with_resourses')
@endsection