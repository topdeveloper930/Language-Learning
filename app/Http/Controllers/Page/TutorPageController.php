<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\LanguagePageController;
use App\Teacher;
use App\Testimonial;

class TutorPageController extends LanguagePageController
{
	protected $template = 'page.tutor';

	protected $js = [
		'jquery3_4', 'flexslider', 'tutor'
	];

	protected function make()
	{
		parent::make();
		$teacherID = (int) request()->route()->parameter( 'id' );

		$this->setParams([
			'tutor'         => Teacher::findOrFail( $teacherID ),
			'language'      => $this->language,
			'moreTutors'    => Teacher::where( [
				[ 'languagesTaught', 'LIKE', "%={$this->language}%" ],
				[ 'teacherID', '!=', $teacherID ],
				[ 'activeTeacher', Teacher::ACTIVE ],
				[ 'newStudents', 1 ]
			] )			              ->inRandomOrder()
			                          ->limit( 4 )
			                          ->get(),
			'testimonials' => Testimonial::where( 'language', "{$this->language}" )
			                             ->inRandomOrder()
			                             ->limit( 4 )
			                             ->get(),
			'purposes' => $this->getPurposes()
		]);
	}

	private function getPurposes()
	{
		return array_merge(
			['standard', 'personal'],
			[] //TODO:
		);
	}
}
