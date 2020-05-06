@extends('layout.zedalabs')

@php
    $lang = app()->getLocale();

    $tplConfig->current_menu OR $tplConfig->current_menu = request()->segment(1);

    !empty($language) OR $language = 'spanish';
@endphp

@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .language-lists a{
            display:block;
            margin-left:auto;
            margin-right:auto;
        }
        .img-thumbnail {
            padding: 4px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            -webkit-transition: all .2s ease-in-out;
            transition: all .2s ease-in-out;
            max-width: 100%;
            height: auto;
        }

        .counter-figure{
            font-size: 7rem;
            color: #303030;
            font-weight: 600;
            line-height: 1;
            margin-bottom: 0px !important;
        }
        .st-total {
            color: #555;
            display: inline-block;
            font-weight: 500;
            margin-right: 0;
            max-width: 80px;
            padding: 4px 8px;
            text-align: center;
        }
        .st-total > span {
            line-height: 17px;
            display: block;
            padding: 0;
        }
        .st-total > span.st-shares {
            font-size: 9px;
            line-height: 9px;
        }
        .button-social{
        min-width: 120px;
        color: white;
        }

        .button-social > img {
            display: inline-block;
            height: 20px;
            width: 20px;
            position: relative;
            top: 5px;
            vertical-align: top;
        }
        .button-social[data-network='facebook'] {
            background-color: #4267B2;
        }
        .button-social[data-network='twitter'] {
            background-color: #55acee;
        }
        .button-social[data-network='pinterest'] {
            background-color: #CB2027;
        }
        .button-social[data-network='linkedin'] {
            background-color: #0077b5;
        }
        .button-social[data-network='reddit'] {
            background-color: #ff4500;
        }
        .button-social[data-network='digg'] {
            background-color: #262626;
        }
        .button-social[data-network='email'] {
            background-color: #7d7d7d;
        }
        .button-social[data-network='sharethis'] {
            background-color: #95D03A;
        }
    </style>
@endsection

@section('top')
    @include('layout.zedalabs.blocks.hidden_menus')

    @include( 'layout.zedalabs.blocks.guest_language_menu' )

    @include('layout.zedalabs.blocks.header_navbar')
@endsection

@section('main')
    @if(View::exists("page.$lang.$subPage"))
        @include("page.$lang.$subPage")
    @else
        @includeIf("page.en.$subPage")
    @endif
@endsection

@section('footer')
    @include('layout.zedalabs.blocks.footer_navbar')
@endsection
