<?php

namespace minvc;

/**
 * Separate from the View class, we have a simple template
 * renderer.
 *
 * Usage:
 *
 *     <?php
 *     
 *     $tpl = new Template;
 *     
 *     echo $tpl->render ('template.html', array ('foo' => 'bar'));
 */
class Template {
	public function render ($view, $data = array ()) {
		if (! file_exists ($view)) {
			throw new RuntimeException ('Template not found: ' . $view);
		}

		// Normalize objects to arrays
		$data = is_array ($data) ? $data : (array) $data;

		// Extract template data
		extract ($data);

		// Return the rendered template
		ob_start ();
		require ($view);
		return ob_get_clean ();
	}
}