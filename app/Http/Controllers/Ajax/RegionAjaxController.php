<?php


namespace App\Http\Controllers\Ajax;


use App\Http\Controllers\AjaxController;
use App\LocationCountries;
use App\LocationRegions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegionAjaxController extends AjaxController {

	public function index( Request $request )
	{
		Validator::make(request(['country']), [
			'country' => 'required|string|exists:locationCountries,name'
		])->validate();

		return ( $request->get('plucked') )
			? response()->json( LocationCountries::where('name', request('country'))->first()->regions->pluck('name') )
			: response()->json( LocationCountries::where('name', request('country'))->first()->regions );
	}

	public function show( $id )
	{
		return response()->json( LocationRegions::findOrFail( $id ) );
	}
}