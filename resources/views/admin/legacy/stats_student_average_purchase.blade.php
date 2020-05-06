@extends('admin.legacy.stats')

@section('center_col')
    <div id="app"
         data-languages="{{ json_encode( $language_list ) }}"
    >
        <input type="hidden" id="translation" value="{{ json_encode( __( $translation ) ) }}">

        <div class="row">
            <div class="control-group span">
                <label class="control-label" for="year">@lang( $translation . '.select_year' )</label>
                <div class="controls">
                    <select id="year" name="year" v-model="year" @change="reload">
                        <option v-for="y in [{{ implode(', ', array_reverse( range( 2014, date('Y') ) )) }}]" :value="y" :selected="y == year">@{{ y }}</option>
                    </select>
                </div>
            </div>
            <div class="control-group span">
                <label class="control-label" for="mode">@lang( $translation . '.switch_by' )</label>
                <div class="btn-group" data-toggle="buttons-radio">
                    <button type="button" value="months" id="mode" :class="{btn: true, 'btn-primary': true, active: 'months' == mode}" @click="toggleMode" :disabled="'active_student' === per">@lang( $translation . '.month' )</button>
                    <button type="button" value="weeks" :class="{btn: true, 'btn-primary': true, active: 'weeks' == mode}" @click="toggleMode" :disabled="'active_student' === per">@lang( $translation . '.week' )</button>
                </div>
            </div>
            <div class="control-group span">
                <label class="control-label" for="mode">@lang( $translation . '.switch_per' )</label>
                <div class="btn-group" data-toggle="buttons-radio">
                    <button type="button" value="transaction" id="per" :class="{btn: true, 'btn-info': true, active: 'transaction' == per}" @click="togglePer">@lang( $translation . '.transaction' )</button>
                    <button type="button" value="active_student" :class="{btn: true, 'btn-info': true, active: 'active_student' == per}" @click="togglePer">@lang( $translation . '.active_student' )</button>
                    <button type="button" value="new_student" :class="{btn: true, 'btn-info': true, active: 'new_student' == per}" @click="togglePer">@lang( $translation . '.new_student' )</button>
                </div>
            </div>
        </div>


        <vdtnet-table
                :id="table_id"
                :opts="opts"
                :fields="fields"
                :hideFooter="hideFooter"
        ></vdtnet-table>
    </div>
    <style>
        .dataTable tr:nth-last-child(-n+3) {
            font-weight: bold;
        }
    </style>
@endsection