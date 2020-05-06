<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Alter members table
	    DB::unprepared(file_get_contents('database/sql/up/2018_12_01_alter_members_table.sql'));

	    $this->populateUsuariosTableWithMembers();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');

	    DB::unprepared(file_get_contents('database/sql/down/2018_12_01_alter_members_table.sql'));
    }

    private function populateUsuariosTableWithMembers()
    {
    	$sql = "SELECT `userID`, `email`, `password` FROM `members` WHERE `userType` != 'user' AND `userType` != 'individual'";
	    $members = DB::select( $sql );

	    foreach ($members as $member) {
		    $usuario = \App\User::create(array(
			    'email' => $member->email,
			    'password' => Hash::make( $member->password )
		    ));

		    $this->updateMemberUsuarioId( $member->userID, $usuario->id );
	    }
    }

    private function updateMemberUsuarioId( $user_id, $usuario_id )
    {
	    DB::unprepared("UPDATE `members` SET `usuario_id` = $usuario_id WHERE `userID` = $user_id");
    }
}
