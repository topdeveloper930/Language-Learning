<section class="page-top">
    <div class="container">
        <div class="row">
            <div class="span12">
                <ul class="breadcrumb">
                    <li><a href="{{ url( '/' ) }}" title="{{ $tplConfig->globals[ 'businessName' ] }}">{{ $tplConfig->globals[ 'businessName' ] }}</a> <span class="divider">/</span></li>
                    <li class="active">{{ __( $tplConfig->current_menu ) }}</li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <h2 class="pageTitle">{{ __( $tplConfig->page_title ) }}</h2>
            </div>
        </div>
    </div>
</section>