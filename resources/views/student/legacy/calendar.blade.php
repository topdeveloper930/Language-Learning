@extends('layout.legacy.student')

@php
    $selected_dt = new DateTime( config( 'main.schedule_from', 'tomorrow' ), new DateTimeZone( $user->timezone_code() ) );
    if( $selected_dt->format('H:i:s') > '23:30:00' ) $selected_dt->add( new DateInterval( 'PT30M' ) );

@endphp

@section('center_col')
    <div id="app">
        @include( 'layout.legacy.blocks.widgets.calendar_menu', [ 'syncButton' => true ] )

        <input type="hidden" id="translation" value="{{ json_encode( __( 'pages.calendar.student.vue' ) ) }}">
        <input type="hidden" id="studentID" value="{{ $user->studentID }}">
        <input type="hidden" id="teachers" value="{{ json_encode( $teachers ) }}">
        <input type="hidden" id="courses" value="{{ json_encode( $courses ) }}">
        <input type="hidden" id="trial_length" value="{{ config( 'main.trial_class_length', 30 ) }}">
        <input type="hidden" id="schedule_advance" value="{{ config( 'main.schedule_advance', 48 ) }}">
        <input type="hidden" id="start_step" value="{{ config( 'main.lesson_start_step', 30 ) }}">
        <input type="hidden" id="last_day" value="{{ date('Y-m-t 23:59:59', strtotime( config( 'main.schedule_forth', '+3 months' ))) }}">

        <hr class="short">
        @if( $teachers->count() )

        <h1 class="text-center mt-44">@lang( 'pages.calendar.schedule_your_class' )</h1>

        <div class="text-center">


            <p>
                @if( $next_class )
                    @lang( 'pages.calendar.student.next_class', [ 'next_class' => $next_class ] )
                @else
                    @lang( 'pages.calendar.student.no_class' )
                @endif
                <br><br>
                @if( $courses->count() )
                    @lang( 'pages.calendar.student.unscheduled', [ 'hours_remaining' => '@{{ hours_remaining("' . __("pages.calendar.student.hours_remaining") . '") }}', 'purchase_link' => $purchase_link ] )
                @else
                    @lang( 'pages.calendar.student.no_purchase', [ 'purchase_link' => $purchase_link ] )
                @endif
            </p>

            <div class="row form-inline" v-show="{{ $teachers->count() > 1 }}">
                <div class="control-group offset2 span6">
                    <label for="teacherOther"><b>@lang( 'pages.calendar.student.select' ):</b></label>
                    <teacher-select
                            @user-change="newTeacherSet"
                            :users="teachers"
                            :active="active_indx"
                    ></teacher-select>
                </div>
            </div><br>

            <h4 style="color:#000000;"><b>@lang( 'pages.calendar.student.teacher', [ 'teacher' => '<span v-text="selected_teacher.firstName"></span>' ] )</b></h4>

            <div class="row">
                <div class="offset1 span4">
                    <js-cal l="myCalendar"
                            language="{{ app()->getLocale() }}"
                            selected="{{ $selected_dt->format( 'd/m/Y' ) }}"
                            start="{{ date('1/m/Y') }}"
                            :end="calendar_end.toFormat('d/L/y')"
                            :driver="simpleJsCalendar"
                            :time_slots="time_slots"
                    ></js-cal>
                </div>
                <div class="span4">
                    <div id="actionBlock">
                        <timeslots :slots="selected_date_slots"
                                   :selected="selected_date"
                                   v-if="active_action === 'select'"
                        ></timeslots>
                        <class-confirm :courses="courses | teacherCourse(teacher_languages)"
                                       :date="formatted_date"
                                       :time="selected_time"
                                       header_tplt="@lang( 'pages.calendar.student.confirm_class' )"
                                       v-if="active_action === 'confirm'"
                        >@lang( 'pages.calendar.student.teacher_notified' )</class-confirm>
                        <div v-if="active_action === 'success'">
                            <h4 class="text-black">@lang('pages.calendar.student.scheduled')</h4>
                            <h5>@{{ formatted_date }}<br>@{{ selected_time }} (@{{ student_tz }})</h5>

                            <hr>
                            <h6>@lang('pages.calendar.student.scheduled_notice')</h6>
                            <br>
                            <!-- Only should show up if they are not already synced with gCalendar -->
                            <button v-if="show_sync_button" class="btn btn-small">@lang('pages.calendar.sync_gcal')</button>
                        </div>
                        <ajax-errors v-if="active_action === 'errors'"
                                     :errors="ajax_errors"
                        >@lang( 'pages.calendar.student.errors' )</ajax-errors>
                    </div>
                </div>
                <div class="span1"></div>
                <div class="span10" style="padding-top: 10px;">@lang( 'pages.calendar.student.timezone', [ 'timezone' => '<span id="student_tz">' . $user->timezone_code() . '</span>' ] )</div>
            </div>

            <hr>

            <div class="row">
                <div class="span3">
                    <img  class="responsive" style=" border-radius: 50%;width:60%;height:60%;" :src="(selected_teacher.profileImage) ? '/' + selected_teacher.profileImage : '/img/profiles/no-profile-image.jpg'">
                    <br>
                    <p v-text="selected_teacher.teacherIntroduction.substr(0, selected_teacher.teacherIntroduction.lastIndexOf('.', 150)) + '. ' + '@lang("pages.calendar.student.teaching")'.replace(':nn', pronoun.ucFirst()).replace(':ll', teacher_languages[0].ucFirst()).replace(':yy', selected_teacher.startedTeaching)"></p>
                    <a class="btn btn-small btn-success "
                       :href="teacher_profile_link"
                       target="_blank"
                    >@lang( 'pages.labels.full_profile' )</a>
                </div>
                <div class="span7 text-left">
                    <h6 class="uc-header">@lang( 'pages.calendar.student.teacher_tz', [ 'nn' => '<span v-text="pronoun_possessive"></span>' ] )</h6>
                    <p v-text="selected_teacher.timezone"></p>

                    <div v-show="selected_teacher.coursesTaught.length">
                        <h6 class="uc-header">@lang( 'pages.calendar.student.teacher_courses', [ 'nn' => '<span v-text="pronoun"></span>' ] )</h6>
                        <p v-text="selected_teacher.coursesTaught.replace( /&courses%5B%5D=/g, ', ').replace( 'courses%5B%5D=', '')"></p>
                    </div>

                    <div v-show="selected_teacher.languagesSpoken.length">
                        <h6 class="uc-header">@lang( 'pages.calendar.student.teacher_speaks', [ 'nn' => '<span v-text="pronoun"></span>' ] )</h6>
                        <p v-text="selected_teacher.languagesSpoken"></p>
                    </div>

                    <div v-show="selected_teacher.agesTaught.length">
                        <h6 class="uc-header">@lang( 'pages.calendar.student.ages', [ 'nn' => '<span v-text="pronoun"></span>' ] )</h6>
                        <p v-text="selected_teacher.agesTaught.replace( /&ages%5B%5D=/g, ', ').replace( 'ages%5B%5D=', '')"></p>
                    </div>

                    <div v-show="selected_teacher.hobbies.length">
                        <h6 class="uc-header">@lang( 'pages.calendar.student.hobbies', [ 'nn' => '<span v-text="selected_teacher.firstName"></span>' ] )</h6>
                        <p v-text="selected_teacher.hobbies"></p>
                    </div>
                </div>
            </div>

            @else
                <h3 style="color:#000000;">@lang( 'pages.calendar.student.no_teacher')</h3>
                <p><i>@lang( 'pages.calendar.student.teacher_assignment', [ 'email' => config( 'legacy.globals.mainEmail' ) ] )</i></p>
                <hr>
                <div class="span10 text-center" style="padding-top: 10px;" v-show="{{ (bool) $user->timezone_code() }}">@lang( 'pages.calendar.student.timezone', [ 'timezone' => '<span id="student_tz">' . $user->timezone_code() . '</span>' ] )</div>
            @endif


        </div>
    </div>
@endsection