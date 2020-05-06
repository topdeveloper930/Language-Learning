<?php


namespace App\Services\Config;



use App\Traits\ArrayObjectAccess;

class TemplateConfig {
	use ArrayObjectAccess;

	private $styles_list;

	private $scripts_list;

	private $default_attributes = [
		'styles'    => [],
		'head_js'   => [],
		'scripts'   => []
	];

	public function __construct( $styles = [], $scripts = [], $attributes = [] )
	{
		$this->styles_list = $styles;
		$this->scripts_list = $scripts;

		$this->container = array_merge( $this->default_attributes, $attributes );
	}

	public function getStyles()
	{
		return $this->styles_list;
	}

	public function getScripts()
	{
		return $this->scripts_list;
	}

	public function getStyle( $key )
	{
		return ( array_key_exists( $key, $this->styles_list ) )
			? $this->styles_list[ $key ]
			: $key;
	}

	public function getScript( $key )
	{
		return ( array_key_exists( $key, $this->scripts_list ) )
			? $this->scripts_list[ $key ]
			: $key;
	}

	/**
	 * @param array $css
	 */
	public function addCSS( $css )
	{
		$this->styles = array_merge( $this->styles, (array) $css );
	}

	/**
	 * @param array $js
	 */
	public function addJS( $js, $head = false )
	{
		if( $head )
			$this->head_js = array_merge( $this->head_js, (array) $js );
		else
			$this->scripts = array_merge( $this->scripts, (array) $js );
	}

	/**
	 * @param array $css
	 */
	public function removeCSS( $css )
	{
		$this->styles = array_diff( $this->styles, (array) $css );
	}

	/**
	 * @param array $js
	 */
	public function removeJS( $js )
	{
		$this->scripts = array_diff( $this->scripts, (array) $js );
		$this->head_js = array_diff( $this->head_js, (array) $js );
	}

	public static function instance( $styles = [], $scripts = [], $attributes = [] )
	{
		return new static( (array) $styles, (array) $scripts, (array) $attributes );
	}
}