<?php

namespace App\Http\Controllers;


class StudentController extends TemplateController
{

	public function __construct()
	{
		parent::__construct();

		$this->setParam( 'user', $this->user );
	}

	protected function getConfigs()
	{
		return array_merge(
			parent::getConfigs(),
			[
				'user_type' => 'student',
				'user_id'   => $this->user[ 'studentID' ],
				'area'      => 'students',
			]
		);
	}

	/**
	 * If the template is explicitly set, then it is used.
	 * Otherwise look for a snakecased name of the class without the StudentController suffix in the student folder of views.
	 *
	 * @throws \ReflectionException
	 */
	protected function setTemplateName()
	{
		parent::setTemplateName();

		if( is_null( $this->template ) )
			$this->template = 'student.' . snake_case( str_replace( 'StudentController', '', $this->classShortName() ));
	}
}
