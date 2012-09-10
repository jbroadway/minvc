<?php

namespace minvc;

/**
 * This is the front controller class that handles routing requests
 * to handler scripts.
 *
 * Usage:
 *
 *     <?php
 *     
 *     $app = new Pimple ();
 *     $view = new minvc\View (new minvc\Template);
 *     $controller = new minvc\Controller ($app, $view);
 *     
 *     echo $controller->handle ($_SERVER['REQUEST_URI']);
 */
class Controller {
	/**
	 * Dependency injection container.
	 */
	public $app;
	
	/**
	 * View object.
	 */
	public $view;

	/**
	 * Base apps folder.
	 */
	public $base = 'apps';

	/**
	 * Constructor sets the dependency injection container.
	 */
	public function __construct (\Pimple $app, View $view) {
		$this->app = $app;
		$this->view = $view;
	}

	/**
	 * Route a URI to the right file.
	 */
	public function route ($uri) {
		// Remove folder path from request
		if (! empty ($this->app['General']['path']) && strpos ($uri, $this->app['General']['path']) === 0) {
			$uri = substr ($uri, strlen ($this->app['General']['path']));
		}

		// Remove queries and hash from uri
		$uri = preg_replace ('/(\?|#).*$/', '', $uri);

		// Convert uri to filename
		$uri = ($uri === '/' || empty ($uri))
			? $this->app['General']['default_handler']
			: ltrim ($uri, '/');

		if (strpos ($uri, '/') === false) {
			// No handler specified, only app
			$app = $uri;
			$handler = 'index';
		} else {
			list ($app, $handler) = explode ('/', $uri, 2);
		}

		// Now we test the file paths until we find a match
		$file = $this->base . DIRECTORY_SEPARATOR
				. $app . DIRECTORY_SEPARATOR
				. 'handlers' . DIRECTORY_SEPARATOR
				. str_replace ('/', DIRECTORY_SEPARATOR, $handler)
				. '.php';

		// This is a list of extra URL parameters after the
		// matching file. For example, if /example/hello/joe/steve
		// matches apps/example/handlers/hello.php, then $params
		// will contain ['joe', 'steve'].
		$params = array ();

		while (! file_exists ($file)) {
			if ($handler === 'index') {
				// We've tried it all
				return $this->route ($this->app['General']['404_handler']);
			}

			// Remove one parameter from the end and try again
			$parts = explode ('/', $handler);
			if (count ($parts) > 0) {
				array_unshift ($params, array_pop ($parts));
			}
			$handler = join ('/', $parts);

			if (empty ($handler)) {
				// Try appname/index as default handler for the app
				$handler = 'index';
			}
			
			$file = $this->base . DIRECTORY_SEPARATOR
					. $app . DIRECTORY_SEPARATOR
					. 'handlers' . DIRECTORY_SEPARATOR
					. str_replace ('/', DIRECTORY_SEPARATOR, $handler)
					. '.php';
		}

		return array ($file, $params);
	}

	/**
	 * Route a request to the right handler.
	 */
	public function handle ($uri) {
		list ($file, $params) = $this->route ($uri);

		return require ($file);
	}
}