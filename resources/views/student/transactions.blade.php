@extends('layout.zedalabs.student')

@php
    $advanceNoticePeriod = config( 'main.schedule_advance', 24 ) * 3.6E6;
    $tplConfig->page_meta_title = trans('student_transactions.transaction_history');
@endphp

@push('dashboard_subtitle')
    <p class="dashboard-subtitle">@lang('student_transactions.students_all_transactions')</p>
@endpush

@section('main')
    <main id="root" v-cloak>
        <div class="container">

            @include( 'layout.zedalabs.widgets.dashboard_header', [ 'role' => 'students', 'dashboard_header' => __( 'student_transactions.transaction_history' ) ] )

            <input class="mb-md" type="text" name="search" placeholder="@lang('student_transactions.search')" value="" @keyup="search">

            <vdtnet-table :id="tbl_id"
                          class-name="table table-bordered table-striped"
                          :opts="opts"
                          :fields="fields"
            ></vdtnet-table>
        </div>
    </main>
@endsection