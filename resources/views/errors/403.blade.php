@php( $meta_title = __( 'errors.403' ) )

@extends('layout.zedalabs_static')

@section('main')
    <section class="hero height-50">
        <div class="container">
            <div class="empty 403">
                <h1>&#x1F6D1;</h1>
                <p>@lang('errors.403text')</p>
            </div>
        </div>
    </section>
@endsection