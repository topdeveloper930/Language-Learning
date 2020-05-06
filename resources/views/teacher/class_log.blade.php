@extends('layout.zedalabs.teacher')

@php
    $tplConfig->page_meta_title = trans('teacher_class_log.student_class_log', ['student' => $student->accost()]);
    $tplConfig->current_menu = 'students';
@endphp

@section('head')

    @parent

    <script>
        var selectedCourse = '{{ $selectedCourse }}',
            numStudents = {{ $numStudents }};
    </script>
@endsection

@push('dashboard_subtitle')
    <p class="dashboard-subtitle shorten" data-length="200">@lang('teacher_class_log.please_fillout')</p>
@endpush

@section('main')
    <main>

        <div id="root" class="container" v-cloak>

            @include('layout.zedalabs.widgets.dashboard_header', ['role' => 'teachers', 'dashboard_header' => trans('teacher_class_log.class_log', ['student' => $student->accost()])])

            <div v-if="creditedCourses.length">
                <form @submit.prevent="logClass" class="columns">
                    <div class="form-group column col-12" v-if="creditedCourses.length === 1">
                        <label>@lang('teacher_class_log.course')</label>
                        <select disabled>
                            <option value="0" v-text="optText(0)"></option>
                        </select>
                    </div>
                    <div class="form-group column col-12" v-else>
                        <label for="course">@lang('teacher_class_log.course')</label>
                        <select id="course" v-model="indx">
                            <option v-for="(course, i) in creditedCourses" :value="i" v-text="optText(i)"></option>
                        </select>
                    </div>

                    <div class="column col-6 col-12-md">
                        <label for="classDate">@lang('teacher_class_log.date')</label>
                        <input type="date" id="classDate" v-model="classDate" required>
                    </div>

                    <div class="column col-6 col-12-md">
                        <label for="date">@lang('teacher_class_log.length')</label>
                        <select id="course" v-model="classLength">
                            <option v-for="l in lessonsLengths" :value="l" v-text="lessons[l]"></option>
                        </select>
                    </div>

                    <div class="column col-12">
                        <label for="whatWasStudied">@lang('teacher_class_log.class_notes')  <a href="#" class="badge" @click.prevent="whatWasStudied = '{{ __('teacher_class_log.no_show_note') }}'">@lang('teacher_class_log.no_show')</a></label>
                        <div class="maxlength-wrap">
                            <textarea id="whatWasStudied" v-model="whatWasStudied" minlength="150" maxlength="1000" rows="3" required></textarea>
                            <span :class="{'maxlength-count': true, textarea: true, 'limit-reached': whatWasStudied.length < 150}" v-text="'150/' + whatWasStudied.length"></span>
                        </div>
                    </div>

                    <div class="column col-12">
                        <label for="internalNotes">@lang('teacher_class_log.teacher_notes')</label>
                        <textarea id="internalNotes" maxlength="1000" rows="3"></textarea>
                    </div>

                    <div class="column col-12 text-align-center">
                        <button type="submit" class="button primary">@lang('teacher_class_log.log_class')</button>
                    </div>
                </form>
            </div>
            <div v-else-if="courses" class="field-invalid-label">
                @lang('teacher_class_log.student_has_no_credits', ['student' => $student->accost()])
            </div>

        </div> <!--div#container-->

    </main>

@endsection