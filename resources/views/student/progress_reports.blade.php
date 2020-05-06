@extends('layout.zedalabs.student')

@php
    $tplConfig->page_meta_title = trans('progress_reports.your_progress');
@endphp

@section('head')
    @parent
    <script>
        var levels = {!! json_encode(config('evaluation.levels')) !!};
    </script>
@endsection

@section('main')
    <main id="root" v-cloak>

        <div class="container">

            <header class="dashboard-header mb-lg">
                <h1 class="dashboard-title">@lang('progress_reports.reports') <span class="badge" v-if="null !== reportsCnt" v-text="reportsCnt"></span></h1>
                <div class="dashboard-actions">
                    <a href="#" class="button"
                       @click.prevent="sh.$emit('info-modal', {
                            sizeCls: 'lg',
                            btnTxt: 'close',
                            header: 'explained',
                            info: 'reports_description'
                       })"
                    >@lang( 'progress_reports.about_reports' )</a>
                </div>
                <p class="dashboard-subtitle shorten" data-length="100">@lang( 'progress_reports.track_your_progress' )</p>
            </header>

            <vdtnet-table :id="table_id"
                          :opts="opts"
                          :fields="fields"
                          class-name=""
            ></vdtnet-table>

        </div>

        <info-modal ok="@lang('progress_reports.ok')"></info-modal>
    </main>
@endsection