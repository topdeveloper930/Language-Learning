@extends('layout.zedalabs')

@php
    $tplConfig->current_menu OR $tplConfig->current_menu = request()->segment(1);

    !empty($language) OR $language = 'spanish';
@endphp

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('top')
    @include('layout.zedalabs.blocks.hidden_menus')

    @include( 'layout.zedalabs.blocks.guest_language_menu' )

    @include('layout.zedalabs.blocks.header_navbar')
@endsection

@section('footer')
    @include('layout/zedalabs/blocks/toaster', ['errors' => $errors])
    @include('layout.zedalabs.blocks.footer_navbar')
@endsection

