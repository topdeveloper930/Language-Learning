@extends('layout.zedalabs.student')

@php
    $tplConfig->page_meta_title = trans('student_purchase.purchase_classes');
@endphp

@section('head')
    @parent
    <script>
        var defaultPaymentMethod = '{{ $defaultPaymentMethod }}',
            minTransferHours = {{ config('payment.transfer_min_purchase') }},
            coupon_code = '{{ $coupon_code }}',
            stripe_key = '{{ config('services.stripe.key') }}',
            old = {!! json_encode( $defaults ) !!},
            ajax_url = '{{ route('students', ['controller' => 'purchase']) }}';
    </script>
@endsection

@section('main')
    <main id="root" v-cloak>

        @include( 'layout.zedalabs.widgets.page_header', [ 'page_header' => __( 'student_purchase.purchase_classes' ), 'page_subtitle' => __( 'student_purchase.purchase' ) ] )

        <div class="container lg">
            <form action="" id="" method="post" class="mb-lg">
                <h2 class="mb-md h3">@lang('student_purchase.course_language')</h2>

                <div class="columns">
                    <div class="form-group column col-6 col-12-md">
                        <label for="language">@lang('student_purchase.language') <a href="#mod_language" class="badge">?</a></label>
                        <select id="language" name="language" v-model="language" @change="onLanguageChange">
                            <option value="">@lang('student_purchase.choose')</option>
                            @foreach(config('main.languages') as $language)
                            <option>{{ $language }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group column col-6 col-12-md">
                        <label for="program">@lang('student_purchase.program') <a href="#mod_program" class="badge">?</a></label>
                        <select id="program" name="program" v-model="selectedCourse" @change="delete errorsBag.program">
                            <option v-for="c in courses">@{{ c.courseType }}</option>
                        </select>
                        <span class="field-invalid-label" v-if="errorsBag.program" v-html="errorsBag.program"></span>
                    </div>
                    <div class="form-group column col-6 col-12-md">
                        <label for="hoursPurchased">@lang('student_purchase.number_of_hours') <a href="#mod_hoursPurchased" class="badge">?</a></label>
                        <input type="number" step="0.25" min="0" id="hoursPurchased" data-hours="{{ config('main.purchase_hours') }}" v-model="hoursPurchased" name="hoursPurchased" @change="initUpdate('hours')"/>
                        <span class="field-invalid-label" v-if="errorsBag.hours" v-html="errorsBag.hours"></span>
                    </div>
                    <div class="form-group column col-6 col-12-md">
                        <label for="numStudents">@lang('student_purchase.class_size') <a href="#mod_classSize" class="badge">?</a></label>
                        <select id="numStudents" name="numStudents" v-model="numStudents" @change="initUpdate">
                            @foreach(config('main.num_students') as $key => $txt)
                                <option value="{{ $key }}">{{ $txt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group column col-6 col-12-md">
                        <div class="input-group">
                            <label for="coupon">@lang('student_purchase.coupon') <a href="#mod_coupon" class="badge">?</a></label>
                            <input type="text" id="coupon" :class="{invalid:errorsBag.coupon}" name="coupon" v-model="coupon" @change="initUpdate" @paste="initUpdate" @keyup="delete errorsBag.coupon_code">
                            <span class="field-invalid-label" v-if="errorsBag.coupon_code" v-html="errorsBag.coupon_code"></span>
                        </div>
                    </div>
                    <div class="form-group column col-6 col-12-md">
                        <label for="giftCard">@lang('student_purchase.gift_card', ['url' => route('page', ['controller' => 'gift-cards'])]) <a href="#mod_giftCard" class="badge">?</a></label>
                        <input type="text" id="giftCard" name="coupon" v-model="giftCard" @change="initUpdate" @keyup="delete errorsBag.giftcard_code">
                        <span class="field-invalid-label" v-if="errorsBag.giftcard_code" v-html="errorsBag.giftcard_code"></span>
                    </div>
                </div>

                <hr>

                <h2 class="mb-md h3">@lang('student_purchase.payment')</h2>

                <div class="tab-navigation mb-md">
                    @foreach( config('payment.methods') as $method )
                    <a href="#" :class="{active: paymentMethod === '{{ $method }}'}" @click.prevent="setMethod('{{ $method }}')">@lang('student_purchase.' . $method)</a>
                    @endforeach
                </div>

                <div class="columns" v-if="'check' === paymentMethod">
                    <p>@lang('student_purchase.check_selected', ['hours' => '<span v-text="hoursPurchased"></span>', 'course' => '<span v-text="purchaseItem"></span>'])</p>
                    <ol>
                        <li>@lang('student_purchase.enough_costs', ['sum' => '<span v-text="total.toFixed(2)"></span>'])</li>
                        <li>
                            @lang('student_purchase.check_out_to', ['to' => config('payment.check_out_to')])
                            <address style="padding:16px">@dboutput( config('payment.check_address') )</address>
                        </li>
                        <li>@lang('student_purchase.mail_us', ['email' => config('legacy.globals.mainEmail')])</li>
                        <li>@lang('student_purchase.term_deposit')</li>
                        <li>@lang('student_purchase.we_check')</li>
                    </ol>
                </div>
                <div class="columns" v-else-if="'transfer' === paymentMethod">
                    <div  class="notification" v-if="{{ config('payment.transfer_min_purchase') }} > hours">@lang('student_purchase.transfer_min_purchase', ['hours' => config('payment.transfer_min_purchase')])</div>
                    <div v-else>
                        <p>@lang('student_purchase.transfer_selected', ['hours' => '<span v-text="hoursPurchased"></span>', 'course' => '<span v-text="purchaseItem"></span>'])</p>
                        <ol>
                            <li>@lang('student_purchase.enough_costs', ['sum' => '<span v-text="total.toFixed(2)"></span>'])</li>
                            <li>
                                @lang('student_purchase.initiate_transfer')
                                <address style="padding:16px">@dboutput( config('payment.wire_transfer_address') )</address>
                            </li>
                            <li>@lang('student_purchase.transfer_initiated', ['email' => config('legacy.globals.mainEmail')])</li>
                            <li>@lang('student_purchase.we_transfer')</li>
                        </ol>
                    </div>
                </div>

                <pricing-breakdown :cost="paymentData.cost"
                                   :referral_credits="paymentData.referral_credits"
                                   :coupon="paymentData.coupon"
                                   :discount="paymentData.discount"
                                   :giftcard="paymentData.giftcard"
                                   :total="paymentData.total"
                ><small>@lang('student_purchase.review_costs', [ 'link' => route('page', ['controller' => 'pricing']) ])</small></pricing-breakdown>

                <div class="form-group text-align-center">
                    <button type="submit" class="button primary" @click.prevent="sendPayment"><i v-if="loading" class="fal fa-spinner fa-spin"></i> @{{ submitText }}</button>
                </div>

            </form>

            <div class="notification">@lang('student_purchase.notification', ['terms' => route('page', ['controller' => 'terms-conditions']), 'owner' => config('payment.owner_company')])</div>

            <simple-modal ok="@lang('student_profile.js.ok_thanks')"></simple-modal>

        </div>
    </main>
@endsection