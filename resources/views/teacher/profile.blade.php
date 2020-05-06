@extends('layout.zedalabs.teacher')

@php
    $tplConfig->page_meta_title = trans('teacher_profile.teacher_profile');
    $thisYear = date('Y');
    $ajax_url = url( "ajax/teacher/{$user->getPK()}");
@endphp

@section('head')
    @parent
    <style>
        .twitter-typeahead {
            display: block !important;
        }
        .maxlength-count.input {
            color: #008964;
        }
    </style>

    <script>
        var initImage = '{{ asset($user->getProfileImage() ) }}',
            agesTaught = {!! json_encode($user->agesTaught) !!},
            coursesTaught = {!! json_encode($user->coursesTaught) !!},
            languagesTaught = {!! json_encode($user->languagesTaught) !!};
    </script>
@endsection

@section('main')

    <main>

        <div id="root" class="container" v-cloak>

            @include( 'layout.zedalabs.widgets.dashboard_header', [ 'role' => 'teachers', 'dashboard_header' => __( 'teacher_profile.profile' ) ] )

            <nav class="tab-navigation mb-lg">
                <a href="#personal" class="active">@lang('teacher_profile.personal_info')</a>
                <a href="#profile">@lang('teacher_profile.profile')</a>
                <a href="#practice">@lang('teacher_profile.practice')</a>
                <a href="#security">@lang('teacher_profile.security')</a>
            </nav>

            <div class="columns">

                <profile-image email="{{ $user->getEmail() }}"
                               :fullname="fullname"
                               :image="profileImage"
                               slug="{{ asset( 'img/profiles/no-profile-image.jpg' ) }}"
                ></profile-image>

                <div id="personal" class="column col-9 col-12-md">

                    <form action="" id="personal_form" method="post" @submit.prevent="submitForm('personal_form')">
                        <h3 class="section-label">@lang('teacher_profile.your_details')</h3>
                        <div class="columns">
                            <div class="column col-2 col-4-md col-12-sm">
                                <div class="form-group">
                                    <label for="title">@lang('teacher_profile.title')</label>
                                    <select id="title" name="title" v-model="formsData.personal_form.title">
                                        @foreach(__('student_profile.titles') as $key => $ttl)
                                        <option value="{{ $key }}"{{ ($key == old('title', $user->title)) ? 'selected' : '' }}>{{ $ttl }}</option>
                                        @endforeach
                                    </select>
                                    <span class="field-invalid-label" v-if="errors.title" v-html="outputErrors('title')"></span>
                                </div>
                            </div>
                            <div class="column col-5 col-4-md col-12-sm">
                                <div class="form-group">
                                    <label for="firstName">@lang('teacher_profile.first_name')</label>
                                    <input type="text" name="firstName" id="firstName" value="{{ old('firstName', $user->firstName ) }}" v-model="formsData.personal_form.firstName" required>
                                </div>
                                <span class="field-invalid-label" v-if="errors.firstName" v-html="outputErrors('firstName')"></span>
                            </div>
                            <div class="column col-5 col-4-md col-12-sm">
                                <div class="form-group">
                                    <label for="lastName">@lang('teacher_profile.last_name')</label>
                                    <input type="text" name="lastName" id="lastName" value="{{ old( 'lastName', $user->lastName ) }}" v-model="formsData.personal_form.lastName" required>
                                </div>
                                <span class="field-invalid-label" v-if="errors.lastName" v-html="outputErrors('lastName')"></span>
                            </div>
                            <div class="column col-7 col-12-md">
                                <div class="form-group">
                                    <label for="timezone">@lang('teacher_profile.timezone')</label>
                                    <select id="timezone" name="timezone" v-model="formsData.personal_form.timezone">
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
                                    <label for="skype">@lang('teacher_profile.skype') <a href="#mod_skype" class="badge">?</a></label>
                                    <input type="text" id="skype" name="skype" value="{{ old( 'skype', $user->skype ) }}" v-model="formsData.personal_form.skype" required>
                                    <span class="field-invalid-label" v-if="errors.skype" v-html="outputErrors('skype')"></span>
                                </div>
                            </div>
                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="phone">@lang('teacher_profile.phone') <a href="#mod_phone" class="badge">?</a></label>
                                    <input type="text" id="phone" name="phone" value="{{ old( 'phone', $user->phone ) }}" v-model="formsData.personal_form.phone">
                                    <span class="field-invalid-label" v-if="errors.phone" v-html="outputErrors('phone')"></span>
                                </div>
                            </div>
                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="newStudents">@lang('teacher_profile.new_students') <a href="#mod_newStudents" class="badge">?</a></label>
                                    <select id="newStudents" name="newStudents" v-model="formsData.personal_form.newStudents">
                                        @foreach( __('teacher_profile.new_student_opts') as $key => $val)
                                            <option value="{{ $key }}"{{ ($key == old('newStudents', $user->newStudents)) ? ' selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                    <span class="field-invalid-label" v-if="errors.newStudents" v-html="outputErrors('newStudents')"></span>
                                </div>
                            </div>
                        </div>

                        <h3 class="section-label mt-lg">@lang('teacher_profile.original_location') <a href="#mod_origin" class="badge">?</a></h3>
                        <div class="columns">
                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="countryOrigin">@lang('teacher_profile.origin_country')</label>
                                    <input type="text" id="countryOrigin" name="countryOrigin" value="{{ old( 'countryOrigin', $user->countryOrigin ) }}" v-model="formsData.personal_form.countryOrigin">
                                    <span class="field-invalid-label" v-if="errors.countryOrigin" v-html="outputErrors('countryOrigin')"></span>
                                </div>
                            </div>
                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="stateOrigin">@lang('teacher_profile.origin_state')</label>
                                    <input type="text" id="stateOrigin" name="stateOrigin" value="{{ old( 'stateOrigin', $user->stateOrigin ) }}"  v-model="formsData.personal_form.stateOrigin">
                                    <span class="field-invalid-label" v-if="errors.stateOrigin" v-html="outputErrors('stateOrigin')"></span>
                                </div>
                            </div>
                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="cityOrigin">@lang('teacher_profile.origin_city')</label>
                                    <input type="text" id="cityOrigin" name="cityOrigin" value="{{ old( 'cityOrigin', $user->cityOrigin ) }}" v-model="formsData.personal_form.cityOrigin">
                                    <span class="field-invalid-label" v-if="errors.cityOrigin" v-html="outputErrors('cityOrigin')"></span>
                                </div>
                            </div>
                        </div>

                        <h3 class="section-label mt-lg">@lang('teacher_profile.current_location') <a href="#mod_residence" class="badge">?</a></h3>
                        <div class="columns">

                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="street1Residence">@lang('teacher_profile.street1')</label>
                                    <input type="text" id="street1Residence" name="street1Residence" value="{{ old( 'street1Residence', $user->street1Residence ) }}" v-model="formsData.personal_form.street1Residence">
                                    <span class="field-invalid-label" v-if="errors.street1Residence" v-html="outputErrors('street1Residence')"></span>
                                </div>
                            </div>
                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="street2Residence">@lang('teacher_profile.street2')</label>
                                    <input type="text" id="street2Residence" name="street2Residence" value="{{ old( 'street2Residence', $user->street2Residence ) }}"  v-model="formsData.personal_form.street2Residence">
                                    <span class="field-invalid-label" v-if="errors.street2Residence" v-html="outputErrors('street2Residence')"></span>
                                </div>
                            </div>

                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="countryResidence">@lang('teacher_profile.country')</label>
                                    <input type="text" id="countryResidence" name="countryResidence" value="{{ old( 'countryResidence', $user->countryResidence ) }}" v-model="formsData.personal_form.countryResidence">
                                    <span class="field-invalid-label" v-if="errors.countryResidence" v-html="outputErrors('countryResidence')"></span>
                                </div>
                            </div>
                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="stateResidence">@lang('teacher_profile.state')</label>
                                    <input type="text" id="stateResidence" name="stateResidence" value="{{ old( 'stateResidence', $user->stateResidence ) }}"  v-model="formsData.personal_form.stateResidence">
                                    <span class="field-invalid-label" v-if="errors.stateResidence" v-html="outputErrors('stateResidence')"></span>
                                </div>
                            </div>
                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="cityResidence">@lang('teacher_profile.city')</label>
                                    <input type="text" id="cityResidence" name="cityResidence" value="{{ old( 'cityResidence', $user->cityResidence ) }}" v-model="formsData.personal_form.cityResidence">
                                    <span class="field-invalid-label" v-if="errors.cityResidence" v-html="outputErrors('cityResidence')"></span>
                                </div>
                            </div>
                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="zipResidence">@lang('teacher_profile.postal_code')</label>
                                    <input type="text" id="zipResidence" name="zipResidence" value="{{ old( 'zipResidence', $user->zipResidence ) }}" v-model="formsData.personal_form.zipResidence">
                                    <span class="field-invalid-label" v-if="errors.zipResidence" v-html="outputErrors('zipResidence')"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="button primary"><i v-if="spin == 'personal_form'" class="fal fa-spinner fa-spin"></i> @lang('teacher_profile.save_personal')</button>
                        </div>
                        <div class="notification">@lang('student_profile.privacy_notion')</div>
                    </form>
                </div>

                <div id="profile" class="column col-9 col-12-md">

                    <form action="" id="profile_form" method="post" @submit.prevent="submitForm('profile_form')">

                        <div class="columns">

                                <div class="column col-12">
                                    <fieldset>
                                        <legend>@lang('teacher_profile.languages')</legend>
                                    </fieldset>
                                </div>

                                <div class="column col-4 col-12-md">
                                    <div class="form-group">
                                        <label for="languagesTaught">@lang('teacher_profile.languages_taught') <a href="#mod_languages_taught" class="badge">?</a></label>
                                        <div>
                                            @foreach($user->languagesTaught as $lang)
                                                <span class="chip">{{ $lang }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="column col-8 col-12-md">
                                    <div class="form-group">
                                        <label for="languagesSpoken">@lang('teacher_profile.languages_spoken') <a href="#mod_languages_spoken" class="badge">?</a></label>
                                        <input type="text" id="languagesSpoken" name="languagesSpoken" value="{{ old( 'languagesSpoken', $user->languagesSpoken ) }}" v-model="formsData.profile_form.languagesSpoken">
                                        <span class="field-invalid-label" v-if="errors.languagesSpoken" v-html="outputErrors('languagesSpoken')"></span>
                                    </div>
                                </div>
                            <div class="column col-12">
                                <fieldset class="form-group">
                                    <legend>@lang('teacher_profile.ages_taught') <a href="#mod_ages_taught" class="badge">?</a></legend>
                                    <div>
                                        <label style="padding-right: 24px;" v-for="(desc, age) in sh.__('ages')">
                                            <input type="checkbox"
                                                   name="agesTaught"
                                                   :value="age"
                                                   v-model="formsData.profile_form.agesTaught"
                                                   @click="errors.agesTaught = ''"
                                            /> @{{ desc }}
                                        </label>
                                    </div>
                                    <span class="field-invalid-label" v-if="errors.agesTaught" v-html="outputErrors('agesTaught')"></span>
                                </fieldset>
                            </div>

                            <div class="column col-12">
                                <fieldset class="form-group">
                                    <legend>@lang('teacher_profile.specialized_courses') <a href="#mod_courses_taught" class="badge">?</a></legend>
                                    <fieldset v-for="(courses, lang) in specializedCourses">
                                        <span class="section-label" v-text="lang"></span>
                                        <div>
                                            <label v-for="c in courses" class="col-6 col-12-md">
                                                <input type="checkbox"
                                                       name="coursesTaught"
                                                       :value="c"
                                                       v-model="formsData.profile_form.coursesTaught"
                                                       @click="errors.coursesTaught = ''"
                                                /> @{{ c }}</label>
                                        </div>
                                    </fieldset>
                                    <span class="field-invalid-label" v-if="errors.coursesTaught" v-html="outputErrors('coursesTaught')"></span>
                                </fieldset>

                                <div class="form-group">
                                    <button type="submit" class="button primary"><i v-if="spin == 'profile_form'" class="fal fa-spinner fa-spin"></i> @lang('teacher_profile.save_profile')</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

                <div id="practice" class="column col-9 col-12-md">

                    <form action="" id="practice_form" method="post" @submit.prevent="submitForm('practice_form')">

                        <div class="columns">
                            <div class="column col-12">
                                <legend>@lang('teacher_profile.experiences')</legend>

                                <div class="form-group">
                                    <label for="teacherIntroduction">@lang('teacher_profile.teacher_introduction') <a href="#mod_introduction" class="badge">?</a></label>
                                    <textarea id="teacherIntroduction" name="teacherIntroduction" minlength="150" rows="5" v-model="formsData.practice_form.teacherIntroduction">{{ old( 'teacherIntroduction', $user->teacherIntroduction ) }}</textarea>
                                    <span class="field-invalid-label" v-if="errors.teacherIntroduction" v-html="outputErrors('teacherIntroduction')"></span>
                                </div>

                                <div class="form-group col-3 col-6-md col-12-sm">
                                    <label for="startedTeaching">@lang('teacher_profile.started_in')</label>
                                    <input type="number" min="{{ $thisYear - 90 }}" max="{{ $thisYear }}" step="1" value="{{ old('startedTeaching', $user->startedTeaching ) }}" id="startedTeaching" name="startedTeaching" v-model="formsData.practice_form.startedTeaching" />
                                    <span class="field-invalid-label" v-if="errors.startedTeaching" v-html="outputErrors('startedTeaching')"></span>
                                </div>
                            </div>

                            <div class="column col-12">
                                <div class="form-group">
                                    <label for="education">@lang('teacher_profile.education')</label>
                                    <textarea id="education" name="education" maxlength="2000" rows="5" v-model="formsData.practice_form.education">{{ old( 'education', $user->education ) }}</textarea>
                                    <span class="field-invalid-label" v-if="errors.education" v-html="outputErrors('education')"></span>
                                </div>
                            </div>

                            <div class="column col-6 col-12-md">
                                <div class="form-group">
                                    <label for="teachingStyle">@lang('teacher_profile.teaching_style') <a href="#mod_teaching_style" class="badge">?</a></label>
                                    <select id="teachingStyle" name="teachingStyle" v-model="formsData.practice_form.teachingStyle">
                                        @foreach( __('teacher_profile.teaching_style_opts') as $val)
                                            <option{{ ($val == old('teachingStyle', $user->teachingStyle)) ? ' selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                    <span class="field-invalid-label" v-if="errors.teachingStyle" v-html="outputErrors('teachingStyle')"></span>
                                </div>
                            </div>

                            <div class="column col-12">
                                <div class="form-group">
                                    <label for="workExperience">@lang('teacher_profile.teaching_methods') <a href="#mod_teaching_methods" class="badge">?</a></label>
                                    <textarea id="workExperience" name="workExperience" maxlength="65535" rows="5" v-model="formsData.practice_form.workExperience">{{ old( 'workExperience', $user->workExperience ) }}</textarea>
                                    <span class="field-invalid-label" v-if="errors.workExperience" v-html="outputErrors('workExperience')"></span>
                                </div>
                            </div>

                            <div class="column col-12">
                                <fieldset>
                                    <legend>@lang('teacher_profile.fun_facts')</legend>
                                    
                                    <div class="form-group">
                                        <label for="hobbies">@lang('teacher_profile.hobbies') <a href="#mod_hobbies" class="badge">?</a></label>
                                        <textarea id="hobbies" name="hobbies" maxlength="1000" rows="5" v-model="formsData.practice_form.hobbies">{{ old( 'hobbies', $user->hobbies ) }}</textarea>
                                        <span class="field-invalid-label" v-if="errors.hobbies" v-html="outputErrors('hobbies')"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="favoriteWebsite">@lang('teacher_profile.favorite_website') <a href="#mod_website" class="badge">?</a></label>
                                        <input type="text" maxlength="255" value="{{ old('favoriteWebsite', $user->favoriteWebsite ) }}" id="favoriteWebsite" name="favoriteWebsite" v-model="formsData.practice_form.favoriteWebsite" />
                                        <span class="field-invalid-label" v-if="errors.favoriteWebsite" v-html="outputErrors('favoriteWebsite')"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="favoriteMovie">@lang('teacher_profile.favorite_movie')</label>
                                        <input type="text" maxlength="255" value="{{ old('favoriteMovie', $user->favoriteMovie ) }}" id="favoriteMovie" name="favoriteMovie" v-model="formsData.practice_form.favoriteMovie" />
                                        <span class="field-invalid-label" v-if="errors.favoriteMovie" v-html="outputErrors('favoriteMovie')"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="favoriteFood">@lang('teacher_profile.favorite_food')</label>
                                        <input type="text" maxlength="255" value="{{ old('favoriteFood', $user->favoriteFood ) }}" id="favoriteFood" name="favoriteFood" v-model="formsData.practice_form.favoriteFood" />
                                        <span class="field-invalid-label" v-if="errors.favoriteFood" v-html="outputErrors('favoriteFood')"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="countriesVisited">@lang('teacher_profile.countries_visited') <a href="#mod_countries_visited" class="badge">?</a></label>
                                        <textarea id="countriesVisited" name="countriesVisited" maxlength="500" rows="2" v-model="formsData.practice_form.countriesVisited">{{ old( 'countriesVisited', $user->countriesVisited ) }}</textarea>
                                        <span class="field-invalid-label" v-if="errors.countriesVisited" v-html="outputErrors('countriesVisited')"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="bucketList">@lang('teacher_profile.bucket_list') <a href="#mod_bucket_list" class="badge">?</a></label>
                                        <textarea id="bucketList" name="bucketList" maxlength="255" rows="2" v-model="formsData.practice_form.bucketList">{{ old( 'bucketList', $user->bucketList ) }}</textarea>
                                        <span class="field-invalid-label" v-if="errors.bucketList" v-html="outputErrors('bucketList')"></span>
                                    </div>

                                </fieldset>

                                <div class="form-group">
                                    <button type="submit" class="button primary"><i v-if="spin == 'practice_form'" class="fal fa-spinner fa-spin"></i> @lang('teacher_profile.save_practice')</button>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>

                <div id="security" class="column col-9 col-12-md">
                    <form action="" class="mb-xl" id="email_form" method="post" @submit.prevent="submitForm('email_form')">
                        <fieldset>
                            <legend>@lang('teacher_profile.change_email') <a href="#mod_email" class="badge">?</a></legend>
                            <div class="columns">

                                <div class="column col-6 col-12-sm">
                                    <div class="form-group">
                                        <label for="email">@lang('teacher_profile.email')</label>
                                        <input type="email" id="email" name="email" value="{{ old( 'email', $user->email ) }}" v-model="formsData.email_form.email">
                                        <span class="field-invalid-label" v-if="errors.email" v-html="outputErrors('email')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-sm">
                                    <div class="form-group">
                                        <label for="email_confirmation">@lang('teacher_profile.confirm_email')</label>
                                        <input type="email" id="email_confirmation" name="email_confirmation" v-model="formsData.email_form.email_confirmation">
                                        <span class="field-invalid-label" v-if="errors.email_confirmation" v-html="outputErrors('email_confirmation')"></span>
                                    </div>
                                </div>

                            </div>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="button primary"><i v-if="spin == 'email_form'" class="fal fa-spinner fa-spin"></i> @lang('teacher_profile.change_email')</button>
                        </div>
                    </form>
                    <form action="" class="mb-xl" id="paypal_form" method="post" @submit.prevent="submitForm('paypal_form')">
                        <fieldset>
                            <legend>@lang('teacher_profile.payment_details') <a href="#mod_paypalEmail" class="badge">?</a></legend>
                            <div class="columns">

                                <div class="column col-6 col-12-md">
                                    <div class="form-group">
                                        <label for="paymentEmail">@lang('teacher_profile.pp_email')</label>
                                        <input type="text" id="paymentEmail" name="paymentEmail" value="{{ old( 'paymentEmail', $user->paymentEmail ) }}" placeholder="@lang('teacher_profile.placeholder_paypal')" v-model="formsData.paypal_form.paymentEmail">
                                        <span class="field-invalid-label" v-if="errors.paymentEmail" v-html="outputErrors('paymentEmail')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-md">
                                    <div class="form-group">
                                        <label for="paymentEmail_confirmation">@lang('teacher_profile.confirm_pp_email')</label>
                                        <input type="text" id="paymentEmail_confirmation" name="paymentEmail_confirmation" value="" v-model="formsData.paypal_form.paymentEmail_confirmation">
                                        <span class="field-invalid-label" v-if="errors.paymentEmail_confirmation" v-html="outputErrors('paymentEmail_confirmation')"></span>
                                    </div>
                                </div>

                            </div>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="button primary"><i v-if="spin == 'paypal_form'" class="fal fa-spinner fa-spin"></i> @lang('teacher_profile.change_payment_email')</button>
                        </div>
                    </form>
                    <form action="" id="security_form" method="post" @submit.prevent="submitForm('security_form')">
                        <fieldset>
                            <legend>@lang('teacher_profile.change_password') <a href="#mod_security" class="badge">?</a></legend>
                            <div class="columns">

                                <div class="column col-12">
                                    <div class="form-group">
                                        <label for="current_password">@lang('teacher_profile.current_password')</label>
                                        <input type="password" id="current_password" name="current_password" value="" v-model="formsData.security_form.current_password">
                                        <span class="field-invalid-label" v-if="errors.current_password" v-html="outputErrors('current_password')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-sm">
                                    <div class="form-group">
                                        <label for="password">@lang('teacher_profile.new_password')</label>
                                        <input type="password" id="password" name="password" value="" v-model="formsData.security_form.password">
                                        <span class="field-invalid-label" v-if="errors.password" v-html="outputErrors('password')"></span>
                                    </div>
                                </div>
                                <div class="column col-6 col-12-sm">
                                    <div class="form-group">
                                        <label for="password_confirmation">@lang('teacher_profile.confirm_new_password')</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" value="" v-model="formsData.security_form.password_confirmation">
                                        <span class="field-invalid-label" v-if="errors.password_confirmation" v-html="outputErrors('password_confirmation')"></span>
                                    </div>
                                </div>

                            </div>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="button primary"><i v-if="spin == 'security_form'" class="fal fa-spinner fa-spin"></i> @lang('teacher_profile.change_password')</button>
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