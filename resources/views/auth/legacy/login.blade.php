@extends('layout.legacy')
@php
    !empty( $left_col_cls ) OR $left_col_cls = 'span6';
    !empty( $center_col_cls ) OR $center_col_cls = 'span6';
@endphp

@php
    !empty( $dashboard ) OR $dashboard = ( 'admin' == $tplConfig->area )
                                        ? 'admin-dashboard.php'
                                        : 'dashboard.php';
@endphp


@section('top_block')
    @include('layout.legacy.blocks.page-top')
@endsection

@section('left_col')
    <h3>@lang( 'pages.headers.login' )</h3>

    {{-- if logged out user --}}
    @if( \Illuminate\Support\Facades\Session::has( 'logout' ) )
        <div class="alert">
            {!!  \Illuminate\Support\Facades\Session::get( 'logout' ) !!}
        </div>
    @endif

    {{-- if there are login errors, show them here --}}
    @if($errors->any())
    <div class="alert alert-error" id="joinError">
        {{ $errors->first('email') }}
        {{ $errors->first('password') }}
    </div>
    @endif

    <form role="form" action="{{ url( '/auth/login' ) }}" method="post">
        {{ csrf_field() }}

        <input type="hidden" name="role" value="{{ $tplConfig->area }}">

        <div class="row controls">
            <div class="span4 control-group">
                <label for="email">@lang( 'pages.labels.email' )</label>
                <input type="email" value="{{ old( 'email' ) }}" class="span4" name="email" id="email" placeholder="your@email.com" required>
            </div>
        </div>

        <div class="row controls">
            <div class="span4 control-group">
                <label for="password">@lang( 'pages.labels.password' )</label>
                <input type="password" value="" class="span4" name="password" id="password" required>
            </div>
            <div class="span2 control-group">
                <label><br></label>
                <p><a href="{{ url( 'auth/forget?area=' . $tplConfig->area ) }}">@lang( 'pages.labels.forget' )</a></p>
            </div>
        </div>

        <div class="btn-toolbar">
            <button type="submit" class="btn btn-primary btn-large">
                @lang( 'pages.auth.login_btn' )
            </button>
        </div>
    </form>
@endsection

@section('center_col')
    <img class="img-rounded" alt="Live Lingua Admin Login" style="width: 568px; height: 293px;" src="{{ asset( 'schoolfinder/img/login.png' ) }}">
@endsection

@section('modals')
{{--    @include('layout.legacy.blocks.mod_errors')--}}
@endsection

@section('footer')
    @include('layout.legacy.blocks.footer.with_resourses')
@endsection

@section('bottom_menu')
    @include( 'layout.legacy.blocks.footer.sub_menu', [ 'isHome' => true ] )
@endsection

@section('js_libs')
{{--    @include('layout.legacy.blocks.js_libraries')--}}
@endsection