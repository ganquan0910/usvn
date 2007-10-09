<?php
// Call USVN_Db_Table_Row_GroupTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "USVN_Db_Table_Row_ProjectTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'www/USVN/autoload.php';
define('CONFIG_FILE', './tests/tmp/config.ini');


/**
 * Test class for USVN_Db_Table_Row_Group.
 * Generated by PHPUnit_Util_Skeleton on 2007-04-18 at 14:39:49.
 */
class USVN_Db_Table_Row_ProjectTest extends USVN_Test_DB {
	private $projectTable;
	private $project;
	private $projectid;
	private $groups;
	private $users;

	/**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("USVN_Db_Table_Row_ProjectTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function setUp() {
		parent::setUp();
		$this->projectTable = new USVN_Db_Table_Projects();
		$this->project = $this->projectTable->fetchNew();
		$this->project->projects_name = 'testStem';
		$this->project->projects_start_date = '2007-04-01 15:29:57';
		$this->projectid = $this->project->save();
		$this->groups = new USVN_Db_Table_Groups();
		$this->groups->insert(
			array(
				"groups_id" => 42,
				"groups_name" => "test",
				"groups_description" => "test"
			)
		);
		$this->groups->insert(
			array(
				"groups_id" => 43,
				"groups_name" => "test2",
				"groups_description" => "test2"
			)
		);
		$this->groups->insert(
			array(
				"groups_id" => 44,
				"groups_name" => "test3",
				"groups_description" => "test3"
			)
		);
		$this->users = new USVN_Db_Table_Users();
		$this->users->insert(
			array(
				"users_id" => 42,
				"users_login" => "test",
				"users_password" => "pass",
			)
		);
		$this->users = new USVN_Db_Table_Users();
		$this->users->insert(
			array(
				"users_id" => 43,
				"users_login" => "test2",
				"users_password" => "pass",
			)
		);
		$this->users = new USVN_Db_Table_Users();
		$this->users->insert(
			array(
				"users_id" => 44,
				"users_login" => "test3",
				"users_password" => "pass",
			)
		);
    }

    public function testProject()
	{
		$this->assertEquals('testStem', $this->project->projects_name);
		$this->assertEquals('testStem', $this->project->name);
	}

	public function testAddGroup()
	{
		$this->project->addGroup($this->groups->find(42)->current());
		$this->project->addGroup($this->groups->find(43)->current());
		$this->groups = $this->project->findManyToManyRowset('USVN_Db_Table_Groups', 'USVN_Db_Table_GroupsToProjects');
		$res = array();
		foreach ($this->groups as $group) {
			array_push($res, $group->groups_name);
		}
		$this->assertContains("test", $res);
		$this->assertContains("test2", $res);
		$this->assertNotContains("test3", $res);
		$this->assertNotContains("notest", $res);
		$access = new USVN_FilesAccessRights($this->project->id);
		$access = $access->findByPath(42, "/");
		$this->assertTrue($access['read']);
		$this->assertFalse($access['write']);
	}

	public function testDeleteGroup()
	{
		$this->project->addGroup($this->groups->find(42)->current());
		$this->project->addGroup($this->groups->find(43)->current());
		$this->find_groups = $this->project->findManyToManyRowset('USVN_Db_Table_Groups', 'USVN_Db_Table_GroupsToProjects');
		$res = array();
		foreach ($this->find_groups as $group) {
			array_push($res, $group->groups_name);
		}
		$this->assertContains("test", $res);
		$this->assertContains("test2", $res);
		$this->project->deleteGroup($this->groups->find(42)->current());
		$this->find_groups = $this->project->findManyToManyRowset('USVN_Db_Table_Groups', 'USVN_Db_Table_GroupsToProjects');
		$res = array();
		foreach ($this->find_groups as $group) {
			array_push($res, $group->groups_name);
		}
		$this->assertNotContains("test", $res);
		$this->assertContains("test2", $res);
		$this->project->deleteGroup($this->groups->find(43)->current());
		$this->groups = $this->project->findManyToManyRowset('USVN_Db_Table_Groups', 'USVN_Db_Table_GroupsToProjects');
		$res = array();
		foreach ($this->groups as $group) {
			array_push($res, $group->groups_name);
		}
		$this->assertNotContains("test", $res);
		$this->assertNotContains("test2", $res);
	}

	public function testDeleteGroupWithFIleRights()
	{
		$table_files = new USVN_Db_Table_FilesRights();
			$fileid = $table_files->insert(array(
    		'projects_id'		=> $this->project->id,
			'files_rights_path' => '/trunk'
		));
		$table_groupstofiles = new USVN_Db_Table_GroupsToFilesRights();

		$this->project->addGroup($this->groups->find(42)->current());
		$table_groupstofiles->insert(array('files_rights_id' 		  => $fileid,
										   'files_rights_is_readable' => true,
				 						   'files_rights_is_writable' => false,
			       	 					   'groups_id'	 			  => 42));
		$this->assertNotNull($table_groupstofiles->findByIdRightsAndIdGroup($fileid, 42));
		$this->project->deleteGroup($this->groups->find(42)->current());
		$this->assertNull($table_groupstofiles->findByIdRightsAndIdGroup($fileid, 42));
	}

	public function testGroupIsMember()
	{
		$group = $this->groups->find(42)->current();
		$this->assertFalse($this->project->groupIsMember($group));
		$this->project->addGroup($group);
		$this->assertTrue($this->project->groupIsMember($group));
	}

	public function testgetUsersGroupMembers()
	{
		$this->project->addGroup($this->groups->find(42)->current());
		$this->project->addGroup($this->groups->find(43)->current());
		$this->assertEquals(count($this->project->getUsersGroupMembers()), 0);
		$this->groups->find(42)->current()->addUser($this->users->find(42)->current());
		$this->groups->find(44)->current()->addUser($this->users->find(44)->current());
		$this->assertEquals(count($this->project->getUsersGroupMembers()), 1);
		$this->groups->find(43)->current()->addUser($this->users->find(42)->current());
		$this->assertEquals(count($this->project->getUsersGroupMembers()), 1);
		$this->assertEquals($this->project->getUsersGroupMembers()->current()->users_login, "test");
		$this->groups->find(43)->current()->addUser($this->users->find(43)->current());
		$this->assertEquals(count($this->project->getUsersGroupMembers()), 2);

		$projectTable = new USVN_Db_Table_Projects();
		$project = $projectTable->fetchNew();
		$project->projects_name = 'testNoplay';
		$project->projects_start_date = '2007-04-01 15:29:57';
		$project->save();
		$project->addGroup($this->groups->find(42)->current());
		$this->assertEquals(count($this->project->getUsersGroupMembers()), 2);
	}

	public function testAddUser()
	{
		$this->project->addUser($this->groups->find(42)->current());
		$this->project->addUser($this->groups->find(43)->current());
		$groups = $this->project->findManyToManyRowset('USVN_Db_Table_Users', 'USVN_Db_Table_UsersToProjects');
		$res = array();
		foreach ($groups as $group) {
			array_push($res, $group->users_login);
		}
		$this->assertContains("test", $res);
		$this->assertContains("test2", $res);
		$this->assertNotContains("test3", $res);
		$this->assertNotContains("notest", $res);
	}

	public function testDeleteUser()
	{
		$this->project->addUser($this->users->find(42)->current());
		$this->project->addUser($this->users->find(43)->current());
		$this->find_users = $this->project->findManyToManyRowset('USVN_Db_Table_Users', 'USVN_Db_Table_UsersToProjects');
		$res = array();
		foreach ($this->find_users as $user) {
			array_push($res, $user->users_login);
		}
		$this->assertContains("test", $res);
		$this->assertContains("test2", $res);
		$this->project->deleteUser($this->users->find(42)->current());
		$this->find_users = $this->project->findManyToManyRowset('USVN_Db_Table_Users', 'USVN_Db_Table_UsersToProjects');
		$res = array();
		foreach ($this->find_users as $user) {
			array_push($res, $user->users_login);
		}
		$this->assertNotContains("test", $res);
		$this->assertContains("test2", $res);
		$this->project->deleteUser($this->users->find(43)->current());
		$this->users = $this->project->findManyToManyRowset('USVN_Db_Table_Users', 'USVN_Db_Table_UsersToProjects');
		$res = array();
		foreach ($this->users as $user) {
			array_push($res, $user->users_login);
		}
		$this->assertNotContains("test", $res);
		$this->assertNotContains("test2", $res);
	}

	public function testUserIsMember()
	{
		$user = $this->users->find(42)->current();
		$this->assertFalse($this->project->userIsAdmin($user));
		$this->project->addUser($user);
		$this->assertTrue($this->project->userIsAdmin($user));
	}

}

// Call USVN_Db_Table_Row_GroupTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "USVN_Db_Table_Row_ProjectTest::main") {
    USVN_Db_Table_Row_ProjectTest::main();
}
?>
