<!-- Facebook Pixel Code -->
<script>
    var ll_fb_api = {!! json_encode( $tplConfig->fb_api ) !!},
    site_base_url = '{{ config('app.url') }}';

    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');

    fbq('init', '{{ $tplConfig->fb_api[ 'id' ] }}');
    fbq('track', "PageView");
</script>
<!-- End Facebook Pixel Code -->