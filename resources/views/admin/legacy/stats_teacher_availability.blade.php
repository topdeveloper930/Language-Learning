@extends('layout.legacy.admin')

@section('center_col')
    <div id="app">
        <input type="hidden" id="translation" value="{{ json_encode( __( $translation ) ) }}">
        <h2>Some title</h2>
        <vdtnet-table
                id="stats_dt"
                :opts="opts"
                :fields="fields"
        ></vdtnet-table>
    </div>
@endsection