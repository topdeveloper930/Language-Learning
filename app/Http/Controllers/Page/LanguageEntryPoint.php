<?php


namespace App\Http\Controllers\Page;


class LanguageEntryPoint extends PageEntryPoint {
	private $language;

	public function callClass( $language, $controller = 'index', $id = null )
	{
		$this->language = $language;

		return parent::callClass( $controller, $id );
	}

	public function classNamespace()
	{
		return parent::classNamespace() . '\\' . ucfirst( $this->language );
	}
}