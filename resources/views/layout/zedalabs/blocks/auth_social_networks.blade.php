@push('scripts')
    <script src="{{ asset('public/js/sn_authentication.js') }}"></script>
@endpush

<div id="sn_auth" class="form-group" style="margin-top: calc(0.5rem + 1vw);">
    <div class="columns mt-xs">
        @foreach(config('auth.social_networks') as $net => $i)
            @if( $i )
            <div class="column">
                <a href="#" class="button sn-link {{ $net }}"><i class="fab {{ ($i === true) ? 'fa-' . $net : $i }}"></i> @lang("auth.$net")</a>
            </div>
            @endif
        @endforeach
    </div>
</div>