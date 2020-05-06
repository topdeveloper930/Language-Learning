@extends('layout.zedalabs.guest')
@php
    $lang = app()->getLocale();
    if( !isset( $subPage ) ) $subPage = 'home';
@endphp

@section('main')
    @if(View::exists("page.$lang.$subPage"))
        @include("page.$lang.$subPage")
    @else
        @includeIf("page.en.$subPage")
    @endif
@endsection
