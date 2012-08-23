<?php

require_once 'lib/minvc/Autoloader.php';
spl_autoload_register ('minvc\Autoloader::load');

use minvc\Template;

class TemplateTest extends PHPUnit_Framework_TestCase {
	function test_render () {
		$t = new Template;
		
		$this->assertContains (
			'It Works!',
			$t->render ('apps/example/views/index.php')
		);
	}
}