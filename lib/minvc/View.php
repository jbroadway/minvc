<?php

namespace minvc;

/**
 * Separates presentation logic from handlers.
 *
 * Usage:
 *
 *     <?php
 *     
 *     $view = new minvc\View (new minvc\Template);
 *     
 *     // with a closure
 *     echo $view->render (function ($params) {
 *         return sprintf ('<p>%s</p>', join (', ', $params));
 *     });
 *     
 *     // with a template
 *     echo $view->render ('template.html', $params);
 */
class View {
	/**
	 * Renderer will handle templates. Must satisfy the following
	 * interface:
	 *
	 *     interface TemplateRenderer {
	 *         public function render ($template, $data = array ());
	 *     }
	 */
	public $renderer;

	/**
	 * Constructor sets the rendering engine.
	 */
	public function __construct ($renderer) {
		$this->renderer = $renderer;
	}
	
	/**
	 * Render a view. View may be a callable function or method
	 * that receives the parameters, or the name of a template
	 * to be passed to the template renderer.
	 */
	public function render ($view, $params = null) {
		if (is_callable ($view)) {
			return $view ($params);
		}
		return $this->renderer->render ($view, $params);
	}
}