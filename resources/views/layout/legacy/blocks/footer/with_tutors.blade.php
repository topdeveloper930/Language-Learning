@php
    $footer_col_cls = 'span3';
    $languages = config( 'legacy.languages' );
@endphp
<div class="{{ $footer_col_cls }}">
    <h4>@lang( 'pages.language.lessons' )</h4>
    <ul>
        @foreach( $languages as $language )
            <li><a href="{{ url( strtolower( $language ) ) }}"
                   title="@lang( 'pages.skype_lessons', [ 'lang' => __( $language ) ] )">@lang( 'pages.lang_lessons', [ 'lang' => __( $language ) ] )</a></li>
        @endforeach
    </ul>
</div>
<div class="{{ $footer_col_cls }}">
    <h4>@lang( 'pages.headers.1_1' )</h4>
    <ul>
        @foreach( $languages as $language )
            <li><a href="{{ url( strtolower( $language ) . '/tutors/' ) }}"
                   title="@lang( 'pages.tutors_online', [ 'lang' => __( $language ) ] )">@lang( 'pages.lang_tutors', [ 'lang' => __( $language ) ] )</a></li>
        @endforeach
    </ul>
</div>


<div class="{{ $footer_col_cls }}">
    <h4>@lang( 'pages.headers.online_courses' )</h4>
    <ul>
        @foreach( $languages as $language )
            <li><a href="{{ url( strtolower( $language ) . '/courses/' ) }}"
                   title="@lang( 'pages.courses_online', [ 'lang' => __( $language ) ] )">@lang( 'pages.learn_lang', [ 'lang' => __( $language ) ] )</a></li>
        @endforeach
    </ul>

</div>
<div class="{{ $footer_col_cls }}">
    <h4>@lang( 'pages.headers.contact_us' )</h4>
    <div class="contact">
        <ul class="contact">
            <li><p><i class="icon icon-map-marker"></i> <strong>U.S. Offices:</strong> <span itemprop="streetAddress">90 Canal Street, 4th Floor</span>, <span itemprop="addressLocality">Boston</span>, <span itemprop="addressRegion">MA</span> <span itemprop="postalCode">02114</span>, <span itemprop="addressRegion">USA</span></p></li>
            <li><p><i class="icon icon-map-marker"></i> <strong>Latin America Offices:</strong> Damian Carmona 2-A, Querétaro, QTO, México</p></li>

            <li><p><i class="icon icon-envelope"></i> <strong>Email:</strong> <a href="mailto:{{ $tplConfig->globals[ 'mainEmail' ] }}" itemprop="email">{{ $tplConfig->globals[ 'mainEmail' ] }}</a></p></li>
            <li><p><i class="icon icon-skype"></i> <strong>Skype:</strong> <a href="skype:live.lingua?add" itemprop="skype">live.lingua</a></p></li>
            <li><p><i class="icon icon-phone"></i> <strong>U.S. Phone:</strong> <span itemprop="telephone">+1 (339) 499-4377</span></p></li>
            <li><p><i class="icon icon-phone"></i> <strong>Mexico Phone:</strong> <span itemprop="telephone">+ 52 (442) 171-1306</span></p></li>
        </ul>
    </div>
</div>