@php( $meta_title = __( 'errors.400' ) )

@extends('layout.zedalabs_static')

@section('main')
    <section class="hero height-50">
        <div class="container">
            <div class="empty 400">
                <h1>¯\_(ツ)_/¯</h1>
                <p>@lang('errors.400text')</p>
            </div>
        </div>
    </section>
@endsection