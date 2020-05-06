<?php

namespace App\Http\Requests;


class StudentPhotoRequest extends PhotoRequest
{
	protected $instClass = \App\Student::class;

	protected $subFolder = 'profilesStudents/';

	protected function maxSize()
	{
		return config( 'filesystems.maxsize.studentProfileImage', parent::maxSize() );
	}

	protected function resizeXY()
	{
		return config( 'filesystems.maxsize.studentProfileImageXY', parent::resizeXY() );
	}
}
