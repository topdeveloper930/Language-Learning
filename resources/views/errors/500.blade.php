@php( $meta_title = __( 'errors.500' ) )

@extends('layout.zedalabs_static')

@section('main')
    <section class="hero height-50">
        <div class="container">
            <div class="empty 500">
                <h1>¯\_(°_o)_/¯</h1>
                <p>@lang('errors.500text')</p>
            </div>
        </div>
    </section>
@endsection