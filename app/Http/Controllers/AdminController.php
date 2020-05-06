<?php

namespace App\Http\Controllers;


class AdminController extends TemplateController
{
	protected $admin;

	// Empty array means all roles of the members table are allowed to see the page.
	protected $permitted_roles = [];

	public function __construct()
	{
		parent::__construct();

		$this->setParam( 'user', $this->admin );
	}

	protected function getConfigs()
	{
		return array_merge(
			parent::getConfigs(),
			[
				'include_analytics' => false
			]
		);
	}

	protected function before()
	{
		parent::before();

		$this->admin = $this->user->member;

		if( !empty( $this->permitted_roles ) AND !in_array( $this->admin->userType, $this->permitted_roles ) )
			throw new \Illuminate\Auth\Access\AuthorizationException('Access restricted' );
	}

	/**
	 * If the template is explicitly set, then it is used.
	 * Otherwise look for a snakecased name of the class without the AdminController suffix in the admin folder of views.
	 *
	 * @throws \ReflectionException
	 */
	protected function setTemplateName()
	{
		parent::setTemplateName();

		if( is_null( $this->template ) )
			$this->template = 'admin.' . snake_case( str_replace( 'AdminController', '', $this->classShortName() ));
	}
}
