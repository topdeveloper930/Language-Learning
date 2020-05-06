@php( isset( $syncButton ) OR $syncButton = false )
<a href="{{ url( 'students/calendar' ) }}" class="btn btn-success">@lang( 'pages.calendar.schedule' )</a>
<a href="{{ url( 'students/upcoming-classes' ) }}" class="btn btn-warning">@lang( 'pages.classes.upcoming' )</a>
<a href="{{ url( 'students/lessons-log.php' ) }}" class="btn btn-info">@lang( 'pages.classes.log' )</a>
@if( $syncButton ) |
<g-calendar calendar_id="{{ $user->gcalendar ? $user->gcalendar->provider_cal_id : '' }}"
            client_id="{{ config( 'api.gcalendar.clientID' ) }}"
            api_key="{{ config( 'api.gcalendar.apiKey' ) }}"
            ll_location="{{ config( 'api.gcalendar.location' ) }}"
            gapi_token="{{ $user->hasToken() }}"
            translate='{{ json_encode(__( 'gcalendar' )) }}'
>@lang( 'gcalendar.sync_btn' )</g-calendar>
@endif