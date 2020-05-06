@php
    if( !isset($role) ) {
        $role = str_singular( $tplConfig->area );
        $role = __( "auth.$role");
    }

    $role = str_singular( $tplConfig->area );
    $fCls = ($errors->any()) ? 'class=shaking' : '';
@endphp
        <!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>

    <meta charset="utf-8">

    <meta name="title" content="" />
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">

    <title>@lang('auth.reset_title')</title>

    <!-- Favicons -->
    @include('layout.zedalabs.blocks.head_favicons')

    <link rel="stylesheet" href="{!! asset('/public/css/framework.css') !!}">
</head>
<body>

<div class="modal-page">

    <a href="{{ route('login', ['role' => $tplConfig->area]) }}" class="action left"><i class="far fa-long-arrow-left"></i> @lang('auth.login')</a>

    <div class="modal-page-box">
        <div id="students">
            <header>
                <!--<a href="/pages-website/home.php" class="logo"><img src="/library/images/logo-icon.svg"></a>-->
                <h1 class="modal-title">@lang('auth.reset_password', [ 'role' => $role ])</h1>
            </header>
            <form method="POST" action="{{ route('reset') }}"{{ $fCls }}>
                {{ csrf_field() }}

                <input type="hidden" name="area" value="{{ $tplConfig->area }}">
                <input type="hidden" name="token" value="{{ request()->route( 'token' ) }}">
                <input type="hidden" name="email" value="{{ request()->route( 'email' ) }}">

                <div class="form-group">
                    <input type="password" value="" name="password" id="password" autocomplete="off" required>
                    <label for="password">@lang( 'auth.password' )</label>
                </div>

                <div class="form-group">
                    <input type="password" value="" name="password_confirmation" id="password_confirmation" autocomplete="off" required>
                    <label for="password_confirmation">@lang( 'auth.confirm' )</label>
                </div>
                <div class="form-group">
                    <button class="button primary" type="submit">@lang('auth.reset_btn')</button>
                </div>
            </form>
        </div>
    </div>

</div>

@if($errors->any())
    <div class="toaster">
        @foreach( $errors->all() as $error )
            <div class="toast caution">{{ $error }}</div>
        @endforeach
    </div>
@endif
</body>
</html>