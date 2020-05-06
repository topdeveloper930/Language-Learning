<section class="mb-xxl">
    <div class="container">
        <header class="section-header position-centered">
            <h2 class="section-title">@lang('navs.more_tutors', ['language' => $language])</h2>
        </header>
        <div class="columns">
            @foreach( $moreTutors as $t )
            <div class="column col-3 col-6-lg col-12-sm">
                <a href="{{ route('tutor', ['language' => ($isDefault) ? '' : $language, 'name' => str_replace(' ', '-', strtolower($t->fullname())), 'id' => $t->getPK() ] ) }}" class="card profile">
                    <img src="{{ asset( $t->getProfileImage() ) }}" class="profile-avatar">
                    <span class="chip language"><img src="{{ asset("public/images/flags/svg/{$t->originCountry->code}.svg") }}"> {{ $t->originCountry->name }}</span>
                    <h4 class="card-title">{{ $t->firstName }}</h4>
                    <p class="card-subtitle">{{ substr($t->teacherIntroduction, 0, 122) }}&hellip;</p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>