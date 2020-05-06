@extends('layout.zedalabs.student')
@php
    $tplConfig->page_meta_title = trans('student_profile.student_profile');
    $ajax_url = url( "ajax/student/{$user->getPK()}");
@endphp

@section('head')
    @parent
    <style>
        .twitter-typeahead {
            display: block !important;
        }
    </style>

    <script>
        var initImage = '{{ $user->getProfileImage() }}';
    </script>
@endsection

@section('main')

    <main>

        <div id="root" class="container" v-cloak>

            @include( 'layout.zedalabs.widgets.dashboard_header', [ 'role' => 'students', 'dashboard_header' => __( 'student_profile.profile' ) ] )

            <nav class="tab-navigation mb-lg">
                <a href="#profile" class="active">@lang('student_profile.profile')</a>
                <a href="#security">@lang('student_profile.security')</a>
                <a href="#settings">@lang('student_profile.settings')</a>
            </nav>

            <div class="columns">

                <profile-image email="{{ $user->getEmail() }}"
                               :fullname="fullname"
                               :image="profileImage"
                               slug="{{ asset( 'img/profiles/no-profile-image.jpg' ) }}"
                ></profile-image>

                <div id="profile" class="column col-9 col-12-md">

                    <p>@lang('student_profile.you_appear')</p>

                    <form action="" id="profile_form" method="post" @submit.prevent="submitForm('profile_form')">
                        <fieldset>
                            <legend>@lang('student_profile.your_details')</legend>
                            <div class="columns">
                                <div class="column col-2 col-4-md col-12-sm">
                                    <div class="form-group">
                                        <label for="title">@lang('student_profile.title')</label>
                                        <select id="title" name="title" v-model="formsData.profile_form.title">
                                            @foreach(__('student_profile.titles') as $key => $ttl)
                                            <option value="{{ $key }}"{{ ($key == old('title', $user->title)) ? 'selected' : '' }}>{{ $ttl }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-invalid-label" v-if="errors.title" v-html="outputErrors('title')"></span>
                                    </div>
                                </div>
                                <div class="column col-5 col-4-md col-12-sm">
                                    <div class="form-group">
                                        <label for="firstName">@lang('student_profile.first_name')</label>
                                        <input type="text" name="firstName" id="firstName" value="{{ old('firstName', $user->firstName ) }}" v-model="formsData.profile_form.firstName" required>
                                    </div>
                                    <span class="field-invalid-label" v-if="errors.firstName" v-html="outputErrors('firstName')"></span>
                                </div>
                                <div class="column col-5 col-4-md col-12-sm">
                                    <div class="form-group">
                                        <label for="lastName">@lang('student_profile.last_name')</label>
                                        <input type="text" name="lastName" id="lastName" value="{{ old( 'lastName', $user->lastName ) }}" v-model="formsData.profile_form.lastName" required>
                                    </div>
                                    <span class="field-invalid-label" v-if="errors.lastName" v-html="outputErrors('lastName')"></span>
                                </div>
                                <div class="column col-7 col-12-md">
                                    <div class="form-group">
                                        <label for="timezone">@lang('student_profile.timezone')</label>
                                        <select id="timezone" name="timezone" v-model="formsData.profile_form.timezone">
                                            <option>{{ old( 'timezone', $user->timezone ) }}</option>
                                            @foreach(config('timezones') as $tz => $tz_description)
                                                <option value="{{ $tz }}">{{ $tz_description }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-invalid-label" v-if="errors.timezone" v-html="outputErrors('timezone')"></span>
                                    </div>
                                </div>
                                <div class="column col-5 col-12-md">
                                    <div class="form-group">
                                        <label for="dateOfBirth">@lang('student_profile.birthday')</label>
                                        <input type="date" id="dateOfBirth" name="dateOfBirth" value="{{ old( 'dateOfBirth', $user->dateOfBirth ) }}" v-model="formsData.profile_form.dateOfBirth">
                                        <span class="field-invalid-label" v-if="errors.dateOfBirth" v-html="outputErrors('dateOfBirth')"></span>
                                    </div>
                                </div>
                                <div class="column col-12">
                                    <div class="form-group">
                                        <label for="information">@lang('student_profile.goals')</label>
                                        <textarea id="information" name="information" maxlength="400" rows="5" v-model="formsData.profile_form.information">{{ old( 'information', $user->information ) }}</textarea>
                                        <span class="field-invalid-label" v-if="errors.information" v-html="outputErrors('information')"></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>@lang('student_profile.class_details')</legend>
                            <div class="columns">
                                <div class="column col-6 col-12-md">
                                    <div class="form-group">
                                        <label for="skype">@lang('student_profile.skype')</label>
                                        <input type="text" id="skype" name="skype" value="{{ old( 'skype', $user->skype ) }}" v-model="formsData.profile_form.skype" required>
                                        <span class="field-invalid-label" v-if="errors.skype" v-html="outputErrors('skype')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-md">
                                    <div class="form-group">
                                        <label for="phone">@lang('student_profile.phone')</label>
                                        <input type="text" id="phone" name="phone" value="{{ old( 'phone', $user->phone ) }}" v-model="formsData.profile_form.phone">
                                        <span class="field-invalid-label" v-if="errors.phone" v-html="outputErrors('phone')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-md">
                                    <div class="form-group">
                                        <label for="paypalEmail">@lang('student_profile.pp_email')</label>
                                        <input type="text" id="paypalEmail" name="paypalEmail" value="{{ old( 'paypalEmail', $user->paypalEmail ) }}" placeholder="@lang('student_profile.placeholder_paypal')" v-model="formsData.profile_form.paypalEmail">
                                        <span class="field-invalid-label" v-if="errors.paypalEmail" v-html="outputErrors('paypalEmail')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-md">
                                    <div class="form-group">
                                        <label for="paypalEmail_confirmation">@lang('student_profile.confirm_pp_email')</label>
                                        <input type="text" id="paypalEmail_confirmation" name="paypalEmail_confirmation" value="" v-model="formsData.profile_form.paypalEmail_confirmation">
                                        <span class="field-invalid-label" v-if="errors.paypalEmail_confirmation" v-html="outputErrors('paypalEmail_confirmation')"></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>@lang('student_profile.current_location')</legend>
                            <div class="columns">
                                <div class="column col-6 col-12-md">
                                    <div class="form-group">
                                        <label for="country">@lang('student_profile.country')</label>
                                        <input type="text" id="country" name="country" value="{{ old( 'country', $user->country ) }}" v-model="formsData.profile_form.country">
                                        <span class="field-invalid-label" v-if="errors.country" v-html="outputErrors('country')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-md">
                                    <div class="form-group">
                                        <label for="state">@lang('student_profile.state')</label>
                                        <input type="text" id="state" name="state" value="{{ old( 'state', $user->state ) }}"  v-model="formsData.profile_form.state">
                                        <span class="field-invalid-label" v-if="errors.state" v-html="outputErrors('state')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-md">
                                    <div class="form-group">
                                        <label for="city">@lang('student_profile.city')</label>
                                        <input type="text" id="city" name="city" value="{{ old( 'city', $user->city ) }}" v-model="formsData.profile_form.city">
                                        <span class="field-invalid-label" v-if="errors.city" v-html="outputErrors('city')"></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="button primary"><i v-if="spin == 'profile_form'" class="fal fa-spinner fa-spin"></i> @lang('student_profile.save_profile')</button>
                        </div>
                        <div class="notification">@lang('student_profile.privacy_notion')</div>
                    </form>
                </div>

                <div id="security" class="column col-9 col-12-md">
                    <form action="" id="security_form" method="post" @submit.prevent="submitForm('security_form')">
                        <fieldset>
                            <legend>@lang('student_profile.change_password')</legend>
                            <div class="columns">
                                <div class="column col-12">
                                    <div class="form-group">
                                        <label for="current_password">@lang('student_profile.current_password')</label>
                                        <input type="password" id="current_password" name="current_password" value="" v-model="formsData.security_form.current_password">
                                        <span class="field-invalid-label" v-if="errors.current_password" v-html="outputErrors('current_password')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-sm">
                                    <div class="form-group">
                                        <label for="password">@lang('student_profile.new_password')</label>
                                        <input type="password" id="password" name="password" value="" v-model="formsData.security_form.password">
                                        <span class="field-invalid-label" v-if="errors.password" v-html="outputErrors('password')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-sm">
                                    <div class="form-group">
                                        <label for="password_confirmation">@lang('student_profile.confirm_new_password')</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" value="" v-model="formsData.security_form.password_confirmation">
                                        <span class="field-invalid-label" v-if="errors.password_confirmation" v-html="outputErrors('password_confirmation')"></span>
                                    </div>
                                </div>

                            </div>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="button primary"><i v-if="spin == 'security_form'" class="fal fa-spinner fa-spin"></i> @lang('student_profile.change_password')</button>
                        </div>
                    </form>
                    <h3>@lang('student_profile.auth_social_networks') <a href="#mod_snAuth" class="badge">?</a></h3>
                    <div class="form-group" style="margin-top: calc(0.5rem + 1vw);">
                        @php( $needle = 'google' )
                        <social-buttons networks="{{ json_encode(config('auth.social_networks')) }}"
                                        my-accounts="{{ json_encode( $user->snAccounts ) }}"
                                        link-stub="{{ route('sn_redirect', ['provider' => $needle, 'role' => $user->getType()]) }}"
                                        provider-stub="{{ $needle }}"
                        ></social-buttons>
                    </div>
                </div>

                <div id="settings" class="column col-9 col-12-md">
                    <form action="" name="settings_form" id="settings_form" method="post" @submit.prevent="submitForm('settings_form')">
                        <fieldset>
                            <legend>@lang('student_profile.class_settings')</legend>
                            <div class="columns">
                                <div class="column col-6 col-4-md col-12-sm">
                                    <div class="form-group">
                                        <label for="mailingList">@lang('student_profile.class_scheduling_notices') <a href="#mod_mailingList" class="badge">?</a></label>
                                        <select id="mailingList" name="mailingList" v-model="formsData.settings_form.mailingList">
                                            @foreach(['Active', 'Inactive'] as $notice)
                                                <option value="{{ $notice }}"{{ ($notice == old('mailingList', $user->mailingList)) ? 'selected' : '' }}>{{ __("student_profile")[strtolower($notice)] }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-invalid-label" v-if="errors.mailingList" v-text="outputErrors('mailingList')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-4-md col-12-sm">
                                    <div class="form-group">
                                        <label for="classLogMessages">@lang('student_profile.class_log_notice') <a href="#mod_classLogMessages" class="badge">?</a></label>
                                        <select id="classLogMessages" name="classLogMessages" v-model="formsData.settings_form.classLogMessages">
                                            @foreach(['Active', 'Inactive'] as $notice)
                                                <option value="{{ $notice }}"{{ ($notice == old('classLogMessages', $user->classLogMessages)) ? 'selected' : '' }}>{{ __("student_profile")[strtolower($notice)] }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-invalid-label" v-if="errors.classLogMessages" v-html="outputErrors('classLogMessages')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-4-md col-12-sm">
                                    <div class="form-group">
                                        <label for="classReminder">@lang('student_profile.class_reminder_notices') <a href="#mod_classReminder" class="badge">?</a></label>
                                        <select id="classReminder" name="classReminder" v-model="formsData.settings_form.classReminder">
                                            @foreach(['Active', 'Inactive'] as $notice)
                                                <option value="{{ $notice }}"{{ ($notice == old('classReminder', $user->classReminder)) ? 'selected' : '' }}>{{ __("student_profile")[strtolower($notice)] }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-invalid-label" v-if="errors.classReminder" v-html="outputErrors('classReminder')"></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="button primary"><i v-if="spin == 'settings_form'" class="fal fa-spinner fa-spin"></i> @lang('student_profile.save_settings')</button>
                        </div>
                    </form>
                </div>
            </div>

            <simple-modal ok="@lang('student_profile.js.ok_thanks')"></simple-modal>

            @php ($imgSize = config( 'filesystems.maxsize.studentProfileImageXY', [300, 300] ))

            <file-upload-modal header="@lang('student_profile.upload_image')"
                         btn_select="@lang('student_profile.select_image')"
                         img-style="height: {{ $imgSize[1] }}px; width: {{ $imgSize[0] }}px;"
                         :preview="true"
                         :max-size="{{ config( 'filesystems.maxsize.studentProfileImage', 512 ) }}"
                         types="image/jpeg|image/jpg|image/png|image/gif"
                         spinner=" <i class='fal fa-spinner fa-spin'></i>"
                         @file_selected="submitImage"
            >@lang('student_profile.profile_image_notice', ['max' => config( 'filesystems.maxsize.studentProfileImage', 500 ), 'pixels' => implode('x', $imgSize)])</file-upload-modal>


        </div>
    </main>
@endsection