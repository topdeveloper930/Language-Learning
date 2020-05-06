@extends('admin.legacy.stats')

@php
    $tplConfig->current_menu = __( 'admin_payments_pending.student_payments' );
    $tplConfig->page_title = __( 'admin_payments_pending.student_payments' );
@endphp

@section('center_col')
    <div id="app">
        <input type="hidden" id="translation" value="{{ json_encode( __( $translation ) ) }}">

        <vdtnet-table
                ref="table"
                :id="table_id"
                :opts="opts"
                :fields="fields"
                :hide-footer="hideFooter"
                @trigger-modal="triggerModal"
        ></vdtnet-table>

        <modal :header="modalHeader"
               :visible="modalVisible"
               :body="modalBody"
               :ok_handler="process"
               :cancel_txt="cancelTxt"
               :ok_txt="okTxt"
               :ok_class="okCls"
               @modalclose="hideModal"
        >
            <p v-text="modalDescription"></p>
            <div style="padding-right: 12px" v-if="'view' !== action">
                <label for="comment" v-text="label"></label>
                <textarea style="width: 100%" id="comment" v-model="comment"></textarea>
                <small>@lang('admin_payments_pending.remark')</small>
            </div>
        </modal>
    </div>
@endsection