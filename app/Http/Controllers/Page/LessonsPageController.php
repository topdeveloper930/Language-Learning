<?php

namespace App\Http\Controllers\Page;


use App\Teacher;

class LessonsPageController extends CourseController {

	protected function make()
	{
		parent::make();

		$this->setParams([
			'teachers' => Teacher::where([
							['activeTeacher', Teacher::ACTIVE],
							['newStudents', 1],
							['profileImage', '!=', config('main.profile_image_stub')],
							['languagesTaught', 'LIKE', "%specialty%5B%5D={$this->language}%"],
						])->inRandomOrder()->limit(4)->get(),
			'countriesCnt' => Teacher::countOriginCountriesForLanguage( $this->language )
		]);
	}

}
