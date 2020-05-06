@extends('layout.zedalabs.teacher')

@php
    $tplConfig->page_meta_title = trans('student_evaluation.evaluation');
    $tplConfig->current_menu = 'students';
@endphp

@push('dashboard_subtitle')
    <p class="dashboard-subtitle shorten" data-length="200">@lang('student_evaluation.please_fillout', ['levels_url' => route('page', ['controller' => 'language-levels'])])</p>
@endpush

@section('main')
    <main>

        <div id="root" class="container" v-cloak>

            @include('layout.zedalabs.widgets.dashboard_header', ['role' => 'teachers', 'dashboard_header' => trans('student_evaluation.evaluation')])

            <form method="post" action="">
                {{ csrf_field() }}

                @if($assignments->count() == 1)
                    <h2>{{ $student->accost() }}, {{ ucfirst($assignments[0]->language) }} - {{ ucwords($assignments[0]->course) }}</h2>
                    <input type="hidden" name="course" value="{{ $assignments[0]->courseType }}">
                @else
                    <h2>{{ $student->accost() }}</h2>
                    <div class="columns">
                        <div class="column col-12">
                            <div class="form-group ruled">
                                <label for="course">@lang('student_evaluation.course')</label>
                                <select id="course" name="course">
                                    @foreach($assignments as $course)
                                        <option value="{{ $course->course }}"{{ ( strtolower(old('course', $courseType)) == strtolower($course->courseType)) ? 'selected' : '' }}>{{ ucfirst($course->language) }} - {{ ucwords($course->course) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="columns">
                    <div class="column col-3 col-6-md">
                        <div class="form-group">
                            <label for="speakingLevel">@lang('teacher_trial_class_report.speaking_level')</label>
                            <select name="speakingLevel"
                                    id="speakingLevel" {{ ($errors->has('speakingLevel')) ? ' class=invalid' : '' }}
                                    @change="removeInvalid"
                            >
                                <option value="">@lang('teacher_trial_class_report.choose')</option>
                                @foreach($levels as $level)
                                    <option {{ ($level->title === old('speakingLevel', '')) ? 'selected' : '' }}>{{ $level->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="column col-3 col-6-md">
                        <div class="form-group">
                            <label for="listeningLevel">@lang('teacher_trial_class_report.listening_level')</label>
                            <select name="listeningLevel"
                                    id="listeningLevel" {{ ($errors->has('listeningLevel')) ? ' class=invalid' : '' }}
                                    @change="removeInvalid"
                            >
                                <option value="">@lang('teacher_trial_class_report.choose')</option>
                                @foreach($levels as $level)
                                    <option {{ ($level->title === old('listeningLevel', '')) ? 'selected' : '' }}>{{ $level->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="column col-3 col-6-md">
                        <div class="form-group">
                            <label for="readingLevel">@lang('teacher_trial_class_report.reading_level')</label>
                            <select name="readingLevel"
                                    id="readingLevel" {{ ($errors->has('readingLevel')) ? ' class=invalid' : '' }}
                                    @change="removeInvalid"
                            >
                                <option value="">@lang('teacher_trial_class_report.choose')</option>
                                @foreach($levels as $level)
                                    <option {{ ($level->title === old('readingLevel', '')) ? 'selected' : '' }}>{{ $level->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="column col-3 col-6-md">
                        <div class="form-group">
                            <label for="writingLevel">@lang('teacher_trial_class_report.writing_level')</label>
                            <select name="writingLevel"
                                    id="writingLevel" {{ ($errors->has('writingLevel')) ? ' class=invalid' : '' }}
                                    @change="removeInvalid"
                            >
                                <option value="">@lang('teacher_trial_class_report.choose')</option>
                                @foreach($levels as $level)
                                    <option {{ ($level->title === old('writingLevel', '')) ? 'selected' : '' }}>{{ $level->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="column col-12">
                        <div class="form-group ruled">
                            <label for="comments">@lang('student_evaluation.additional_notes')</label>
                            <textarea id="comments"
                                      name="comments"
                                      maxlength="5000"
                                      rows="5" {{ ($errors->has('comments')) ? ' class=invalid' : '' }}
                                      @keyup="removeInvalid"
                            >{{ old('comments') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-sm">
                    <button type="submit" class="button primary">@lang('teacher_trial_class_report.save')</button>
                </div>
            </form>
        </div> <!--div#container-->

    </main>

@endsection