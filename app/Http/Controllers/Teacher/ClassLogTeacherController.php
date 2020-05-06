<?php

namespace App\Http\Controllers\Teacher;

use App\ClassLog;
use App\CourseList;
use App\Http\Controllers\TeacherController;
use App\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClassLogTeacherController extends TeacherController
{
    protected $js = ['teacher_class_log'];

    protected $translation = 'teacher_class_log.js';

    protected function before()
    {
	    parent::before();

	    abort_unless((int) request()->route('id'), 404);
    }

	protected function make()
    {
	    parent::make();

	    $student = $this->teacher->students()
	                             ->wherePivot('active', 1)
	                             ->wherePivot( 'studentID', (int) $this->arguments[0] )
	                             ->firstOrFail();

	    $this->setParams([
		    'student'        => $student,
		    'selectedCourse' => request( 'course' ),
		    'numStudents'    => request( 'n' )
	    ]);
    }

    protected function obtainData()
    {
	    parent::obtainData();

	    if ( 'POST' == request()->method() )
		    $this->data = $this->logClass( request()->all() );
        else
		    $this->data = $this->teacher->getCoursesLimited( (int)$this->arguments[0] );
    }

    private function logClass( $classLog )
    {
	    $classLog['studentID'] = $this->arguments[0];
	    $teacherID = $this->teacher->getKey();

    	Validator::make( $classLog, [
    		'studentID'      => ['required', Rule::exists('teacher2student')->where(function ($query) use($teacherID) {
								    $query->where('active', 1)->where('teacherID', $teacherID);
							    })],
		    'numStudents'    => 'required|in:1,2,3',
		    'course'         => 'required|exists:courseList,courseType',
		    'creditsUsed'    => 'required|numeric|min:0.5|max:2',
		    'whatWasStudied' => 'required|min:150|max:1000',
		    'internalNotes'  => 'string|nullable|max:1000',
		    'classDate'      => 'required|date|before_or_equal:' . date('Y-m-d')
	    ])
		    ->after(function($validator){   //Check if student has enough credits
		    	$cl = $validator->getData();
		    	$student = Student::find( $cl['studentID'] );

		    	if( $student->getCourseRemainingBalance($cl['course'], $cl['numStudents']) < $cl['creditsUsed'] ){
				    $validator->getMessageBag()->add('no_credits', __( 'teacher_class_log.student_has_no_credits', ['student' => $student->fullName()] ));
			    }
		    })
		    ->validate();

    	$course = CourseList::where('courseType', $classLog['course'])->first();

	    $classLog['course'] = $course->getCourseTitle();

	    $this->teacher->classLogs()->create($classLog);

	    $this->notification(
	    	__(
	    		'teacher_class_log.class_log_added',
			    ['student' => $this->teacher->students->find($classLog['studentID'])->accost()]
		    ),
		    'success'
	    );

    	return ($this->isTimeForEvaluation($course->courseType))
		    ? route('teachers', ['controller' => 'student-evaluation', 'id' => $classLog['studentID']]) . '?course=' . urlencode($classLog['course'])
		    : route('teachers', ['controller' => 'dashboard']);
    }

	/**
	 * @param string $course
	 *
	 * @return bool
	 */
    private function isTimeForEvaluation( $course )
    {
	    $lastEvaluation = $this->teacher->evaluations()
	                                    ->where( [
		                                    [ 'studentID', $this->arguments[0] ],
		                                    [ 'course', $course ]
	                                    ] )
	                                    ->latest( 'evaluationDate' )
	                                    ->first();

	    $evaluated = ($lastEvaluation) ? $lastEvaluation->classesTaken : 0;

	    return ( ClassLog::totalStudentHoursByCourse( $this->arguments[0], '%' . $course ) - $evaluated)
	            >= config('evaluation.step');
    }
}
