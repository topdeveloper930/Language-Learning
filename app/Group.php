<?php

namespace App;

use App\Services\Auth\UserType;
use App\Services\Auth\UserTypeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group extends Model implements UserType
{
	use UserTypeTrait;

	const TYPE = 'group';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'groupID';

	protected $guard = 'group';

	public function usuario()
	{
		return $this->belongsTo( User::class );
	}

	public function group_history()
	{
		return $this->hasMany(GroupHistory::class, 'group_id' );
	}

	public function teachers()
	{
		return $this->belongsToMany(Teacher::class, 'group_teacher', 'group_id', 'teacher_id');
	}

	public function students()
	{
		return $this->belongsToMany(Student::class, 'group_student', 'group_id', 'student_id');
	}

	public function getAllStudents( $active = null )
	{
		$q = $this->students();

		if( $active )
			$q->where( 'students.active', '=', ucfirst($active) );

		return $q->get();
	}

	public function member( $usuario_id, $action, $member_type, $member_id, $note = '' )
	{
		$member_field = str_singular( strtolower( $member_type ) ) . '_id';
		$member_type = str_plural( strtolower( $member_type ) );

		// Like $this->>students()->attach( $member_id );
		$this->{$member_type}()->{$action}( $member_id );

		$history = new GroupHistory;
		$history->usuario_id = $usuario_id;
		$history->{$member_field} = $member_id;
		$history->action = $action;
		$history->note = $note;

		return $this->group_history()->save( $history );
	}

	public function attachTeacher( $usuario_id, $member_id, $note = '' )
	{
		return (bool) $this->member( $usuario_id, 'attach', 'teacher', $member_id, $note );
	}

	public function detachTeacher( $usuario_id, $member_id, $note = '' )
	{
		return (bool) $this->member( $usuario_id, 'detach', 'teacher', $member_id, $note );
	}

	public function attachStudent( $usuario_id, $member_id, $note = '' )
	{
		return (bool) $this->member( $usuario_id, 'attach', 'student', $member_id, $note );
	}

	public function detachStudent( $usuario_id, $member_id, $note = '' )
	{
		return (bool) $this->member( $usuario_id, 'detach', 'student', $member_id, $note );
	}

	public function getTitle()
	{
		return $this->contactTitle;
	}

	public function getFirstName()
	{
		return $this->contactFirstName;
	}

	public function getLastName()
	{
		return $this->contactLastName;
	}
}
