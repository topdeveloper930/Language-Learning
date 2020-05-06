@extends('layout.legacy.admin')
@php
    $center_col_cls = 'span12';
    !empty( $ajax_url ) OR $ajax_url = \Illuminate\Support\Facades\Request::url();
@endphp

@section('head')
    @parent
    <script>
        var ajax_url = '{{ url( $ajax_url ) }}';
    </script>
@endsection

@section('left_col')
@endsection