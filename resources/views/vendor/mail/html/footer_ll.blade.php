<tr>
    <td>
        <table class="footer" align="center" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="background-color:#fafafa;" align="center" width="75%">
                    <p style="text-align:center;font-size:13px;font-family:Helvetica, Arial, Verdana, sans-serif;color:#888888;">
                        {{ $slot }}<br>
                        {{ config( 'legacy.globals.emailMailingAddress' ) }} <a href="{{ url( '/' ) }}" target="_blank" style="color:#888888;text-decoration:none;">{{ config( 'app.url' ) }}</a>
                    </p>
                </td>
                <td style="background-color:#fafafa;" align="left">
                    <a href="{{ config( 'legacy.globals.facebookURL' ) }}" target='_blank'><img src="{{ asset( '/schoolfinder/img/emails/facebook.jpg' ) }}" alt="{{ config( 'legacy.globals.businessName' ) }} Facebook Page" title="{{ config( 'legacy.globals.businessName' ) }} Facebook Page" style="border:none;" height="32" width="32"></a>
                    <a href="{{ config( 'legacy.globals.twitterURL' ) }}" target="_blank"><img src="{{ asset( '/schoolfinder/img/emails/twitter.jpg' ) }}" alt="{{ config( 'legacy.globals.businessName' ) }} Twitter Feed" title="{{ config( 'legacy.globals.businessName' ) }} Twitter Feed" style="border:none;" height="32" width="32"></a>
                    <a href="{{ config( 'legacy.globals.pinterestURL' ) }}" target="_blank"><img src="{{ asset( '/schoolfinder/img/emails/pinterest.jpg' ) }}" alt="{{ config( 'legacy.globals.businessName' ) }} Pinterest Account" title="{{ config( 'legacy.globals.businessName' ) }} Pinterest Account" style="border:none;" height="32" width="32"></a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    @if( !empty( $ucode ) )
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                            <tr>
                                <td align="center" width="100%">
                                    <p style="font-size:13px;font-family:Helvetica, Arial, Verdana, sans-serif;color:#888888;">
                                        @lang( 'emails.unsubscribe', [ 'url' => url( '/unsubscribe.php?ucode=' . $ucode ) ] )
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @endif
                </td>
            </tr>
        </table>
    </td>
</tr>
