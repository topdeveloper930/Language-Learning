@extends('layout.zedalabs.guest')

@php
    $tplConfig->page_meta_title = __('tutor.ll_teacher', [ 'tutor' => $tutor->accost() ]);

    $tplConfig->current_menu = 'tutors';
    $teaching_since = __('tutor.teaching_since', ['language' => $tutor->languagesTaught[0], 'year' => $tutor->startedTeaching ]);
    !empty( $tplConfig->page_meta_description ) OR  $tplConfig->page_meta_description = strip_tags($tplConfig->page_meta_title . '. ' . $teaching_since);
@endphp

@section('main')

<section class="mb-xxl">
    <div class="container">

        <div class="columns">

            <aside class="column col-3 col-12-md" style="padding-right:3rem;">
                <div class="card profile mb-sm">
                    <img class="profile-avatar" src="{{ asset( $tutor->getProfileImage() ) }}">
                    <h2 class="card-title">{{ $tutor->fullname() }}</h2>
                    <span class="chip language"><img src="{{ asset("public/images/flags/svg/{$tutor->originCountry->code}.svg") }}"> {{ $tutor->originCountry->name }}</span>
                    <p class="card-subtitle">@lang('tutor.timezone_is', ['tz' => $tutor->timezone ])</p>
                </div>
                <a href="{{ route('page', ['controller' => 'trial-lesson', 'id' => $tutor->teacherID]) }}" class="button primary display-block">@lang('tutor.trial', ['tutor' => $tutor->firstName ])</a>
            </aside>

            <div class="column col-9 col-12-md">
                <nav class="tab-navigation mb-md">
                    @foreach( __('tutor.tabs') as $alias => $title )
                        <a href="#{{ $alias }}"{{ ( 'teacherIntroduction' == $alias ) ? ' class=active' : '' }}>{{ $title }}</a>
                        @push('tabs')
                            <div id="{{ $alias }}"{{ ( 'teacherIntroduction' == $alias ) ? ' class=active' : ' hidden=1' }}>
                                @if( 'teaching-options' == $alias )
                                    @if( count($tutor->coursesTaught) )
                                        <h3>@lang('tutor.courses_i_teach')</h3>
                                        @foreach( $tutor->coursesTaught as $course )
                                            <a href="{{ route('language', ['language' => $language, 'controller' => trim(str_ireplace([$language, ' '], ['', '-'], strtolower($course)), '-')]) }}" class="chip">{{ $course }}</a>
                                        @endforeach
                                    @endif

                                    <h3>@lang('tutor.age_ranges')</h3>
                                    @if( 5 == count( $tutor->agesTaught ) )
                                        <p>@lang('tutor.all_ages')</p>
                                    @else
                                        @foreach( $tutor->agesTaught as $age )
                                            <a href="#" class="chip">@lang( 'tutor.ages.' . strtolower( $age ) )</a>
                                        @endforeach
                                    @endif
                                @else
                                    @dboutput( $tutor->{$alias} )
                                @endif
                            </div>
                        @endpush
                    @endforeach
                </nav>

                @stack('tabs')

            </div>

        </div>

    </div>
</section>

<section class="mb-xxl">
    <div class="container">
        <div class="columns stats">
            <div class="column col-4 col-12-md">
                <div class="stat">
                    <p class="stat-label">{!! $teaching_since !!}</p>
                </div>
            </div>
            <div class="column col-4 col-12-md">
                <div class="stat">
                    <i class="fact-icon"></i>
                    <p class="stat-label">@lang('tutor.speaks', [ 'count' => $tutor->totalSpokenLanguages(), 'spoken' => $tutor->allSpokenLanguages() ])</p>
                </div>
            </div>
            <div class="column col-4 col-12-md">
                <div class="stat">
                    <p class="stat-label">@lang('tutor.visited', [ 'countries' => $tutor->countriesVisited ])</p>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="mb-xxl">
    <div class="container">
        <header class="section-header position-centered">
            <h2 class="section-title">@lang('tutor.teach_to_help', ['language' => $language])</h2>
        </header>
        <div class="columns align-stretch">
            @foreach( $purposes as $purpose)
            <div class="column col-6 col-12-md">
                <div class="card card-sm shadow">
                    <i class="fad fa-check-circle card-icon"></i>
                    <div class="card-body">
                        <h3 class="h5">@lang("tutor.purposes.$purpose", ['language' => $language])</h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@if( count( $testimonials ))
<section class="mb-xxl pv-xxl section-fill">
    <div class="slider-container">
        <div class="slider container lg">
            @foreach($testimonials as $testimonial)
            <blockquote class="testimonial-feature">
                <p>
                    "{{ $testimonial->comment }}"
                    <cite><span class="text-ribbon">{{ $testimonial->firstName }} {{ $testimonial->lastName }} - {{ $testimonial->profession }}</span></cite>
                </p>
            </blockquote>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('layout.zedalabs.blocks.see_other_tutors')

<div class="container">
    <div class="cta-section primary pv-xl text-align-center">
        <div class="container lg">
            <h2 class="cta-title">@lang('tutor.ready_to_begin')</h2>
            <div class="cta-buttons">
                <a href="{{ route('page', ['controller' => 'trial-lesson', 'id' => $tutor->teacherID]) }}" class="button primary">@lang('tutor.try_lesson_with', ['tutor' => $tutor->firstName])</a><br>
                <span class="button-hint">@lang('tutor.no_credit_card')</span>
            </div>
        </div>
    </div>
</div>

@endsection