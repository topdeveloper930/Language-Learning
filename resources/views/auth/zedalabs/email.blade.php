@extends('layout.zedalabs')
@php
if( !isset($role) ) {
    $role = str_singular( $tplConfig->area );
    $role = __( "auth.$role");
}
$fCls = ($errors->any()) ? 'class=shaking' : '';
@endphp

@section('main')
    <div class="modal-page">

        <a href="{{ route('login', ['role' => $tplConfig->area]) }}" class="action left"><i class="far fa-long-arrow-left"></i> @lang('auth.login')</a>

        <div class="modal-page-box">
            <div id="students">
                <header>
                    <!--<a href="/pages-website/home.php" class="logo"><img src="/library/images/logo-icon.svg"></a>-->
                    <h1 class="modal-title">@lang('auth.role_password', [ 'role' => $role ])</h1>
                </header>
                <form role="form" action="{{ url( '/auth/email' ) }}" method="post"{{ $fCls }}>
                    {{ csrf_field() }}
                    <input type="hidden" name="area" value="{{ $tplConfig->area }}">
                    <div class="form-group">
                        <input type="email" placeholder="" value="" name="email" id="email" autocomplete="off" required>
                        <label for="email">@lang( 'auth.email_reset' )</label>
                    </div>
                    <div class="form-group">
                        <button class="button primary" type="submit">@lang('auth.send_link')</button>
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
@endsection
