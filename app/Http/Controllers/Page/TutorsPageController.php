<?php

namespace App\Http\Controllers\Page;

use App\CourseList;
use App\Http\Controllers\LanguagePageController;
use App\Teacher;
use Illuminate\Support\Arr;

class TutorsPageController extends LanguagePageController
{
	protected $template = 'page.tutors';

    protected $js = [ 'tutors' ];

	protected function make()
    {
	    parent::make();

	    $this->setParams([
	    	'language' => $this->language,
		    'filters' => request()->all()
	    ]);
    }

    protected function obtainData()
    {
	    parent::obtainData();

	    $this->data = ( Arr::get( $this->arguments, 0 ) == 'courses' )
		    ? CourseList::where([
			        ['language', ucfirst($this->language)],
				    ['courseType', 'NOT LIKE', 'Standard %']
			    ])->pluck('courseType')
		    : Teacher::listActive( $this->language );
    }
}
