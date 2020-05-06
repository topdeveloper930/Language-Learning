<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UsuariosImportTeachers extends Migration
{
	private  $dump_storage_path = 'backups/%s.sql';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//	    $this->dump_table( 'teachers' );
//	    $this->dump_table( 'usuarios' );

        $this->delete_inactive_duplicates();

	    Schema::table('teachers', function (Blueprint $table) {
		    $table->integer('usuario_id', false, true)->unique()->nullable();
	    });

	    $this->populate_usuarios_with_teachers();

	    // Widely used by legacy code
//	    Schema::table('teachers', function (Blueprint $table) {
//		    $table->dropColumn( 'email' );
//		    $table->dropColumn( 'password' );
//	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('teachers', function (Blueprint $table) {
		    $table->dropColumn('usuario_id');
	    });
//	    DB::unprepared( file_get_contents( storage_path(  sprintf( $this->dump_storage_path, 'teachers' ) ) ) );
//	    DB::unprepared( file_get_contents( storage_path(  sprintf( $this->dump_storage_path, 'usuarios' ) ) ) );
    }

    private function delete_inactive_duplicates()
    {
	    $emails = [];
	    $duplicates = DB::table('teachers')
	                    ->select( DB::raw('`email`, COUNT(*) AS c') )
	                    ->groupBy('email')
	                    ->having('c', '>', 1)
	                    ->get();

	    if( $duplicates->count() )
		    foreach( $duplicates AS $d )
			    array_push( $emails, $d->email );

	    DB::table('teachers')
	      ->whereIn('email', $emails)
	      ->where('activeTeacher', 'Inactive')
	      ->delete();
    }

	private function dump_table( $table )
	{
		$cmd = (App::environment('local'))
			? 'D:\OSPanel\modules\database\MySQL-5.7-x64\bin\mysqldump.exe'
			: 'mysqldump';

		$process = new \Symfony\Component\Process\Process(sprintf(
			'%s -u%s -p%s %s %s > %s',
			$cmd,
			config('database.connections.mysql.username'),
			config('database.connections.mysql.password'),
			config('database.connections.mysql.database'),
			$table,
			storage_path( sprintf( $this->dump_storage_path, $table ) )
		));

		return $process->mustRun();
	}

	private function populate_usuarios_with_teachers()
	{
		$sql = "SELECT `teacherID`, `email`, `password` FROM `teachers`";
		$teachers = DB::select( $sql );

		foreach ($teachers as $teacher) {
			try{
				$usuario = \App\User::create([
					'email' => $teacher->email,
					'password' => Hash::make( $teacher->password )
				]);
			}
			catch ( \Exception $e ) {
				$usuario = \App\User::where('email', $teacher->email)->first();
			}

			$this->update_teacher_usuario_id( $teacher->teacherID, $usuario->id );
		}
	}

	private function update_teacher_usuario_id( $user_id, $usuario_id )
	{
		DB::unprepared("UPDATE `teachers` SET `usuario_id` = $usuario_id WHERE `teacherID` = $user_id");
	}

	private function get_min_max_usuario_id()
    {
	    return DB::table('teachers')
	      ->select(DB::raw('MIN(`usuario_id`) as `from`, MAX(`usuario_id`) as `to`'))
	      ->first();
    }
}
