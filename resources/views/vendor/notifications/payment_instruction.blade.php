@component('mail::message_ll')
@if(stripos($transaction->paymentGateway, "transfer"))
### @lang('student_purchase.transfer_min_purchase', ['hours' => config('payment.transfer_min_purchase')])


@lang('student_purchase.transfer_selected', [ 'hours' => $transaction->hours, 'course' => $paymentFor ])


1. @lang('student_purchase.enough_costs', ['sum' => number_format( $transaction->paymentAmount, 2)])

2. @lang('student_purchase.initiate_transfer')

>
> <pre>{!! config('payment.wire_transfer_address') !!}</pre>
>

3. @lang('student_purchase.transfer_initiated', ['email' => config('legacy.globals.mainEmail')])

@lang( 'student_purchase.we_transfer' )
@else
@lang('student_purchase.check_selected', ['hours' => $transaction->hours, 'course' => $paymentFor])


1. @lang('student_purchase.enough_costs', ['sum' => number_format( $transaction->paymentAmount, 2)])

2. @lang('student_purchase.check_out_to', ['to' => config('payment.check_out_to')])

>
> <pre>{!! config('payment.check_address') !!}</pre>
>

3. @lang('student_purchase.mail_us', ['email' => config('legacy.globals.mainEmail')])

4. @lang('student_purchase.term_deposit')

@lang( 'student_purchase.we_check' )
@endif
@endcomponent
