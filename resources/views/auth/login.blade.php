@if( $tplConfig->area AND 'auth' != $tplConfig->area )
    @include('auth.zedalabs.login')
@else
    @include('auth.zedalabs.commonLogin')
@endif
