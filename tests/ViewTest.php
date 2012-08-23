<?php

require_once 'lib/minvc/Autoloader.php';
spl_autoload_register ('minvc\Autoloader::load');

use minvc\Template,
	minvc\View;

class ViewTest extends PHPUnit_Framework_TestCase {
	function test_render () {
		$v = new View (new Template);

		$this->assertEquals (
			'one, two',
			$v->render (
				function ($params) {
					return join (', ', $params);
				},
				array ('one', 'two')
			)
		);
		
		$this->assertContains (
			'It Works!',
			$v->render ('apps/example/views/index.php')
		);
	}
}