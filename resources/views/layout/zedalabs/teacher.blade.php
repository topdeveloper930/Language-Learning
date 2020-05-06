@extends('layout.zedalabs')

@php
    $tplConfig->current_menu OR $tplConfig->current_menu = request()->segment(2);
@endphp

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('top')
    @include('layout.zedalabs.blocks.teacher_mobile_menu')
    <div class="hidden-menu-overlay"></div>

    @if( !empty($announcement) )
        @include( 'layout.zedalabs.blocks.announcement', ['announcement' => $announcement] )
    @endif

    @include('layout.zedalabs.blocks.header_navbar_teacher')
@endsection

@section('footer')
    @include('layout/zedalabs/blocks/toaster', ['errors' => $errors])
    @include('layout.zedalabs.blocks.footer_navbar_dashboard')
@endsection

