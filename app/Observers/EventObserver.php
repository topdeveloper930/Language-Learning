<?php


namespace App\Observers;


use App\Event;
use App\Events\ScheduledClassEvent;
use App\Notifications\ClassScheduled;
use App\Student;

class EventObserver {
	
	/**
	 * Handle to the Event "created" event.
	 *
	 * @param  Event  $event
	 * @return void
	 */
	public function created(Event $event)
	{
		// Syncing to calendars
		event( new ScheduledClassEvent( $event ) );

		$createdBy = substr( $event->createdBy, 0, strpos( $event->createdBy, ':' ) );

		$class_scheduled = new ClassScheduled( $event );

		switch ( $createdBy )
		{
			case 'teacher':
				$event->student->notify( $class_scheduled );
				break;
			case 'api':
				$event->teacher->notify( $class_scheduled );
				break;
			default:
				$event->student->notify( $class_scheduled );
				$event->teacher->notify( $class_scheduled );
		}

		$event->student->active = Student::STUDENT_ACTIVE;
		$event->student->save();
	}

	/**
	 * Handle the Event "updated" event.
	 *
	 * @param  Event  $event
	 * @return void
	 */
	public function updated(Event $event)
	{
		// Syncing to calendars
		event( new ScheduledClassEvent( $event ) );

		$updated_by = substr( $event->updated_by, 0, strpos( $event->updated_by, ':' ) );
		$notify_class = ( 1 === $event->active ) ? '\App\Notifications\ClassChanged' : '\App\Notifications\ClassCancelled';

		switch ( $updated_by )
		{
			case 'teacher':
				$event->student->notify( new $notify_class( $event ) );
				break;
			case 'api':
				$event->teacher->notify( new $notify_class( $event ) );
				break;
			default:
				$event->student->notify( new $notify_class( $event ) );
				$event->teacher->notify( new $notify_class( $event ) );
		}
	}

	/**
	 * Handle the Event "deleted" event.
	 *
	 * @param  Event  $event
	 * @return void
	 */
	public function deleted(Event $event)
	{
		// Not needed. "updated" does the job. No physical delete.
	}
}