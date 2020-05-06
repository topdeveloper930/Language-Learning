@extends('layout.zedalabs.student')

@php
    $advanceNoticePeriod = config( 'main.schedule_advance', 24 ) * 3.6E6;
    $tplConfig->page_meta_title = trans('students_dashboard.your_classes');
@endphp

@section('head')
    @parent
    <style>
        .pagination div, .pagination span {
            display: flex;
        }
        .pagination a.current,
        .pagination a:hover {
            opacity: 1;
            background: rgba(45,43,36,0.05);
        }
        .pagination a.disabled {
            opacity: .5;
            background: white;
        }
    </style>
    <script>
        var classRoomReady = {{ config('main.classroom_ready') }};
    </script>
@endsection

@push('extra_item_after')
    <a href="{{ route('students', ['controller' => 'schedule-class']) }}" class="button primary">@lang( 'students_dashboard.schedule_class' ) <i class="far fa-plus"></i></a>
@endpush

@section('main')
    <main id="root" v-cloak>
        <input type="hidden" id="timezone" value="{{ $user->timezone_code() }}">
        <div class="container">

            @include( 'layout.zedalabs.widgets.dashboard_header', [ 'role' => 'students', 'dashboard_header' => __( 'students_dashboard.your_classes' ) ] )

            <nav class="tab-navigation mb-lg">
                <a href="#upcoming-classes" class="active">@lang('students_dashboard.upcoming') <span class="badge">{{ $upcoming_total }}</span></a>
                <a href="#previous-classes">@lang('students_dashboard.previous') <span class="badge">@{{ cntPreviousClasses }}</span></a>
            </nav>

            <div class="tab-content">
                <div id="upcoming-classes">
                    @if(count($upcoming_classes))
                    <table>
                        <thead>
                        <tr class="align-center">
                            <th>@lang('students_dashboard.teacher')</th>
                            <th>@lang('students_dashboard.date_time')</th>
                            <th>@lang('students_dashboard.program')</th>
                            <th>@lang('students_dashboard.length')</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach( $upcoming_classes as $class )
                                @php
                                    $startLT = $user->formatUTCtoMyTimeZone( $class->eventStart, 'M d, Y h:ia' );
                                    $teacher_name = $class->teacher->accost();
                                    $areYouSure = trans(
                                    	'students_dashboard.are_you_sure',
                                    	[
                                    		'lesson' => substr($class->entryTitle, strpos($class->entryTitle, '(') + 1, strpos($class->entryTitle, ')') - strpos($class->entryTitle, '(') - 1 ),
                                    		'teacher' => $teacher_name,
                                    		'date_time' => $startLT
                                    ])
                                @endphp

                                <tr id="lesson_{{ $class->calendarID }}">
                                    <td>
                                        <a href="{{ route('tutor', ['language' => strtolower( $class->teacher->languagesTaught[0] ), 'name' => str_replace(' ', '-', strtolower($class->teacher->fullname())), 'id' => $class->teacherID ]) }}" class="chip">
                                            <img src="{{ asset($class->teacher->profileImage) }}" alt="{{ $teacher_name }}" class="avatar"> {{ $teacher_name }}
                                        </a>
                                    </td>
                                    <td>{{ $startLT }}</td>
                                    <td>@fromparentheses($class->entryTitle)</td>
                                    <td><span v-text="(Date.parse('{{$class->eventEnd}}') - Date.parse('{{$class->eventStart}}')) / 6E4"></span> @lang('students_dashboard.minutes')</td>
                                    <td class="actions">
                                        <enter-btn href="{{ url('students/classroom', [ 'event' => $class->calendarID ]) }}"
                                                   class="button primary"
                                                   start="{{ $user->formatUTCtoMyTimeZone( $class->eventStart, 'Y-m-d H:i:s' ) }}"
                                            >@lang('students_dashboard.enter_classroom')</enter-btn>

                                        <button type="button"
                                                v-if="(new Date( '{{ $class->eventStart }}' ) - new Date('{{ gmdate('Y-m-d H:i:s') }}')) > {{ $advanceNoticePeriod }}"
                                                class="button"
                                                @click="$refs.classModal.show({{ $class->calendarID }}, '{{ $areYouSure }}')"
                                        >
                                            @lang('students_dashboard.change_class')
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="notification mt-lg">
                        @lang('students_dashboard.need_cancel') <a href="{{ url('terms-conditions#refund_cancel') }}">@lang('students_dashboard.learn_more')</a>
                    </div>
                    @else
                        @php
                            $credits = 0;
                            foreach ( $courseArr as $course_row )
                                $credits += $course_row->hours - $course_row->creditsUsed;
                        @endphp
                        <div class="empty pv-lg">
                            <div class="empty-icon">
                                <i class="fal fa-comments-alt"></i>
                            </div>
                            <p class="empty-title">@lang('students_dashboard.nothing_scheduled')</p>
                            <p class="empty-subtitle">{{ trans_choice('students_dashboard.credits', $credits, ['credits' => $credits]) }}</p>
                            <div class="empty-action">
                                <a v-if="{{ $credits > .5 ? 1 : 0 }}" href="{{ url('students/schedule-class' ) }}" class="button primary">@lang('students_dashboard.schedule_class')</a>
                                <a v-else href="{{ route('students', ['controller' => 'purchase'] ) }}" class="button primary">@lang('students_dashboard.purchase')</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div id="previous-classes">
                    <vdtnet-table v-show="cntPreviousClasses > 0"
                                  :id="table_id"
                                  :opts="opts"
                                  :fields="fields"
                                  class-name=""
                    ></vdtnet-table>
                    <div v-show="cntPreviousClasses == 0" class="empty pv-lg">
                        <div class="empty-icon">
                            <i class="fal fa-comments-alt"></i>
                        </div>
                        <p class="empty-title">@lang('students_dashboard.nothing_logged')</p>
                        <p class="empty-subtitle">@lang('students_dashboard.nothing_reported_yet')</p>
                    </div>
                </div>
            </div>

        </div>
        <change-class ref="classModal"
                      quit-txt="@lang('students_dashboard.quit')"
                      ok-txt="@lang('students_dashboard.confirm_delete')"
                      delete-href="{{ route('event.index' ) }}"
                      edit-href="{{ route('students', ['controller' => 'schedule-class'] ) }}"
                      @delete="deleteRow"
                      @err="errors"
        ></change-class>
    </main>
@endsection