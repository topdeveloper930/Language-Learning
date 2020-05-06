@php
    !empty( $left_col_cls ) OR $left_col_cls = 'span2';
    !empty( $center_col_cls ) OR $center_col_cls = 'span10';
@endphp

@extends('layout.legacy')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('mainmenu')
    <ul class="nav nav-pills nav-main" id="mainMenu">
        <li @if ( $tplConfig->main_menu_active == 'home' ) class="active" @endif>
            <a href="{{ url( '/' ) }}" title="{{ $tplConfig->globals[ 'businessName' ] }}" rel="nofollow">{{ __( 'pages.home' ) }}</a>
        </li>
        <li @if ( $tplConfig->main_menu_active == 'dashboard' ) class="active" @endif>
            <a href="{{ url( '/students/dashboard.php' ) }}" title="@lang( 'pages.student.dashboard' )" rel="nofollow">@lang( 'pages.student.dashboard' )</a>
        </li>
    </ul>
@endsection

@section('top_block')
    @include('layout.legacy.blocks.page-top')
@endsection

@section('left_col')
    <aside class="sidebar">
        <h4>@lang( 'pages.Dashboard' )</h4>
        @include('layout.legacy.blocks.widgets.student-sidebar')
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
    @include('layout.legacy.blocks.footer.with_resourses')
@endsection

@section('bottom_menu')
    @include( 'layout.legacy.blocks.footer.sub_menu', [ 'isHome' => false ] )
@endsection

@section('js_libs')
{{--    @include('layout.legacy.blocks.js_libraries')--}}
@endsection