@extends('layout.zedalabs.student')

@php
    $tplConfig->page_meta_title = $page_subtitle = __( 'purchase_result.' . strtolower( $transaction->paymentStatus ) );

    $surveymonkeyScript = sprintf(
    	'(function(t,e,s,n){var o,a,c;t.SMCX=t.SMCX||[],e.getElementById(n)||(o=e.getElementsByTagName(s),a=o[o.length-1],c=e.createElement(s),c.type="text/javascript",c.async=!0,c.id=n,c.src=["https:"===location.protocol?"https://":"http://","widget.surveymonkey.com/collect/website/js/%s.js"].join(""),a.parentNode.insertBefore(c,a))})(window,document,"script","smcx-sdk");',
    	config('surveymonkey.purchase_success')[ intval($user->classCreditLogs->count() > 1) ]
    );

    if( $nonInstantPay ) $page_subtitle .= ' | ' . $transaction->paymentGateway;
@endphp

@section('head')
    @parent
    <style>
        .smcx-widget {margin: 0 auto !important;}
        @media screen and (max-width: 992px) {
            .column[class*="col-"] {
                width: 100%;
            }
        }
    </style>
    <script>
        var stripe_key = '{{ config('services.stripe.key') }}',
            initVue = {{ 1 * (App\Transaction::PENDING == $transaction->paymentStatus) }};
    </script>
@endsection

@section('main')
    <main>

        @include( 'layout.zedalabs.widgets.page_header', [ 'page_header' => $page_title, 'page_subtitle' => $page_subtitle ] )

        <div class="container lg text-align-center">
            @if( $transaction->purchase )
                @php( $balance = $transaction->purchase->balance )

                <div class="accordion mb-lg">
                    <a href="javascript:void(0);" class="accordion-title">@lang('purchase_result.details')</a>
                    <div class="accordion-content">
                        <table class="table-striped text-align-center">
                            <tr>
                                <td>@lang('purchase_result.course')</td>
                                <td>{{ trans_choice( 'purchase_result.course_type', $transaction->purchase->numStudents, ['course' => $transaction->purchase->courseType ]) }}</td>
                            </tr>
                            <tr>
                                <td>@lang('purchase_result.hours_cnt')</td>
                                <td>{{ trans_choice('purchase_result.hours', $transaction->hours) }}</td>
                            </tr>
                            <tr>
                                <td>@lang('purchase_result.cost')</td>
                                <td>${{ number_format( $balance['cost'], 2 ) }} US</td>
                            </tr>
                            @if($balance['discount'])
                                <tr>
                                    <td>@lang('purchase_result.coupon_for', ['for' => $balance['coupon']])</td>
                                    <td>-${{ number_format( $balance['discount'], 2 ) }} US</td>
                                </tr>
                            @endif
                            @if($balance['referral_credits'])
                                <tr>
                                    <td>@lang('purchase_result.referral_credits')</td>
                                    <td>-${{ number_format( $balance['referral_credits'], 2 ) }} US</td>
                                </tr>
                            @endif
                            <tfoot>
                            <tr>
                                <th>@lang('purchase_result.total')</th>
                                <th>${{ number_format( $balance['total'], 2 ) }} US</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @if( !strcasecmp(App\Transaction::COMPLETED, $transaction->paymentStatus ) )

                    <h2>@lang('purchase_result.thankyou')</h2>

                    <p>@lang('purchase_result.was_completed', ['amount' => number_format( $transaction->paymentAmount, 2 ), 'hours' => trans_choice('purchase_result.hours', $transaction->hours), 'course' => $transaction->purchase->course->getCourseTitle() ])</p>

                    <script>
                        <?php echo $surveymonkeyScript; ?>
                    </script>

                @elseif( !strcasecmp(App\Transaction::DENIED, $transaction->paymentStatus ) )

                    <p>{!! trans_choice('purchase_result.cancel_reason', intval(!empty($transaction->purchase->error)), [ 'reason' => __( $transaction->purchase->error ) ])  !!}</p>

                @else

                <div class="columns" id="root" v-cloak>

                    @if( $nonInstantPay )

                        <p>@lang('purchase_result.non_instant_pay')</p>
                        <div class="column col-12">
                            <button class="button caution" @click="modalVisible = true"><i v-if="loading" class="fal fa-spinner fa-spin"></i> @lang('purchase_result.cancel')</button>
                        </div>

                    @else
                        <p>@lang('purchase_result.instant_pay')</p>

                            <div class="column col-6">
                                <button class="button primary" @click="returnToPayment('{{ strtolower( $transaction->paymentGateway ) }}', '{{ $idOrUrl }}')">@lang('purchase_result.retry')</button>
                            </div>
                            <div class="column col-6">
                                <button class="button caution" @click="modalVisible = true"><i v-if="loading" class="fal fa-spinner fa-spin"></i> @lang('purchase_result.cancel')</button>
                            </div>

                    @endif

                    <confirm-modal header="@lang('purchase_result.cancellation')"
                                   :visible="modalVisible"
                                   ok="@lang('purchase_result.ok')"
                                   no="@lang('purchase_result.no')"
                                   ok-cls="caution"
                                   no-cls=""
                                   @confirm="cancelTransaction({{ $transaction->getKey() }})"
                                   @close="modalVisible = false"
                    >@lang(
	                    'purchase_result.warning',
	                    [
	                    	'hours' => trans_choice('purchase_result.hours', $transaction->hours),
	                        'classes' => trans_choice( 'purchase_result.course_type', $transaction->purchase->numStudents, ['course' => $transaction->purchase->courseType ])
                        ]
                    )</confirm-modal>

                </div>
                @endif
            @endif
        </div>


    </main>
@endsection