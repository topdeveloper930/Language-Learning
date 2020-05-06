@extends('layout.legacy.student')

@section('center_col')
    <div id="root" v-cloak>
        @include( 'layout.legacy.blocks.widgets.calendar_menu', [ 'syncButton' => true ] )

        <hr class="short">

        <div class="text-center">

            <input type="hidden" id="translation" value="{{ json_encode( __( 'pages.upcoming.vue' ) ) }}">
            <input type="hidden" id="upcoming_classes" value="{{ $upcoming_classes->toJson() }}">
            <input type="hidden" id="schedule_advance" value="{{ config( 'main.cancel_advance', 24 ) }}">

            <section v-if="classes.length">
                <h3 class="text-black my-20">@lang( 'pages.upcoming.classes' )</h3>

                <h5 id="timezone">@lang( 'pages.upcoming.tz', [ 'tz' => $user->timezone_code() ] )</h5>

                <hr>

                <lesson v-for="(lesson, indx) in classes"
                        :start="lesson.eventStart"
                        :end="lesson.eventEnd"
                        :teacher="lesson.teacher_name"
                        :teacher_id="lesson.teacherID"
                        :course="lesson.entryTitle"
                        :numstudents="lesson.numStudents"
                        :indx="indx"
                ></lesson>

                <p class="text-left">@lang( 'pages.upcoming.important', [ 'hours' => config( 'main.cancel_advance', 24 ) ] )</p>
            </section>

            <section v-else>
                <h3 class="text-black mb-10">@lang( 'pages.upcoming.no_classes' )</h3>
                <br>
                <a href="{{ route('students', ['controller' => 'calendar']) }}" title="{{ strtolower( __( 'pages.upcoming.schedule' ) ) }}" class="btn btn-large btn-success">@lang( 'pages.upcoming.schedule' )</a>
            </section>

            <ll-modal :header="lang.cancel"
                      :body="class_body"
                      :visible="!!class_body"
                      :cancel_txt="lang.cancel_txt"
                      :ok_txt="lang.ok_txt"
                      :ok_handler="cancelClass"
                      @modalclose="modalClose"
                      ok_class="btn btn-danger"
            >   <button type="button" class="close" @click="ajax_errors = false" v-if="ajax_errors" style="padding: 8px 12px">x</button>
                <ajax-errors v-if="ajax_errors"
                             class="alert alert-error"
                             :errors="ajax_errors"
                >@lang( 'pages.upcoming.errors' )</ajax-errors>
            </ll-modal>

        </div>
    </div>
@endsection