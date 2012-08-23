<?php

require_once 'lib/minvc/Autoloader.php';
spl_autoload_register ('minvc\Autoloader::load');

use minvc\Filter;

class FilterTest extends PHPUnit_Framework_TestCase {
	function test_sanitize () {
		ob_start ();
		Filter::sanitize ('<br>');
		$out = ob_get_clean ();

		$this->assertEquals ('&lt;br&gt;', $out);
	}
}