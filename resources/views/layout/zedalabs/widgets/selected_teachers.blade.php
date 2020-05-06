<header class="section-header position-centered">
    <h2 class="section-title">Learn from a qualified native-speaking tutor who truly cares about your goals</h2>
</header>

<div class="columns align-stretch">
    @foreach($teachers as $teacher)
    <div class="column col-3 col-6-md col-12-sm">
        <a href="{{ route('tutor', ['language' => ($isDefault) ? '' : $language, 'name' => strtolower(str_replace(' ', '-', $teacher->fullName())), 'id' => $teacher->getPK() ]) }}" class="card profile">
            <img src="{{ asset($teacher->profileImage) }}" alt="@lang('lessons.teacher_alt', ['language' => $language, 'name' => $teacher->accost()])" class="profile-avatar">
            <span class="chip language"><img src="{{ asset( sprintf('/public/images/flags/svg/%s.svg', $teacher->originCountry->code)) }}"> {{ $teacher->countryOrigin }}</span>
            <h4 class="card-title">{{ $teacher->fullName() }}</h4>
            <p class="card-subtitle">{{ substr($teacher->teacherIntroduction, 0, 100) }}&hellip;</p>
        </a>
    </div>
    @endforeach
</div>

<div class="cta-buttons text-align-center mt-xl">
    <a href="{{ $isDefault ? route('page', ['controller' => 'tutors']) : route('language', ['language' => $language, 'controller' => 'tutors']) }}" class="button secondary">Meet our tutors</a><br>
    <span class="button-hint">{{ trans_choice( 'lessons.speaking_countries', $countriesCnt, ['language' => $language ]) }}</span>
</div>