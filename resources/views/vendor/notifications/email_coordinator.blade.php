@component('mail::message_coordinator')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# Whoops!
@else
# Hello!
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
	    case 'warning':
		    $color = 'orange';
		    break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang( 'emails.salutation' )
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
    @lang( 'emails.button_problem', [ 'actionText' => $actionText, 'actionUrl' => $actionUrl ] )
@endcomponent
@endisset
@endcomponent
