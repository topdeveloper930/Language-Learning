<tr>
    <td class="header">
        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
            <tbody>
            <tr>
                <td align="left" valign="middle" height="80" width="355">
                    <a href="{{ url( '/' ) }}" target="_blank">
                        <img src="{{ asset( config( 'legacy.globals.rootPageHeaderLogo' ) ) }}" alt="{{ config( 'legacy.globals.businessName' ) }}" width="355" height="68">
                    </a>
                </td>
                <td class="text-right" style="vertical-align: bottom; padding-right: 35px">
                    <h1 class="text-right" style="font-size:26px;">{{ $slot }}</h1>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
