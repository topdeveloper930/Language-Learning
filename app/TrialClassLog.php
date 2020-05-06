<?php

namespace App;

use App\Traits\TimezoneTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TrialClassLog extends Model
{
	use TimezoneTrait, Notifiable;

	const CREATED_AT = 'requestDate';
	const UPDATED_AT = null;

    protected $table = 'trialClassLog';

    protected $primaryKey = 'trialClassLogID';


	public function fullName()
	{
		return trim( sprintf( '%s %s', $this->firstName, $this->lastName ) );
	}

	public function accost()
	{
		return trim( sprintf( '%s %s', $this->sex, $this->fullName() ) );
	}

    public function getAssignedTeacher()
    {
	    return Teacher::join( 'trialClass2Teachers', 'trialClass2Teachers.teacherID', '=', 'teachers.teacherID' )
	             ->where( [
		             [ 'trialClass2Teachers.trialClassLogID', $this->getKey() ],
		             [ 'trialClass2Teachers.results', 0 ]
	             ] )
	             ->latest('trialClass2Teachers.trialClass2Teachers')
	             ->first();
    }

    public function createStudent( $password )
    {
	    return Student::create([
		    'title'        => $this->sex,
		    'firstName'    => $this->firstName,
		    'lastName'     => $this->lastName,
		    'email'        => $this->email,
		    'password'     => $password,
		    'dateOfBirth'  => '',
		    'ageRange'     => $this->ageRange,
		    'information'  => $this->comments,
		    'timezone'     => $this->timezone,
		    'skype'        => $this->skypeName,
		    'phone'        => '',
		    'paypalEmail'  => '',
		    'country'      => $this->country,
		    'state'        => $this->state,
		    'city'         => $this->city,
		    'studentNotes' => ''
	    ]);
    }

	public static function getSignUpStats( $year, $country = '', $language = '', $age = '', $gender = 0 )
	{
		$sex_opts = [
			'', " AND `trialClassLog`.`sex` = 'Mr.' ", " AND `trialClassLog`.`sex` = 'Ms.' ",
			" AND `trialClassLog`.`sex` = 'Mrs.' ", " AND `trialClassLog`.`sex` = 'Dr.' ",
			" AND `trialClassLog`.`sex` = 'Prof.' ", " AND (`trialClassLog`.`sex` = 'Ms.' OR `trialClassLog`.`sex` = 'Mrs.') ",
			" AND (`trialClassLog`.`sex` = 'Dr.' OR `trialClassLog`.`sex` = 'Prof.') "
		];

		$gq = ( isset( $sex_opts[ $gender ] ) ) ?  $sex_opts[ $gender ] : '';

		$value = Cache::remember(
			'signups_after_trial_' . $year . $country . $language . $age . $gq,
			24 * 60,
			function () use( $year, $country, $language, $age, $gq ) {
				$sql = "
					SELECT 'signups' `field`, COUNT(`students`.`studentID`) `count`, MONTH(`trialClassLog`.`requestDate`) `month`
						  FROM `students`
						  JOIN `trialClassLog` ON `students`.`email` = `trialClassLog`.`email`
						  JOIN (SELECT `userID`, COUNT(*) `cnt`  FROM `transactions` WHERE `paymentStatus` = 'Completed' AND YEAR(`transactionDate`) = $year AND IF('$language' != '', `paymentFor` LIKE '$language%', 1) GROUP BY `userID`)
						         `t` ON `t`.`userID` = `students`.`studentID` AND `t`.`cnt` > 0
						 WHERE IF('$country' != '', `trialClassLog`.`country` = '$country', 1 )
						   AND IF('$language' != '', `trialClassLog`.`language` = '$language', 1) 
						   AND `trialClassLog`.`scheduled` = 'true'
						   AND IF('$age' != '', `trialClassLog`.`ageRange` = '$age', 1)
						   AND YEAR(`trialClassLog`.`requestDate`) = $year $gq
						GROUP BY `month`
					UNION	
					SELECT 'trials' `field`, COUNT(`trialClassLogID`) `count`, MONTH(`requestDate`) `month`
						  FROM `trialClassLog`
						 WHERE `scheduled` = 'true'
						   AND IF('$country' != '', `country` = '$country', 1)
						   AND IF('$language' != '', `language` = '$language' , 1)
						   AND IF('$age' != '', `ageRange` = '$age', 1)
						   AND YEAR(`requestDate`) = $year $gq
						 GROUP BY `month`
				";

				return DB::select( $sql );
			}
		);

		return $value;
	}

	public static function getIdOfTeacherAssignedToTrialClass( $course, $studentEmails )
	{
		$studentEmails = (array) $studentEmails;

		return DB::table( 'trialClassLog' )
		         ->join('trialClass2Teachers', function ($join) {
			         $join->on('trialClass2Teachers.trialClassLogID', '=', 'trialClassLog.trialClassLogID')
			              ->on('trialClassLog.scheduled', '=', DB::raw("'true'"));
		         })
		         ->select('trialClass2Teachers.teacherID')
		         ->where([
			         [ 'trialClass2Teachers.results', 'completed' ],
			         [ 'trialClassLog.course', $course ]
		         ])
		         ->whereIn('trialClassLog.email', $studentEmails)
		         ->orderBy('trialClassLog.trialClassLogID', 'DESC')
		         ->limit(1)
		         ->value('teacherID');
	}
}
