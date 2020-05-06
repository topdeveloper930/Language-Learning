@extends('layout.zedalabs.student')

@section('head')
    @parent
    <script>
        var studentID = {{ $user->studentID }},
            timezone = '{{ $user->timezone_code() }}',
            schedule_advance = {{ config( 'main.schedule_advance', 48 ) }},
            courseList = {!! json_encode($courses) !!},
            trial_class_length = {{ config( 'main.trial_class_length', 30 ) }},
            start_step = {{ config( 'main.lesson_start_step', 30 ) }},
            last_day = new Date( "{{ date('Y-m-d 23:59:59', strtotime( config( 'main.schedule_forth', '+3 months' ))) }}"),
            editClass = {!! json_encode($class) !!},
            teacherList = {!! json_encode($teachers) !!};
    </script>
@endsection

@push('extra_item_before')
    <g-calendar calendar_id="{{ $user->gcalendar ? $user->gcalendar->provider_cal_id : '' }}"
                client_id="{{ config( 'api.gcalendar.clientID' ) }}"
                api_key="{{ config( 'api.gcalendar.apiKey' ) }}"
                ll_location="{{ config( 'api.gcalendar.location' ) }}"
                gapi_token="{{ $user->hasToken() }}"
                translate='{{ json_encode(__( 'gcalendar' )) }}'
    ></g-calendar>
@endpush

@section('main')
    <main id="root" v-cloak>
        <div class="container">

            @include( 'layout.zedalabs.widgets.dashboard_header', [ 'role' => 'students', 'dashboard_header' => ($class ? __( 'schedule_class.reschedule_class' ) : __( 'schedule_class.schedule_classes' )) ] )

            <div class="step-by-step">
                <div :class="{step: true, activated: !isReschedule}" data-instruction="@lang('schedule_class.select_teacher')">
                    <h2 class="step-title">1. @lang('schedule_class.teacher')</h2>
                    <p class="step-description">@lang('schedule_class.first_select')</p>
                    <div class="columns">
                        <div v-for="teacher of teachers" class="column col-3 col-6-md col-12-sm">
                            <a :class="{card:true, profile:true, selected: selectedTeacher && teacher.teacherID == selectedTeacher.teacherID }" @click.prevent="selectTeacher(teacher)">
                                <img :src="imgSrc(teacher)" alt="" class="profile-avatar">
                                <h2 class="card-title" v-text="fullname(teacher)"></h2>
                            </a>
                        </div>
                    </div>
                </div>
                <div :class="{step:true, activated:selectedTeacher && !isReschedule}" data-instruction="@lang('schedule_class.select_program')">
                    <h2 class="step-title">2. @lang('schedule_class.program')</h2>
                    <p class="step-description">@lang('schedule_class.programs_available')</p>

                    <a v-for="course of teacherCourses"
                       :class="{card:true, 'card-sm mb-sm':true, selected: selectedCourse && course == selectedCourse, disabled: remainingCredits(course) < .5 }"
                       href="javascript:void(0);"
                       @click="selectCourse(course)">
                        <div class="card-content">
                            <h2 class="card-title" v-text="course.course"></h2>
                            <p class="card-excerpt" v-html="courseDescription(course)"></p>
                        </div>
                    </a>
                </div>
                <div :class="{step:true, activated:selectedCourse}" data-instruction="@lang('schedule_class.select_class_length')">
                    <h2 class="step-title">3. @lang('schedule_class.class_length')</h2>
                    <p class="step-description">@lang('schedule_class.how_long')</p>
                    <div class="columns">
                        <div v-for="l of lengths" class="column col-3">
                            <a :class="{card:true, profile: true, selected: l == length, disabled: l > maxClassLength}" href="javascript:void(0);" @click.prevent="setLength(l)">
                                <h2 class="card-title" v-text="lengthText(l)"></h2>
                            </a>
                        </div>
                    </div>
                </div>
                <div :class="{step:true, activated:length}" data-instruction="@lang('schedule_class.select_date_time')">
                    <h2 class="step-title">4. @lang('schedule_class.date_time')</h2>
                    <p class="step-description">@lang('schedule_class.choose_datetime')</p>

                    <div class="columns align-stretch">
                        <div class="column col-8 col-12-md sticky-sidebar">
                            <div id="datepicker"></div>
                        </div>
                        <div class="column col-4 col-12-md">
                            <div v-if="!sh.empty(time_slots)" class="calendar-times">
                                <button v-for="(slot, i) in time_slots"
                                        v-if="slot.status >= 0"
                                        type="button" :class="{disabled: isDisabledSlot(i), selected: slot.t === selectedSlot}"
                                        v-text="studentTime(slot)"
                                        @click="selectTime({t:slot.t, status: !isDisabledSlot(i)})"
                                ></button>
                            </div>
                            <div v-else class="text-center text-notify pv-lg">
                                @lang('schedule_class.no_slots')
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="{step: true, activated: selectedSlot}">
                    <h2 class="step-title">@lang('schedule_class.confirm')</h2>
                    <p class="step-description">@lang('schedule_class.check_confirm')</p>
                    <div class="mb-md">
                        <p>
                            <strong>@lang('schedule_class.teacher'):</strong> <span v-if="selectedTeacher" v-text="accost(selectedTeacher)"></span><br>
                            <strong>@lang('schedule_class.program'):</strong> <span v-if="selectedCourse" v-text="program"></span><br>
                            <strong>@lang('schedule_class.class_length'):</strong> <span v-if="length" v-text="classLength"></span><br>
                            <strong>@lang('schedule_class.date_time'):</strong> <span v-if="selectedSlot" v-text="selectedDT"></span>
                        </p>
                    </div>
                    <button type="button" class="button primary" @click="submitLesson">
                        <i v-if="loading" class="fa fa-spinner fa-spin"></i>
                        @lang('schedule_class.confirm_class')
                    </button>
                </div>

                <div v-if="stepInstruction" class="step-instruction" style="display: inherit"><span v-text="stepInstruction"></span><br><i class="fad fa-arrow-alt-circle-down"></i></div>

            </div>

        </div>
    </main>
@endsection