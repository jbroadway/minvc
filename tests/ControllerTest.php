<?php

require_once 'lib/Pimple.php';
require_once 'lib/minvc/Autoloader.php';
spl_autoload_register ('minvc\Autoloader::load');

use minvc\Controller,
	minvc\Template,
	minvc\View;

class ControllerTest extends PHPUnit_Framework_TestCase {
	function setUp () {
		$this->app = new Pimple ();
		$this->app['General'] = array (
			'path' => '',
			'default_handler' => 'example/index',
			'404_handler' => 'example/404'
		);
	}

	function test_route () {
		$c = new Controller ($this->app, new View (new Template));

		$this->assertEquals (
			array ('apps/example/handlers/index.php', array ()),
			$c->route ('/')
		);
		$this->assertEquals (
			array ('apps/example/handlers/index.php', array ()),
			$c->route ('/example')
		);
		$this->assertEquals (
			array ('apps/example/handlers/index.php', array ()),
			$c->route ('/example/index')
		);
		$this->assertEquals (
			array ('apps/example/handlers/index.php', array ()),
			$c->route ('example')
		);
		$this->assertEquals (
			array ('apps/example/handlers/index.php', array ()),
			$c->route ('example/index')
		);
		$this->assertEquals (
			array ('apps/example/handlers/index.php', array ('fooo')),
			$c->route ('/example/fooo')
		);
		$this->assertEquals (
			array ('apps/example/handlers/index.php', array ('fooo', 'baaar')),
			$c->route ('/example/fooo/baaar')
		);
		$this->assertEquals (
			array ('apps/example/handlers/404.php', array ()),
			$c->route ('/fooo')
		);
	}

	function test_handle () {
		$c = new Controller ($this->app, new View (new Template));

		$this->assertEquals ('Hello world', $c->handle ('/example/hello'));
	}
}