<style>.async-hide { opacity: 0 !important} </style>
<script>
    window.dataLayer = window.dataLayer || [];
    dataLayer.push({
        'event': 'userId',
        'userId': '{{ $tplConfig->user_id  }}',
        'userType': '{{ $tplConfig->user_type  }}'
    });
    (function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
        h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
        (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
    })(window,document.documentElement,'async-hide','dataLayer',4000,
        {'{{ config( 'services.google.tag_manager_id' ) }}':true});
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ config( 'services.google.tag_manager_id' ) }}');</script>
<!-- End Google Tag Manager -->