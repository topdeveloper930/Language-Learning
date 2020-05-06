<?php

namespace App\Http\Requests;


class TeacherPhotoRequest extends PhotoRequest
{
	protected $instClass = \App\Teacher::class;

	protected $subFolder = 'profilesTeachers/';

	protected function maxSize()
	{
		return config( 'filesystems.maxsize.teacherProfileImage', parent::maxSize() );
	}

	protected function resizeXY()
	{
		return config( 'filesystems.maxsize.teacherProfileImageXY', parent::resizeXY() );
	}
}
