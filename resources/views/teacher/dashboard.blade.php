@extends('layout.zedalabs.teacher')

@php
    $tplConfig->page_meta_title = trans('teacher_dashboard.your_classes');
@endphp

@section('head')
    @parent

    <style>
        a.disabled {
            cursor: default;
        }
    </style>
    
    <script>
        var classRoomReady = {{ config('main.classroom_ready') }},
            ltNow = Date.parse('{{ \Carbon\Carbon::now($user->timezone_code()) }}'),
            classLogUrl = '{{ route('teachers', ['controller' => 'class-log', 'id' => ':id']) }}',
            teacherID = {{ $user->getPK() }};
    </script>
@endsection

@section('main')
    <main>

        <div id="root" class="container" v-cloak>

            <header class="dashboard-header mb-md">
                <h1 class="dashboard-title">@lang('teacher_dashboard.your_classes')</h1>
                <div class="dashboard-actions">
                    <a href="{{ route('teachers', ['controller' => 'class-log']) }}" class="button" @click.prevent="logClassWizard"><i class="fad fa-badge-check"></i> @lang( 'teacher_dashboard.log_class' )</a>
                    <a href="{{ route('teachers', ['controller' => 'calendar']) }}" class="button"><i class="fal fa-calendar-alt"></i> @lang( 'teacher_dashboard.calendar' )</a>
                </div>
            </header>

            <nav class="tab-navigation mb-lg">
                <a href="#upcoming-classes" class="active">@lang('teacher_dashboard.upcoming') <span class="badge" v-text="cntUpcomingClasses"></span></a>
                <a href="#trial-classes">@lang('teacher_dashboard.trial_classes') <span class="badge" v-text="cntTrialClasses"></span></a>
            </nav>

            <div class="tab-content">
                <div id="upcoming-classes">
                    <vdtnet-table :id="upcoming_id"
                                  :ref="upcoming_id"
                                  :opts="upcoming_opts"
                                  :fields="upcoming_fields"
                                  class-name=""
                                  @trigger-modal="triggerModal"
                                  @info="showInfo"
                    ></vdtnet-table>
                </div>
                <div id="trial-classes">
                    <vdtnet-table :id="trials_id"
                                  :ref="trials_id"
                                  :opts="trial_opts"
                                  :fields="trial_fields"
                                  class-name=""
                                  @log-completed="logCompleted"
                                  @log-noshow="logNoshow"
                    ></vdtnet-table>
                </div>
            </div>

            <info-modal ref="infoModal"
                        ok="@lang('teacher_dashboard.ok')"
            >
                <div slot="info" v-html="studentInfo"></div>
            </info-modal>

            <confirm-modal ok="@lang('teacher_dashboard.confirm_noshow')"
                           no="@lang('teacher_dashboard.cancel')"
                           :header="logHeader"
                           :visible="confirmNoShow"
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

            <confirm-modal ok="@lang('teacher_dashboard.go')"
                           no="@lang('teacher_dashboard.cancel')"
                           header="@lang('teacher_dashboard.log_new_class')"
                           :visible="showClassLog"
                           :ok-cls="{primary:selectedStudent, disabled:!selectedStudent}"
                           @close="showClassLog = false"
                           @confirm="goClassLog"
            >
                <i v-if="!studentsLoaded" class="fa fa-spinner fa-spin"></i>
                <p v-else-if="!students.length">@lang('teacher_dashboard.no_students')</p>
                <div v-else class="form-group ruled">
                    <label for="students">@lang('teacher_dashboard.with_student')</label>
                    <select id="students" v-model="selectedStudent">
                        <option value="">@lang('teacher_dashboard.choose')</option>
                        <option v-for="student in students" :key="student.id" :value="student.id" v-text="printStudentLine(student)"></option>
                    </select>
                </div>
            </confirm-modal>

        </div>

    </main>

@endsection