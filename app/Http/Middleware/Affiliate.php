<?php

namespace App\Http\Middleware;

use App\AffiliateLog;
use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class Affiliate
{
    /**
     * Handle an incoming request: check referral code. Then, if not provided, check affiliate program code.
     * (Only one code accepted, not both).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    $cookie_name   = config( 'referral_program.referral_code_cookie_name', 'llrpcode' );

	    if( $this->hasCookie( $cookie_name, $request->hasCookie( $cookie_name ) ) )
		    return $next($request);

	    $referral_code = $request->get( $cookie_name );
	    $campaignID    = 'RP';

	    if( !$referral_code ) {
		    $cookie_name   = config( 'referral_program.affiliate_cookie_name', 'll_referral' );

		    if( $this->hasCookie( $cookie_name, $request->hasCookie( $cookie_name ) ) )
			    return $next($request);

		    $campaignID    = $request->get( config( 'referral_program.affiliate_campaign_id', 'cid' ) );
		    $referral_code = $request->get( config( 'referral_program.affiliate_get_name', 'rid' ) );
	    }

	    if( $referral_code ) {
		    Cookie::queue( Cookie::forever( $cookie_name, $referral_code ) );

		    $this->writeAffiliateLog( $request, $referral_code, $campaignID );
	    }

        return $next($request);
    }

    private function hasCookie( $name, $new_cookie_set )
    {
	    // Re-write cookie from legacy code (can be deleted later)
	    if( isset( $_COOKIE[ $name ] ) ) {
		    if( !$new_cookie_set )
			    Cookie::queue( Cookie::forever( $name, $_COOKIE[ $name ] ) );

		    return true;
	    }

	    return false;
    }

	private function writeAffiliateLog( $request, $rid, $cid )
	{
		AffiliateLog::create([
			'referralID'    => $rid,
			'campaignID'    => $cid,
			'landingPage'   => $request->url(),
			'referrer'      => $request->server('HTTP_REFERER'),
			'useragent'     => $request->server('HTTP_USER_AGENT'),
			'ip'            => $request->ip(),
			'country'       => \App\Ip2Nation::find( DB::raw( "INET_ATON('{$request->ip()}')" ) ),
			'remoteHost'    => @getHostByAddr( $request->ip() )
		]);
	}
}
