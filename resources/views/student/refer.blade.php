@extends('layout.zedalabs.student')

@push('extra_item_after')
    <a href="{{ route('students', ['controller' => 'schedule-class']) }}" class="button primary">@lang( 'students_dashboard.schedule_class' ) <i class="far fa-plus"></i></a>
@endpush

@php
    $bonus = ( !empty($programConfig['referrer_bonus_amount'] ))
            ? sprintf('$%s US', number_format( $programConfig['referrer_bonus_amount'], 2 ) )
            : $programConfig['referrer_bonus_percent'] . '%';

    $coupon_code = $user->refCoupon() ? $user->refCoupon()->code : '';
@endphp

@section('main')
    <main id="root" v-cloak>
        <div class="container">

            @include( 'layout.zedalabs.widgets.dashboard_header', [ 'role' => 'students', 'dashboard_header' => __( 'student_refer.refer_friend' ) ] )

            <nav class="tab-navigation mb-lg">
                <a v-for="tab in ['home', 'referrals', 'bonus_details', 'invitations']"
                   href="#"
                   :class="{active: activeTab === tab}"
                   v-text="sh.__(tab)"
                   @click.prevent="setActiveTab(tab)"
                ></a>
            </nav>

            <div class="tab-content">
                <div id="home" v-if="activeTab === 'home'">
                    <img class="position-centered" src="{{ asset('/img/live-lingua-referral-program.jpg') }}">
                    <br><br>
                    <h4>@lang('student_refer.win_to_win')</h4>
                    <p>
                        @lang( 'student_refer.benefits', [ 'percent' => $programConfig['referral_discount_percent'], 'bonus' => $bonus] )
                    </p>

                    <hr>

                    <h3>1) @lang('student_refer.share')</h3>
                    <p>@lang('student_refer.way_to_share')</p>

                    <div class="text-align-center">
                        <span class="chip">{{ $coupon_code }}</span>
                    </div>

                    <hr>
                    <h3>2) @lang('student_refer.link')</h3>
                    <p>@lang('student_refer.code_via_email')</p>

                    <pre>
                        <code data-lang="@lang('student_refer.click2copy')" class="copy_to_cb text-align-center hljs bash">{{ route('page', ['controller' => 'trial-lesson?' ]) . config('referral_program.referral_code_cookie_name') . '=' . $coupon_code }}</code>
                    </pre>


                    <hr>
                    <h3>3) @lang('student_refer.by_email')</h3>

                    <p>@lang('student_refer.fill_email_form')</p>

                    <refer-form :max="{{ $programConfig['referral_emails_max'] }}"
                                :sent="{{ $user->referralInvitationsTodayTotal() }}"
                                @success="sentSuccess"
                                @err="errors"
                    ></refer-form>

                </div>
                <div id="referrals" v-if="activeTab === 'referrals'">

                    <refer-color-key :data="referralColorKey"
                                     v-on:colorize="filterReferralsTable"
                    >@lang('student_refer.color_key')</refer-color-key>

                    <vdtnet-table :id="referralsTableId"
                                  class-name="table-striped"
                                  :opts="referralsOpts"
                                  :fields="referralsFields"
                    ></vdtnet-table>

                </div>
                <div id="bonus_details" v-if="activeTab === 'bonus_details'">

                    <refer-color-key :data="creditsColorKey"
                                     v-on:colorize="filterCreditsTable"
                    >@lang('student_refer.color_key')</refer-color-key>
                    <h4 v-text="remainingCredits"></h4>

                    <vdtnet-table :id="creditsTableId"
                                  class-name="table-striped"
                                  :opts="creditsOpts"
                                  :fields="creditsFields"
                    ></vdtnet-table>

                </div>
                <div id="invitations" v-if="activeTab === 'invitations'">

                    <refer-color-key :data="invitationsColorKey"
                                     v-on:colorize="filterInvitationsTable"
                    >@lang('student_refer.color_key')</refer-color-key>

                    <vdtnet-table :id="invitationsTableId"
                                  class-name="table-striped"
                                  :opts="invitationsOpts"
                                  :fields="invitationsFields"
                    ></vdtnet-table>

                </div>
            </div>

        </div>
    </main>
@endsection