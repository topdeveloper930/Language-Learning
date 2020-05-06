<?php

namespace App\Jobs;

use App\Notifications\TeacherGender;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetGender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Model $user )
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void|bool
     */
    public function handle()
    {
        if( 'male' == $this->user->gender OR 'female' == $this->user->gender ) return;

        if ( $this->user->title AND 'mr.' == strtolower( $this->user->title ) ) {
        	// If title is Mr. then it's man.
	        $this->user->update([ 'gender' => 'male' ]);

	        return;
        }
        elseif ( $this->user->title AND ( 'ms.' == strtolower( $this->user->title ) OR 'mrs.' == strtolower( $this->user->title ) ) ) {
	        // If title is Ms. or Mrs. then it's woman.
	        $this->user->update([ 'gender' => 'female' ]);

        	return;
        }
        else {
	        try
	        {
		        $api    = app()->makeWith( \GenderApi\Client::class, [ 'apiKey' => config( 'api.gender_api_key' ) ] );
		        $lookup = $api->getByFirstNameAndLastName( $this->user->fullName() );

		        if ( $lookup->genderFound() )
		        {
			        $this->user->update( [ 'gender' => $lookup->getGender() ] );

			        return;
		        }
	        }
	        catch ( \GenderApi\Exception $e ) { /* No need to bother with handling */ }
        }

	    // Send notification to coordinator to manually set the teacher's gender.
	    $coordinator = \App\User::where('email', config( 'legacy.globals.mainEmail' ))->first();

        if( $coordinator )
	        $coordinator->notify( new TeacherGender( $this->user ) );

    }
}
