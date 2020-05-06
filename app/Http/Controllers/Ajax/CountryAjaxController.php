<?php


namespace App\Http\Controllers\Ajax;


use App\Http\Controllers\AjaxController;
use App\LocationCountries;
use Illuminate\Http\Request;

class CountryAjaxController extends AjaxController {

	public function index( Request $request )
	{
		return ( $request->get('plucked') )
			? response()->json( LocationCountries::all()->pluck('name') )
			: response()->json( LocationCountries::all() );
	}

	public function show( $id )
	{
		return response()->json( LocationCountries::findOrFail( $id ) );
	}
}