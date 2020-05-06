<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTeachersSetNullables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    DB::statement("
			ALTER TABLE `teachers` 
			    MODIFY COLUMN `title`					ENUM('Mr.', 'Ms.', 'Mrs.', 'Dr.', 'Prof.') NULL,
			    MODIFY COLUMN `titleID`					TINYINT				NULL,
			    MODIFY COLUMN `skype`					VARCHAR(150)		NULL,
			    MODIFY COLUMN `phone`					VARCHAR(150)		NULL,
			    MODIFY COLUMN `timezone`				VARCHAR(150)		NULL,
			    MODIFY COLUMN `timezoneID`				SMALLINT UNSIGNED 	NULL,
			    MODIFY COLUMN `languagesSpoken`			VARCHAR(500)		NULL,
			    MODIFY COLUMN `languagesSpokenES`		VARCHAR(500)		NULL,
			    MODIFY COLUMN `teacherIntroduction` 	TEXT 				NULL,
			    MODIFY COLUMN `teacherIntroductionES`	TEXT				NULL,
			    MODIFY COLUMN `languagesTaught`			VARCHAR(1000)		NULL,
			    MODIFY COLUMN `dialectsTaught`			VARCHAR(1000)		NULL,
			    MODIFY COLUMN `coursesTaught`			VARCHAR(5000)		NULL,
			    MODIFY COLUMN `agesTaught`				VARCHAR(500)		NULL,
			    MODIFY COLUMN `paymentEmail`			VARCHAR(150)		NULL,
			    MODIFY COLUMN `paymentNotes`			VARCHAR(1000)		NULL,
			    MODIFY COLUMN `countryOrigin`			VARCHAR(150)		NULL,
			    MODIFY COLUMN `countryOriginID`			SMALLINT UNSIGNED 	NULL,
			    MODIFY COLUMN `stateOrigin`				VARCHAR(150)		NULL,
			    MODIFY COLUMN `stateOriginID`			SMALLINT UNSIGNED 	NULL,
			    MODIFY COLUMN `cityOrigin`				VARCHAR(150)		NULL,
			    MODIFY COLUMN `street1Residence`		VARCHAR(150)		NULL,
			    MODIFY COLUMN `street2Residence`		VARCHAR(150)		NULL,
			    MODIFY COLUMN `countryResidence`		VARCHAR(150)		NULL,
			    MODIFY COLUMN `countryResidenceID`		SMALLINT UNSIGNED 	NULL,
			    MODIFY COLUMN `stateResidence`			VARCHAR(150)		NULL,
			    MODIFY COLUMN `stateResidenceID`		SMALLINT UNSIGNED 	NULL,
			    MODIFY COLUMN `cityResidence`			VARCHAR(150)		NULL,
			    MODIFY COLUMN `zipResidence`			VARCHAR(100)		NULL,
			    MODIFY COLUMN `startedTeaching`			SMALLINT UNSIGNED 	NULL,
			    MODIFY COLUMN `teachingStyle`			VARCHAR(100)		NULL,
			    MODIFY COLUMN `teachingStyleID`			TINYINT UNSIGNED 	NULL,
			    MODIFY COLUMN `teachingStyleES`			VARCHAR(100)		NULL,
			    MODIFY COLUMN `education`				VARCHAR(2000)		NULL,
			    MODIFY COLUMN `educationES`				VARCHAR(2000)		NULL,
			    MODIFY COLUMN `workExperience`			TEXT				NULL,
			    MODIFY COLUMN `workExperienceES`		TEXT				NULL,
			    MODIFY COLUMN `hobbies`					VARCHAR(1000)		NULL,
			    MODIFY COLUMN `hobbiesES`				VARCHAR(1000)		NULL,
			    MODIFY COLUMN `favoriteWebsite`			VARCHAR(255)		NULL,
			    MODIFY COLUMN `favoriteMovie`			VARCHAR(255)		NULL,
			    MODIFY COLUMN `favoriteMovieES`			VARCHAR(255)		NULL,
			    MODIFY COLUMN `favoriteFood`			VARCHAR(128)		NULL,
			    MODIFY COLUMN `favoriteFoodES`			VARCHAR(128)		NULL,
			    MODIFY COLUMN `countriesVisited`		VARCHAR(500)		NULL,
			    MODIFY COLUMN `countriesVisitedES`		VARCHAR(500)		NULL,
			    MODIFY COLUMN `bucketList`				VARCHAR(255)		NULL,
			    MODIFY COLUMN `bucketListES`			VARCHAR(255)		NULL,
			    MODIFY COLUMN `teacherNotes`			TEXT				NULL,
			    MODIFY COLUMN `teacherScore`			SMALLINT UNSIGNED 	NULL		DEFAULT '550',
			    MODIFY COLUMN `activeTeacher`			ENUM('Active', 'Inactive') NULL DEFAULT 'Inactive',
			    MODIFY COLUMN `newStudents`				TINYINT				NULL		DEFAULT '1'
		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    DB::statement("
			ALTER TABLE `teachers` 
			    MODIFY COLUMN `title`					VARCHAR(10) 		NOT NULL,
			    MODIFY COLUMN `titleID`					INT(11)				NOT NULL,
			    MODIFY COLUMN `skype`					VARCHAR(150) 		NOT NULL,
			    MODIFY COLUMN `phone`					VARCHAR(150) 		NOT NULL,
			    MODIFY COLUMN `timezone`				VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `timezoneID`				INT(11)			 	NOT NULL,
			    MODIFY COLUMN `languagesSpoken`			VARCHAR(500)		NOT NULL,
			    MODIFY COLUMN `languagesSpokenES`		VARCHAR(500)		NOT NULL,
			    MODIFY COLUMN `teacherIntroduction` 	TEXT 				NOT NULL,
			    MODIFY COLUMN `teacherIntroductionES`	TEXT				NOT NULL,
			    MODIFY COLUMN `languagesTaught`			VARCHAR(1000)		NOT NULL,
			    MODIFY COLUMN `dialectsTaught`			VARCHAR(1000)		NOT NULL,
			    MODIFY COLUMN `coursesTaught`			VARCHAR(5000)		NOT NULL,
			    MODIFY COLUMN `agesTaught`				VARCHAR(500)		NOT NULL,
			    MODIFY COLUMN `paymentEmail`			VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `paymentNotes`			VARCHAR(1000)		NOT NULL,
			    MODIFY COLUMN `countryOrigin`			VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `countryOriginID`			INT(11)				NOT NULL,
			    MODIFY COLUMN `stateOrigin`				VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `stateOriginID`			INT(11)			 	NOT NULL,
			    MODIFY COLUMN `cityOrigin`				VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `street1Residence`		VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `street2Residence`		VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `countryResidence`		VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `countryResidenceID`		INT(11)			 	NOT NULL,
			    MODIFY COLUMN `stateResidence`			VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `stateResidenceID`		INT(11)			 	NOT NULL,
			    MODIFY COLUMN `cityResidence`			VARCHAR(150)		NOT NULL,
			    MODIFY COLUMN `zipResidence`			VARCHAR(100)		NOT NULL,
			    MODIFY COLUMN `startedTeaching`			VARCHAR(100)	 	NOT NULL		DEFAULT '2012',
			    MODIFY COLUMN `teachingStyle`			VARCHAR(100)		NOT NULL,
			    MODIFY COLUMN `teachingStyleID`			INT(11)			 	NOT NULL,
			    MODIFY COLUMN `teachingStyleES`			VARCHAR(100)		NOT NULL,
			    MODIFY COLUMN `education`				VARCHAR(2000)		NOT NULL,
			    MODIFY COLUMN `educationES`				VARCHAR(2000)		NOT NULL,
			    MODIFY COLUMN `workExperience`			TEXT				NOT NULL,
			    MODIFY COLUMN `workExperienceES`		TEXT				NOT NULL,
			    MODIFY COLUMN `hobbies`					VARCHAR(1000)		NOT NULL,
			    MODIFY COLUMN `hobbiesES`				VARCHAR(1000)		NOT NULL,
			    MODIFY COLUMN `favoriteWebsite`			VARCHAR(255)		NOT NULL,
			    MODIFY COLUMN `favoriteMovie`			VARCHAR(255)		NOT NULL,
			    MODIFY COLUMN `favoriteMovieES`			VARCHAR(255)		NOT NULL,
			    MODIFY COLUMN `favoriteFood`			VARCHAR(128)		NOT NULL,
			    MODIFY COLUMN `favoriteFoodES`			VARCHAR(128)		NOT NULL,
			    MODIFY COLUMN `countriesVisited`		VARCHAR(500)		NOT NULL,
			    MODIFY COLUMN `countriesVisitedES`		VARCHAR(255)		NOT NULL,
			    MODIFY COLUMN `bucketList`				VARCHAR(255)		NOT NULL,
			    MODIFY COLUMN `bucketListES`			VARCHAR(255)		NOT NULL,
			    MODIFY COLUMN `teacherNotes`			TEXT				NOT NULL,
			    MODIFY COLUMN `teacherScore`			INT(10)			 	NOT NULL		DEFAULT '550',
			    MODIFY COLUMN `activeTeacher`			VARCHAR(10)			NOT NULL		DEFAULT 'Inactive',
			    MODIFY COLUMN `newStudents`				INT(10)				NOT NULL		DEFAULT '1'
	    ");
    }
}
