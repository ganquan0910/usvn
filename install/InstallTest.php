<?php
// Call InstallTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "InstallTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'install/Install.php';
require_once 'www/USVN/autoload.php';

/**
 * Test class for Install.
 * Generated by PHPUnit_Util_Skeleton on 2007-03-20 at 09:07:00.
 */
class InstallTest extends PHPUnit_Framework_TestCase {
	private $db;

	/**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("InstallTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
		$params = array ('host'     => 'localhost',
                 'username' => 'usvn-test',
                 'password' => 'usvn-test',
                 'dbname'   => 'usvn-test');

		$this->db = Zend_Db::factory('PDO_MYSQL', $params);
		Zend_Db_Table::setDefaultAdapter($this->db);
		USVN_Db_Utils::deleteAllTables($this->db);
    }

    protected function tearDown() {
		USVN_Db_Utils::deleteAllTables($this->db);
    }
/*
    public function testInstallDbHostIncorrect() {
		try {
			Install::installDb("tests/tmp/config.ini", "fake.usvn.info", "usvn-test", "usvn-test", "usvn-test", "usvn_");
		}
		catch (Exception $e) {
			return;
		}
		$this->assertFalse(true);
    }

    public function testInstallDbUserIncorrect() {
		try {
			Install::installDb("tests/tmp/config.ini", "localhost", "usvn-fake", "usvn-test", "usvn-test", "usvn_");
		}
		catch (Exception $e) {
			return;
		}
		$this->assertFalse(true);
    }

	public function testInstallDbPasswordIncorrect() {
		try {
			Install::installDb("tests/tmp/config.ini", "localhost", "usvn-test", "usvn-fake", "usvn-test", "usvn_");
		}
		catch (Exception $e) {
			return;
		}
		$this->assertFalse(true);
    }

	public function testInstallDbDatabaseIncorrect() {
		try {
			Install::installDb("tests/tmp/config.ini", "localhost", "usvn-test", "usvn-test", "usvn-fake", "usvn_");
		}
		catch (Exception $e) {
			return;
		}
		$this->assertFalse(true);
    }

	public function testInstallDbConfigFileNotWritable() {
		try {
			Install::installDb("tests/fake/config.ini", "localhost", "usvn-test", "usvn-test", "usvn-test", "usvn_");
		}
		catch (Exception $e) {
			return;
		}
		$this->assertFalse(true);
    }

	public function testInstallDbTestLoadDb() {
		Install::installDb("tests/tmp/config.ini", "localhost", "usvn-test", "usvn-test", "usvn-test", "usvn_");
		$list_tables =  $this->db->listTables();
		$this->assertTrue(in_array('usvn_users', $list_tables));
		$this->assertTrue(in_array('usvn_files', $list_tables));
    }

	public function testInstallDbTestLoadDbOtherPrefixe() {
		Install::installDb("tests/tmp/config.ini", "localhost", "usvn-test", "usvn-test", "usvn-test", "fake_");
		$list_tables =  $this->db->listTables();
		$this->assertFalse(in_array('usvn_users', $list_tables));
		$this->assertFalse(in_array('usvn_files', $list_tables));
		$this->assertTrue(in_array('fake_users', $list_tables));
		$this->assertTrue(in_array('fake_files', $list_tables));
    }

	public function testInstallDbTestConfigFile() {
		Install::installDb("tests/tmp/config.ini", "localhost", "usvn-test", "usvn-test", "usvn-test", "usvn_");
		$this->assertTrue(file_exists("tests/tmp/config.ini"));
		$config = new Zend_Config_Ini("tests/tmp/config.ini", "general");
		$this->assertEquals("localhost", $config->database->options->host);
		$this->assertEquals("usvn-test", $config->database->options->dbname);
		$this->assertEquals("usvn-test", $config->database->options->username);
		//$this->assertEquals("usvn-test", $config->database->options->username); Faire un test pour le password
		$this->assertEquals("pdo_mysql", $config->database->adapterName);
		$this->assertEquals("usvn_", $config->database->prefixe);
    }
*/
	public function test_empty()
	{
	}
}

// Call InstallTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "InstallTest::main") {
    InstallTest::main();
}
?>
