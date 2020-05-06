@php
    $footer_col_cls = 'span3';
    $address = config( 'address' );
@endphp

<div class="{{ $footer_col_cls }}">
    <h4>@lang( 'pages.headers.live_lessons' )</h4>
    <ul>
        @foreach( config( 'legacy.languages_favourite' ) as $language )
            <li><a href="{{ url( strtolower( $language ) ) }}"
                   title="@lang( 'pages.lessons.online', [ 'lang' => __( "pages.language.$language" ) ] )">@lang( 'pages.lessons.language', [ 'lang' => __( "pages.language.$language" ) ] )</a></li>
        @endforeach

        <li><a href="{{ url( '/language-lessons.php' ) }}"
               title="@lang( 'pages.language.other' )">@lang( 'pages.language.other' )</a></li>
    </ul>
</div>

<div class="{{ $footer_col_cls }}">
    <h4>@lang( 'pages.headers.resources' )</h4>
    <ul>
        @foreach( config( 'legacy.resourses' ) as $uri => $resourse )
            <li><a href="{{ url( $uri ) }}" title="@lang( $resourse[ 1 ] )">@lang( $resourse[ 0 ] )</a></li>
        @endforeach
    </ul>
</div>


<div class="{{ $footer_col_cls }}">
    <h4>@lang( 'pages.headers.contact_us' )</h4>
    <ul class="contact">
        <li><p><i class="icon icon-envelope-alt"></i> <strong>@lang( 'pages.labels.email' ):</strong>
                <a href="mailto:{{ $tplConfig->globals[ 'mainEmail' ] }}" itemprop="email">{{ $tplConfig->globals[ 'mainEmail' ] }}</a></p></li>
        <li><p><i class="icon icon-skype"></i> <strong>@lang( 'pages.labels.skype' ):</strong>
                <a href="skype:{{ $tplConfig->globals[ 'skype' ] }}" itemprop="skype">{{ $tplConfig->globals[ 'skype' ] }}</a></p></li>
        <li><p><i class="icon icon-phone"></i> <strong>@lang( 'pages.labels.phone_us' ):</strong>
                <span itemprop="telephone">{{ $tplConfig->globals[ 'phone_us' ] }}</span></p></li>
        <li><p><i class="icon icon-phone"></i> <strong>@lang( 'pages.labels.phone_mexico' ):</strong>
                <span itemprop="telephone">{{ $tplConfig->globals[ 'phone_mexico' ] }}</span></p></li>
    </ul>

</div>

<div class="{{ $footer_col_cls }}">
    <h4>@lang( 'pages.headers.or_visit_us' )</h4>
    <div class="contact" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
        <ul class="contact">
            <li><p><i class="icon icon-map-marker"></i> <strong>@lang( 'pages.labels.offices_us' ):</strong>
                    <span itemprop="streetAddress">{{ $address[ 'street' ] }}</span>,
                    <span itemprop="addressLocality">{{ $address[ 'city' ] }}</span>,
                    <span itemprop="addressRegion">{{ $address[ 'state' ] }}</span>
                    <span itemprop="postalCode">{{ $address[ 'postalCode' ] }}</span>,
                    <span itemprop="addressRegion">{{ $address[ 'country' ] }}</span></p></li>

            <li><p><i class="icon icon-map-marker"></i> <strong>@lang( 'pages.labels.offices_latin' ):</strong> {{ $address[ 'office_mexico' ] }}</p></li>
        </ul>
    </div>
    @include( 'layout.legacy.blocks.footer.social_icons' )
</div>