<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ ( !is_null( $tplConfig->page_meta_title ) ) ? $tplConfig->page_meta_title : __( $tplConfig->page_title ) }}</title>
<meta name="description" content="{{ $tplConfig->page_meta_description }}">
<meta name="author" content="{{ $tplConfig->globals[ 'businessName' ] }} - {{ $tplConfig->globals[ 'directoryName' ] }}">
<meta name="copyright" content="{{ $tplConfig->globals[ 'businessName' ]  }} - {{ $tplConfig->globals[ 'directoryName' ] }}">