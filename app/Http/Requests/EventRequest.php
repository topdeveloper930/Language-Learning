<?php

namespace App\Http\Requests;

use App\CourseList;
use App\Event;
use App\Services\Auth\UserType;
use App\Student;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventRequest extends SystemRequest
{
	const DATE_FORMAT = "Y-m-d H:i:s";
	
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

    	$class_id = $this->event ? $this->event : $this->class_id;

	    $this->offsetSet('inst', Event::find( $class_id ));

	    // We don't want to bother with non-existing or inactive classes
	    if( !$this->inst OR !$this->inst->active )
		    throw new NotFoundHttpException( 'Class does not exist or deleted.' );

	    if( parent::authorize() )
		    return true;

	    return Auth::user()->can('delete', $this->inst);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	        'class_id'      => 'required_without:id',
            'id'            => 'required_without:class_id',
	        'eventStart'    => 'bail|required_with:id|date_format:"' . static::DATE_FORMAT . '"|event_after:' . config( 'main.schedule_from', '+24 hours' ) . ',inst',
	        'eventEnd'      => 'bail|required_with:id|event_no_conflict:eventStart,inst|date_format:"' . static::DATE_FORMAT. '"|after:eventStart'
        ];
    }

	protected function prepareForValidation()
    {
    	if('PUT' == $this->method() OR 'PATCH' == $this->method())
			$this->getInputSource()->add([
				'id' => ($this->event) ? $this->event : $this->class,
				'inst' => $this->inst
			]);
    	else
		    $this->getInputSource()->add([
			    'class_id' => ($this->event) ? $this->event : $this->class
		    ]);
    }

	/**
	 * @param \Illuminate\Validation\Validator $validator
	 */
	public function withValidator( $validator)
	{
		// Check if enough credits
		$validator->after(function ($validator) {
			if( 'DELETE' == $this->method() AND $this->inst->eventStart < gmdate( 'Y-m-d H:i:s', strtotime( config( 'main.cancel_from', 'tomorrow' )) ) )
				$validator->errors()->add( 'eventStart', __('validation.event_after', [ 'value' => config( 'main.cancel_advance', 24 ) ] ) );

			if( in_array( $this->method(), [ 'POST', 'PUT', 'PATCH' ] ) AND ! $this->enoughCredit() )
				$validator->errors()->add('course', trans('validation.enough_credit'));
		});
	}

	public function enoughCredit()
	{
		$class_length = ( strtotime( $this->eventEnd ) - strtotime(  $this->eventStart ) ) / 3600;

		$class_length -= $this->inst->lengthHours();

		if( $class_length <= 0 )
			return true;

		return $this->inst->student->remainingCredits( $this->inst->getCourseType(), $this->inst->numStudents ) >= $class_length;
	}


	public function currentUserID()
	{
		$user = Auth::user();

		return ( $user instanceof UserType )
			? $user->getPrimaryKey()
			: Auth::id();
	}

	public function guardName()
	{
		$guard = Auth::guard();

		if( method_exists( $guard, 'userType' ) )
			return $guard->userType();

		if( Auth::guard( 'api' )->check() )
			return 'api';

		return 'undefined';
	}
}
