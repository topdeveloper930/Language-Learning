@extends('layout.zedalabs.student')

@php
    $tplConfig->page_meta_title = trans('credits.history');
@endphp

@push('dashboard_subtitle')
    <p class="dashboard-subtitle">@lang( 'credits.all_transactions' )</p>
@endpush

@push('extra_item_after')
    <a href="{{ route('students', ['controller' => 'schedule-class']) }}" class="button primary">@lang( 'students_dashboard.schedule_class' ) <i class="far fa-plus"></i></a>
@endpush

@section('main')
    <main id="root" v-cloak>

        <div class="container">

            @include( 'layout.zedalabs.widgets.dashboard_header', [ 'role' => 'students', 'dashboard_header' => __( 'credits.your_history' ) ] )

            <vdtnet-table :id="table_id"
                          :opts="opts"
                          :fields="fields"
                          class-name=""
            ></vdtnet-table>

        </div>

    </main>
@endsection