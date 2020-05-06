<?php

namespace App;


use Tests\TestCase;

class GroupHistoryTest extends TestCase {

	public function testUsuario() {
		$u_email = GroupHistory::find(2)->usuario->email;
		$this->assertEquals('chinese@user.com', $u_email );
	}

	public function testGroup() {
		$g_name = GroupHistory::find(2)->group->groupName;
		$this->assertTrue( $g_name == 'Live Lingua (Test Group)' );
	}

	public function testStudent() {
		$this->assertFalse( count( GroupHistory::find( 2 )->student ) > 0 );
		$this->assertTrue( count( GroupHistory::find( 1 )->student ) > 0 );
	}

	public function testTeacher() {
		$this->assertSame(188, GroupHistory::find( 2 )->teacher->teacherID );
	}

	public function testAllRelations() {
		$gh = GroupHistory::with(['usuario', 'group', 'teacher', 'student'])->find(2);
		$this->assertEquals(10, $gh->usuario->id);
		$this->assertEquals(188, $gh->teacher->teacherID);
		$this->assertNull( $gh->student );
		$this->assertEquals( 'RayG', $gh->group->contactFirstName );
	}
}
