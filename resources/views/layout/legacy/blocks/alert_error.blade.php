<div class="alert alert-error" id="joinError">
    @isset( $err_message )
    {!! $err_message !!}<br><br>
    @endisset
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>