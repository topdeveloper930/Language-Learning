@component('mail::message_ll')
@php
    $refCode = $invitation->student->refCoupon()->code;
@endphp
@lang( 'emails.refer.good_day', ['name' => $invitation->name] )


@lang( 'emails.refer.use_link', ['link' => route('page', [ 'controller' => 'trial-lesson', 'id' => $refCode]) ])


@lang( 'emails.refer.have_discount', ['discount' => $discount] )


**{{ $refCode }}**


@lang( 'emails.refer.assistance' )


@lang( 'emails.refer.have_great_day' )


@if($invitation->note)
**{{ trans('emails.refer.referrer_says', ['referrer' => $invitation->student->accost()]) }}**
>
> <pre><i>{{ $invitation->note }}</i></pre>
>
@endif
@endcomponent
