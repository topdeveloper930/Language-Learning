<footer id="footer" class="dark">

    <div class="footer-primary">
        <div class="container">
            <nav class="footer-navigation">
                <div class="columns">
                    <div class="column col-3 col-6-md">
                        <span class="navigation-label">Live Lingua</span>
                        <a href="{{ route('language', ['language' => ($isDefault ? '' : $language), 'controller' => 'lessons']) }}">@lang('navs.lessons')</a>
                        <a href="{{ route('language', ['language' => ($isDefault ? '' : $language), 'controller' => 'costs']) }}">@lang('navs.pricing')</a>
                        <a href="{{ route('language', ['language' => ($isDefault ? '' : $language), 'controller' => 'tutors']) }}">@lang('navs.tutors')</a>
                        <a href="{{ route('page', ['controller' => 'language-lessons']) }}">@lang('navs.other_languages')</a>
                    </div>
                    <div class="column col-3 col-6-md">
                        <span class="navigation-label">@lang('navs.free_resources')</span>
                        <a href="{{ route('page', ['controller' => 'verbs']) }}">@lang('navs.spanish_verbs')</a>
                        <a href="{{ route('language', ['language' => ($isDefault ? '' : $language), 'controller' => 'courses']) }}">@lang('navs.courses')</a>
                        <a href="{{ url('blog/') }}">@lang('navs.blog')</a>
                        <a href="{{ route('page', ['controller' => 'quiz']) }}">@lang('navs.quiz')</a>
                    </div>
                    <div class="column col-3 col-6-md">
                        <span class="navigation-label">@lang('navs.get_started')</span>
                        <a href="{{ route('page', ['controller' => 'trial-lesson']) }}">@lang('navs.try_free')</a>
                        <a href="{{ route('page', ['controller' => 'jobs']) }}">@lang('navs.work_with_us')</a>
                        <a href="{{ route('commonLogin') }}">@lang('navs.login')</a>
                        <a href="{{ route('page', ['controller' => 'faq']) }}">@lang('navs.faq')</a>
                    </div>
                    <div class="column col-3 col-6-md">
                        <span class="navigation-label">@lang('navs.more')</span>
                        <a href="{{ route('page', ['controller' => 'contact']) }}">@lang('navs.contact')</a>
                        <a href="{{ route('page', ['controller' => 'gift-cards']) }}">@lang('navs.gift_cards')</a>
                        <a href="{{ route('page', ['controller' => 'affiliates']) }}">@lang('navs.affiliate_program')</a>
                        <a href="{{ route('page', ['controller' => 'staff']) }}">@lang('navs.staff')</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    @include('layout.zedalabs.blocks.footer_copyright')
</footer>