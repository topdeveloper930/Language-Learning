<tr>
    <td>
        <table style="width:100%">
            <tr>
                <td style="width:50%; padding-left: 35px">
                    <h2 style="padding:0;margin:0;">{{ config( 'legacy.globals.classCoordinatorName' ) }}</h2>
                    @lang('emails.class_coordinator')<br>
                    <i>@lang('pages.footer_ribon', ['company' => config('app.name')])</i><br>
                    <span style="font-size: x-small">
                        <a href="{{ route('language', ['language' => 'english']) }}">English</a>
                        | <a href="{{ url('/') }}">Spanish</a>
                        | <a href="{{ route('language', ['language' => 'french']) }}">French</a>
                        | <a href="{{ route('language', ['language' => 'german']) }}">German</a>
                        | <a href="{{ route('language', ['language' => 'italian']) }}">Italian</a>
                        | <a href="{{ route('language', ['language' => 'portuguese']) }}">Portuguese</a><br>
                        <a href="{{ route('language', ['language' => 'russian']) }}">Russian</a>
                        | <a href="{{ route('language', ['language' => 'chinese']) }}">Chinese</a>
                        | <a href="{{ route('language', ['language' => 'japanese']) }}">Japanese</a>
                        | <a href="{{ route('language', ['language' => 'korean']) }}">Korean</a>
                        | <a href="{{ route('language', ['language' => 'arabic']) }}">Arabic</a>
                    </span>
                </td>
                <td  style="width:50%;">
                    {{ $slot }}
                </td>
            </tr>
        </table>
    </td>
</tr>
