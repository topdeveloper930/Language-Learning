@extends('layout.legacy')

@php
    $center_col_cls = 'span12 text-center';
    $tplConfig->area = request()->route( 'role' );
    $tplConfig->page_title = __( 'pages.auth.pass_reset' );
    $tplConfig->current_menu = __( 'pages.menu.login' );
    $tplConfig->page_meta_title = __( 'pages.auth.pass_reset' );
    $role = str_singular( $tplConfig->area );
@endphp

@section('top_block')
    @include('layout.legacy.blocks.page-top')
@endsection

@section('center_col')
    <h3>@lang( 'pages.auth.reset_your_pass', [ 'role' => __( "pages.$role.$role" ) ] )</h3>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <!-- if there are login errors, show them here -->
       @include( 'layout.legacy.blocks.alert_error', [ 'err_message' => __( 'pages.auth.input_error' ) ] )
    @endif

    <form method="POST" action="{{ route('reset') }}">
        {{ csrf_field() }}

        <input type="hidden" name="area" value="{{ $tplConfig->area }}">
        <input type="hidden" name="token" value="{{ request()->route( 'token' ) }}">
        <input type="hidden" name="email" value="{{ request()->route( 'email' ) }}">


        <div class="row controls">
            <div class="span12 control-group">
                <label for="password">@lang( 'auth.password' )</label>
                <input type="password" value="" class="span4" name="password" id="password" required>
            </div>
        </div>

        <div class="row controls">
            <div class="span12 control-group">
                <label for="password_confirmation">@lang( 'auth.confirm' )</label>
                <input type="password" value="" class="span4" name="password_confirmation" id="password_confirmation" required>
            </div>
        </div>

        <div class="btn-toolbar">
            <button type="submit" class="btn btn-primary btn-large">
                @lang( 'auth.reset_btn' )
            </button>
        </div>
    </form>
@endsection

@section('modals')
    @include('layout.legacy.blocks.mod_errors')
@endsection

@section('footer')
    @include('layout.legacy.blocks.footer.with_resourses')
@endsection

@section('bottom_menu')
    @include( 'layout.legacy.blocks.footer.sub_menu', [ 'isHome' => true ] )
@endsection

@section('js_libs')
    @include('layout.legacy.blocks.js_libraries')
@endsection