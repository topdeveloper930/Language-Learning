<meta charset="utf-8">

<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">

<meta name="description" content="{{ $tplConfig->page_meta_description }}">
<meta name="author" content="{{ $tplConfig->globals[ 'businessName' ] }} - {{ $tplConfig->globals[ 'directoryName' ] }}">
<meta name="copyright" content="{{ $tplConfig->globals[ 'businessName' ]  }} - {{ $tplConfig->globals[ 'directoryName' ] }}">

<title>{{ ( !is_null( $tplConfig->page_meta_title ) ) ? $tplConfig->page_meta_title : __( $tplConfig->page_title ) }}</title>