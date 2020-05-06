<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuizResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Alter learningQuizResults first to optimize it.
	    DB::statement( 'ALTER TABLE `learningQuizResults` ENGINE = InnoDB' );

	    Schema::table('learningQuizResults', function ( Blueprint $table ) {
		    $table->bigIncrements('id')             ->change();
		    $table->string( 'language' )->nullable()->change();
		    $table->string( 'country' ) ->nullable()->change();
		    $table->string( 'style' )   ->nullable()->change();
		    $table->string( 'highest' ) ->nullable()->change();
		    $table->decimal( 'visual', 3, 1 )       ->default(0.0)->change();
		    $table->decimal( 'auditory', 3, 1 )     ->default(0.0)->change();
		    $table->decimal( 'kinesthetic', 3, 1 )  ->default(0.0)->change();
	    });

	    $this->long2inet_pton();

	    DB::statement('ALTER TABLE `learningQuizResults` CHANGE COLUMN `ip` `ip` VARBINARY(16) NULL AFTER `language`');

	    // Add quiz_result_id fields to `teachers` ...
	    Schema::table('teachers', function (Blueprint $table) {
		    $table->integer('quiz_result_id', false, true)->nullable();
	    });

	    // ... and `jobApplicant` tables
	    Schema::table('jobApplicant', function (Blueprint $table) {
		    $table->integer('quiz_result_id', false, true)->nullable();
	    });

	    $errors_cnt = 0;

	    // Copy teachers quiz results
	    $this->move_quiz_results( 'teachers', $errors_cnt );

	    // Copy applicants quiz results
	    $this->move_quiz_results( 'jobApplicant', $errors_cnt );

	    // If all records transferred successfully, drop redundant columns.
	    if( !$errors_cnt ) {
		    Schema::table('jobApplicant', function (Blueprint $table) {
			    $table->dropColumn('learningStyle' );
			    $table->dropColumn('highest' );
			    $table->dropColumn('visual' );
			    $table->dropColumn('auditory' );
			    $table->dropColumn('kinesthetic' );
		    });

		    Schema::table('teachers', function (Blueprint $table) {
			    $table->dropColumn('teachingStyleResult' );
			    $table->dropColumn('highest' );
			    $table->dropColumn('visual' );
			    $table->dropColumn('auditory' );
			    $table->dropColumn('kinesthetic' );
		    });
	    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

	    if( !count( DB::select( DB::raw("SHOW COLUMNS FROM `teachers` LIKE 'highest'") ) ) ) {
			// Check if columns where deleted, then re-create them.
		    Schema::table('teachers', function (Blueprint $table) {
			    $table->string('teachingStyleResult', 16 )  ->nullable();
			    $table->string('highest', 16 )              ->nullable();
			    $table->decimal( 'visual', 3, 1 )       ->default(0.0);
			    $table->decimal('auditory', 3, 1 )      ->default(0.0);
			    $table->decimal('kinesthetic', 3, 1 )   ->default(0.0);
		    });
	    }

	    if( !count( DB::select( DB::raw("SHOW COLUMNS FROM `jobApplicant` LIKE 'highest'") ) ) ) {
		    Schema::table('jobApplicant', function (Blueprint $table) {
			    $table->string('learningStyle', 16 )    ->nullable();
			    $table->string('highest', 16 )          ->nullable();
			    $table->decimal( 'visual', 3, 1 )   ->default(0.0);
			    $table->decimal('auditory', 3, 1 )  ->default(0.0);
			    $table->decimal('kinesthetic', 3, 1 )->default(0.0);
		    });
	    }

	    // and populate with data from learningQuizResults
	    $teachers_sql = "
			UPDATE `teachers` `t` JOIN `learningQuizResults` `l` ON `t`.`quiz_result_id` = `l`.`id` 
			SET `t`.`teachingStyleResult` = `l`.`style`, `t`.`highest` = `l`.`highest`, `t`.`visual` = `l`.`visual`, `t`.`auditory` = `l`.`auditory`, `t`.`kinesthetic` = `l`.`kinesthetic`
			WHERE `t`.`quiz_result_id` IS NOT NULL
		";

	    $app_sql = "
			UPDATE `jobApplicant` `t` JOIN `learningQuizResults` `l` ON `t`.`quiz_result_id` = `l`.`id` 
			SET `t`.`learningStyle` = `l`.`style`, `t`.`highest` = `l`.`highest`, `t`.`visual` = `l`.`visual`, `t`.`auditory` = `l`.`auditory`, `t`.`kinesthetic` = `l`.`kinesthetic`
			WHERE `t`.`quiz_result_id` IS NOT NULL
		";

	    DB::statement( $teachers_sql );

	    DB::statement( $app_sql );

    	$this->delete_quiz_results();

    	$this->inet_pton2long();

	    Schema::table('teachers', function (Blueprint $table) {
		    $table->dropColumn('quiz_result_id' );
	    });

	    Schema::table('jobApplicant', function (Blueprint $table) {
		    $table->dropColumn('quiz_result_id' );
	    });
    }

	private function move_quiz_results( $table, &$err_cnt )
	{
		$class = '\App\\' . ucfirst( str_singular( $table ) );
		$style_field = ( 'jobApplicant' == $table ) ? 'learningStyle' : 'teachingStyleResult';

		/**
		 * @var \Illuminate\Database\Eloquent\Model $inst
		 */
		foreach ( $class::where('highest', '!=', '')->whereNotNull('highest')->get() as $inst ) {
			try{
				$quiz_result = \App\LearningQuizResult::create([
					'style' => $inst->{$style_field},
					'highest' => $inst->highest,
					'visual' => $inst->visual,
					'auditory' => $inst->auditory,
					'kinesthetic' => $inst->kinesthetic
				]);

				$inst->update([ 'quiz_result_id' => $quiz_result->id ]);
			}
			catch ( \Exception $e ) {
				$err_cnt++;
				\Illuminate\Support\Facades\Log::error( $inst->getKeyName() . ': ' . $inst->{$inst->getKeyName()} );
			}
		}
	}

	private function delete_quiz_results()
	{
		$sql = "DELETE FROM `learningQuizResults` WHERE `id` IN (SELECT `quiz_result_id` FROM `teachers` UNION SELECT `quiz_result_id` FROM `jobApplicant`)";
		DB::statement( $sql );
	}

	/**
	 * Conversion for up()
	 */
	private function long2inet_pton()
	{
		$rows = DB::table('learningQuizResults')->select('id', 'ip')->get();

		$rows->each(function ($row){
			DB::table('learningQuizResults')
			  ->where('id', $row->id)
			  ->update(['ip' => inet_pton(long2ip($row->ip))]);
		});
	}

	/**
	 * Backward conversion for down()
	 */
	private function inet_pton2long()
	{
		$rows = DB::table('learningQuizResults')->select('id', 'ip')->get();

		$rows->each(function ($row){
			DB::table('learningQuizResults')
			  ->where('id', $row->id)
			  ->update(['ip' => ip2long(inet_ntop($row->ip))]);
		});
	}
}
