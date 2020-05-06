<?php

namespace App\Http\Controllers;


use App\Services\Config\TemplateConfig;
use App\Traits\ClassNames;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

abstract class TemplateController extends Controller
{
	use ClassNames;

	protected $template;

	/**
	 * @var TemplateConfig
	 */
	protected $tplConfig;

	protected $params = [];

	protected $user;

	protected $css = [ // Theses default "legacy" design styles may be not needed in future templates (zedalabs).
		'font_open_sans', 'bootstrap', 'font-awesome', 'theme', 'theme-elements', 'schoolfinder-inc-header', 'skin_blue',
		'bootstrap-responsive', 'theme-responsive'
	];

	protected $main_menu_active;
	protected $current_menu = '';
	protected $page_title = '';
	protected $translation = '';

	protected $js = [];

	protected $arguments;

	protected $data = [];

	protected $language = 'spanish';

	/**
	 * @var \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	protected $redirectResponse;

	public function __construct()
	{
		$this->user = auth()->user();

		$this->before();
	}

	public function __invoke()
	{
		if( $this->redirectResponse )
			return $this->redirectResponse;

		$this->arguments = func_get_args();

		if ( request()->ajax() ) {
			$this->obtainData();
		}
		else {
			$this->prepareTemplate();

			$this->make();
		}

		return $this->respond();
	}

	protected function respond()
	{
		if( $this->redirectResponse )
			return $this->redirectResponse;

		if ( request()->ajax() )
			return response()->json( $this->data );

		return view( $this->template )->with( $this->params );
	}

	protected function prepareTemplate()
	{
		$this->setTemplateName();

		$this->tplConfig = TemplateConfig::instance(
			config('styles'),
			config('scripts'),
			$this->getConfigs()
		);

		View::share([
			'tplConfig' => $this->tplConfig,
			'language'  => $this->language,
			'isDefault' => 'spanish' == strtolower($this->language)
		]);

		if( $this->translation )
			$this->setParam( 'translation', $this->translation );

		$this->tplConfig->addCSS( $this->css );

		$this->tplConfig->addJS( $this->js );
	}

	protected function getConfigs()
	{
		return [
			'globals'   => config( 'legacy.globals' ),
			'fb_api'    => config( 'services.fb_api' ),
			'include_analytics' => App::environment('production'),
			'user_type' => 'guest',
			'user_id'   => 'not set',
			'page_title' => $this->page_title,
			'current_menu' => $this->current_menu,
			'main_menu_active' => $this->main_menu_active
		];
	}

	protected function setParams( Array $params )
	{
		$this->params = array_merge( $this->params, $params );
	}

	protected function setParam( $name, $value )
	{
		$this->params[ $name ] = $value;
	}

	protected function notification( $message, $level = null )
	{
		Session::flash('notification', $message);
		!$level OR Session::flash('notification-class', $level);
	}

	protected function before() {}

	protected function make(){}
	protected function obtainData(){}
	protected function setTemplateName(){}
}
