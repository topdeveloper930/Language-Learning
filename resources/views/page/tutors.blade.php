@extends('layout.zedalabs.guest')

@php
    $tplConfig->page_meta_title = __( 'tutors.meta_title', ['language' => $language] );
    $tplConfig->page_meta_description = __('tutors.meta_description', ['language' => $language]);
    $tplConfig->current_menu = 'tutors';
    $tutorUrl = route('tutor', ['language' => ($isDefault ? '' : $language), 'name' => ':n', 'id' => ':id']);
@endphp

@section('head')
    @parent

    <script>
        var filters = {!! json_encode( $filters ) !!},
            language = "{{ $language }}",
            tutorUrl = "{{ $tutorUrl }}";
    </script>
@endsection

@section('main')

<section class="mb-xl">
    <div class="container lg">
        <header class="page-header position-centered">
            <h1 class="page-title">@lang('tutors.our_tutors', ['language' => $language])</h1>
            <p class="page-subtitle">@lang('tutors.feel_free_to')</p>
        </header>
    </div>
</section>

<div id="root" v-cloak>

    <div class="hidden-menu left" id="faq-categories">
        <nav class="nav-menu">
            <form method="post" action="" class="mb-md">
                <span class="nav-label">@lang('tutors.search')</span>
                <input type="text" placeholder="@lang('tutors.search_tutors')" v-model="searchStr">

                <span class="nav-label">@lang('tutors.location')</span>

                <label v-for="c in locations"><input type="checkbox" :value="c" v-model="filters.location"> @{{ c }}</label>

                <span class="nav-label">@lang('tutors.languages_spoken')</span>

                <label v-for="l in languagesSpoken"><input type="checkbox" :value="l" v-model="filters.lang"> @{{ l }}</label>

                <span class="nav-label">@lang('tutors.courses_taught')</span>

                <select v-model="filters.course">
                    <option value="">@lang('tutors.select')</option>
                    <option v-for="c in coursesTaught" :value="c" v-text="c"></option>
                </select>
            </form>
        </nav>
    </div>

    <section class="mb-xxl">
        <div class="container">

            <a href="#faq-categories" class="button secondary show-md mb-lg">@lang('tutors.search')</a>

            <div class="columns">

                <aside class="column col-3 col-12-sm sticky-sidebar hide-md">
                    <nav class="nav-menu">
                        <h4 class="mb-md">@lang('tutors.filters')</h4>
                        <form method="post" action="" class="mb-md">
                            <span class="nav-label">@lang('tutors.search')</span>
                            <input type="text" placeholder="@lang('tutors.search_tutors')" v-model="searchStr">
                            <span class="nav-label">@lang('tutors.location')</span>

                            <label v-for="c in locations"><input :value="c" type="checkbox" v-model="filters.location"> @{{ c }}</label>

                            <span class="nav-label">@lang('tutors.languages_spoken')</span>

                            <select v-model="filters.lang">
                                <option value="">@lang('tutors.select')</option>
                                <option v-for="c in languagesSpoken" :value="c" v-text="c"></option>
                            </select>

                            <span class="nav-label">@lang('tutors.courses_taught')</span>

                            <select v-model="filters.course">
                                <option value="">@lang('tutors.select')</option>
                                <option v-for="c in coursesTaught" :value="c" v-text="c"></option>
                            </select>
                        </form>
                    </nav>
                </aside>

                <div id="tutors" class="column col-9 col-12-sm">
                    <div class="columns">
                        <card v-for="(teacher, index) in teachersFiltered"
                              v-if="limit == 0 || (index < limit * page && index >= (page - 1) * limit)"
                              imgcls="profile-avatar"
                              :href="teacherUrl(teacher)"
                              :imgsrc="teacher.profileImage"
                              :imgalt="teacher.firstName + ' ' + teacher.lastName"
                              :header="teacher.firstName + ' ' + teacher.lastName"
                              :no-image-stub="profileImageStub"
                              :id="'lazy' + index"
                              :country-code="teacher.countryCode"
                              :country-name="teacher.countryName"
                              linkcls="card profile"
                              profile-btn="@lang('tutors.profile')"
                        >@{{ teacher.teacherIntroduction.substr(0, 60) + '&hellip;' }}</card>
                    </div>

                    <pagination ref="pagination"
                                next="@lang('tutors.next')"
                                prev="@lang('tutors.previous')"
                                spacer="@lang('tutors.spacer')"
                                :items="teachersFiltered"
                                :limit="limit"
                                :start="page"
                                v-model="page"
                    ></pagination>

                </div>

            </div>



        </div>
    </section>
</div>

@include('page.en.tutors')

<div class="container">
    <div class="cta-section primary pv-xl text-align-center">
        <div class="container lg">
            <h2 class="cta-title">@lang('tutors.meet_tutors')</h2>
            <p class="cta-subtitle">@lang('tutors.dont_worry')</p>
            <div class="cta-buttons">
                <a href="{{ route('page', ['controller' => 'trial-lesson']) . '?lang=' . $language }}" class="button primary">@lang('tutors.try_free')</a><br>
                <span class="button-hint">@lang('tutors.no_credit_card')</span>
            </div>
        </div>
    </div>
</div>

@endsection
