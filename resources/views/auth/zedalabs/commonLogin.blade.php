@extends('layout.zedalabs')

@php($err_class = ($errors->any()) ? ' class=shaking' : '')

@push('scripts')
    <script src="{{ asset('public/js/login.js') }}"></script>
@endpush

@section('main')
    <div class="modal-page">

        <a href="/" class="action left"><i class="far fa-long-arrow-left"></i> @lang('auth.home')</a>

        <div class="modal-page-box">

            <nav class="tab-navigation">
                <a href="#students" class="active">@lang('auth.students')</a>
                <a href="#teachers">@lang('auth.teachers')</a>
            </nav>

            <div id="students">
                <header>
                    <!--<a href="/pages-website/home.php" class="logo"><img src="/library/images/logo-icon.svg"></a>-->
                    <h1 class="modal-title">@lang('auth.student_login')</h1>
                    <p class="modal-subtitle">@lang('auth.student_yet', ['link' => route('page', ['controller' => 'trial-lesson'])])</p>
                </header>

                <form role="form" action="{{ url( '/auth/login' ) }}" method="post"{{ $err_class }}>
                    {{ csrf_field() }}
                    <input type="hidden" name="role" value="students">
                    <div class="form-group">
                        <input type="email" placeholder=" " value="{{ old( 'email' ) }}" name="email" id="email" autocomplete="off" required>
                        <label for="email">@lang('auth.email_address')</label>
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder=" " value="" name="password" id="password" required>
                        <label for="password">@lang('auth.password')</label>
                    </div>
                    <div class="form-group">
                        <button class="button primary" type="submit">@lang('auth.login')</button>
                    </div>
                    <div class="form-group">
                        <a href="{{ route( 'password.request', ['area' => 'students'] ) }}" class="forgot-password">@lang( 'auth.forget' )</a>
                    </div>
                </form>
            </div>


            <div id="teachers">
                <header>
                    <h1 class="modal-title">@lang('auth.teacher_login')</h1>
                    <p class="modal-subtitle">@lang('auth.teacher_yet', ['link' => route('page', ['controller' => 'jobs'])])</p>
                </header>



                <form role="form" action="{{ url( '/auth/login' ) }}" method="post"{{ $err_class }}>
                    {{ csrf_field() }}
                    <input type="hidden" name="role" value="teachers">
                    <div class="form-group">
                        <input type="email" placeholder=" " value="{{ old( 'email' ) }}" name="email" id="email" autocomplete="off" required>
                        <label for="email">@lang('auth.email_address')</label>
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder=" " value="" name="password" id="password" required>
                        <label for="password">@lang('auth.password')</label>
                    </div>
                    <div class="form-group">
                        <button class="button primary" type="submit">@lang('auth.login')</button>
                    </div>
                    <div class="form-group">
                        <a href="{{ route( 'password.request', ['area' => 'teachers'] ) }}" class="forgot-password">@lang( 'auth.forget' )</a>
                    </div>
                </form>
            </div>

            @include('layout/zedalabs/blocks/auth_social_networks')


        </div>

    </div>

    @include('layout/zedalabs/blocks/toaster', ['errors' => $errors])
@endsection