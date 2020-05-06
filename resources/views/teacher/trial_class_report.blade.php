@extends('layout.zedalabs.teacher')

@php
    $tplConfig->page_meta_title = trans('teacher_trial_class_report.report');
    $tplConfig->current_menu = 'dashboard';
@endphp

@section('head')
    @parent

    <script>
        var dashboard_url = '{{ route('teachers', ['controller' => 'dashboard']) }}',
            trialId = {{ $trial->trialClass2Teachers }};
    </script>
@endsection

@push( 'extra_item_before')
    <a href="#no-show" class="button caution" @click.prevent="noShowMod = true">@lang('teacher_trial_class_report.report_no_show')</a>
@endpush

@push('dashboard_subtitle')
    <p class="dashboard-subtitle shorten" data-length="200">@lang('teacher_trial_class_report.please_fillout', ['levels_url' => route('page', ['controller' => 'language-levels'])])</p>
@endpush

@section('main')
    <main>

        <div id="root" class="container" v-cloak>

            @include('layout.zedalabs.widgets.dashboard_header', ['role' => 'teachers', 'dashboard_header' => trans('teacher_trial_class_report.trial_class_report', ['student' => $trial->trialClassLog->accost(), 'date' => date('M&\n\b\s\p;d', strtotime($trial->teacherClassDate))])])

            <form method="post" action="">
                {{ csrf_field() }}

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
                            <label for="comments">@lang('teacher_trial_class_report.proposed_plan')</label>
                            <textarea id="comments"
                                      name="comments"
                                      maxlength="5000"
                                      rows="5" {{ ($errors->has('comments')) ? ' class=invalid' : '' }}
                                      @keyup="removeInvalid"
                            >{{ old('comments') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="button primary">@lang('teacher_trial_class_report.save')</button>
                </div>
            </form>

            <confirm-modal ok="@lang('teacher_dashboard.confirm_noshow')"
                           no="@lang('teacher_dashboard.cancel')"
                           header="@lang('teacher_dashboard.js.trial_header', ['student' => $trial->trialClassLog->accost(), 'date' => substr($trial->teacherClassDate, 0, strrpos($trial->teacherClassDate, ':') )])"
                           :visible="noShowMod"
                           ok-cls="caution"
                           @close="resetNoShow"
                           @confirm="setNoShow"
            >
                <p>@lang('teacher_dashboard.check_boxes')</p>
                <fieldset class="form-group ruled mt-sm">
                    <p>
                        <label><input type="checkbox" v-model="iWaited" @change="iWaitedErr = 0"> @lang('teacher_dashboard.i_waited')</label>
                        <span class="field-invalid-label" v-if="iWaitedErr">@lang('teacher_dashboard.required')</span>
                    </p>
                    <p>
                        <label><input type="checkbox" v-model="iLeftMessage" @change="iLeftMessageErr = 0"> @lang('teacher_dashboard.i_left_message')</label>
                        <span class="field-invalid-label" v-if="iLeftMessageErr">@lang('teacher_dashboard.required')</span>
                    </p>
                    <i v-if="loading" class="fa fa-spinner fa-spin"></i>
                </fieldset>
            </confirm-modal>
        </div> <!--div#container-->

    </main>

@endsection