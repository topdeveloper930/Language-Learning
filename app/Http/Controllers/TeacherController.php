<?php

namespace App\Http\Controllers;


class TeacherController extends TemplateController
{
	/**
	 * @var \App\Teacher
	 */
	protected $teacher;

	public function __construct()
	{
		parent::__construct();

		$this->teacher = $this->user->getInstance();

		$this->setParam( 'user', $this->teacher );
	}

	protected function getConfigs()
	{
		return array_merge(
			parent::getConfigs(),
			[
				'user_type' => 'teacher',
				'user_id'   => $this->teacher['teacherID'],
			]
		);
	}

	/**
	 * If the template is explicitly set, then it is used.
	 * Otherwise look for a snakecased name of the class without the TeacherController suffix in the teacher folder of views.
	 *
	 * @throws \ReflectionException
	 */
	protected function setTemplateName()
	{
		parent::setTemplateName();

		if( is_null( $this->template ) )
			$this->template = 'teacher.' . snake_case( str_replace( 'TeacherController', '', $this->classShortName() ));
	}
}
