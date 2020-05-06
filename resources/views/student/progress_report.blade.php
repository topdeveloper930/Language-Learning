@extends('layout.zedalabs.student')

@php
    $tplConfig->page_meta_title = trans('progress_report.report');
    $teacher_photo = asset( $report->teacher->accost() );
    $tplConfig->current_menu = 'progress-reports';
@endphp

@section('main')
    <main id="root" v-cloak>

        <div class="container lg">

        @include( 'layout.zedalabs.widgets.page_header', [
            'page_header' => __( 'progress_report.report_#', [ 'number' => $report->getKey() ] ),
            'page_subtitle' => __( 'progress_report.written_by', [
            	'teacher_url'       => route('page', [ 'controller' => 'tutor', 'id' => $report->teacherID ]),
            	'teacher_photo'     => asset( $report->teacher->profileImage ),
            	'teacher_accost'    => trim($report->teacher->title . ' ' . $report->teacher->firstName),
            	'teacher'           => $report->teacher->fullName(),
            	'date'              => date('F, jS Y', strtotime($report->evaluationDate))
            ])
        ])
            <div class="report-feedback">
                <h3 class="section-label">@lang('progress_report.study_plan')</h3>
                <p>{{ $report->comments }}</p>
            </div>

            <hr>

            <div class="report-scores">
                <h3 class="section-label">@lang('progress_report.scoring')</h3>

                @foreach(['speaking', 'listening', 'reading', 'writing'] as $skill)
                <div class="report-score mb-md">
                    <h3 class="h4">@lang("progress_report.$skill") <span class="badge">{{ $report->{$skill . 'Level'} }}/10</span></h3>
                    <a href="javascript:void(0);" @click.prevent="setModal({{ $report->{$skill . 'LevelID'} }})">{{ $report->{$skill . 'LevelTitle'} }}</a>
                    <div class="bar mt-xs">
                        <div class="bar-inner tooltip" data-tooltip="{{ $report->{$skill . 'Level'} }}/10 ({{ $report->{$skill . 'LevelTitle'} }})" style="width:{{ 10 * $report->{$skill . 'Level'} }}%;"></div>
                    </div>
                </div>
                @endforeach

            </div>

            <div class="report-total-score text-align-center mt-xl mb-lg">
                <span class="label">@lang('progress_report.average_score')</span>
                <span class="score">{{ ( $report->speakingLevel +  $report->listeningLevel + $report->writingLevel + $report->readingLevel ) / 4 }}</span>
            </div>

            <div class="notification">@lang('progress_report.notification', ['levels_url' => route('page', ['controller' => 'language-levels'])])</div>

        </div>

        <info-modal ok="@lang('progress_report.ok')"></info-modal>
    </main>
@endsection