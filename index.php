<?php

/**
 * This is the minvc front controller. It contains initializations
 * and performs request routing to handlers. All dynamic requests
 * are routed through this script.
 */

/**
 * For compatibility with PHP 5.4's built-in web server, we bypass
 * the front controller for requests with file extensions and
 * return false.
 */
if (php_sapi_name () === 'cli-server'
	&& isset ($_SERVER['REQUEST_URI'])
	&& preg_match ('/\.[a-zA-Z0-9]+$/', parse_url ($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
	return false;
}

/**
 * If we're on the command line, set the request to use
 * the first argument passed to the script.
 */
if (defined ('STDIN')) {
	$_SERVER['REQUEST_URI'] = '/' . $argv[1];
}

/**
 * Register the autoloader.
 */
require 'lib/minvc/Autoloader.php';
spl_autoload_register ('minvc\Autoloader::load');

/**
 * Look for a MINVC_ENV environment variable to load the appropriate
 * configuration file.
 */
define ('MINVC_ENV', getenv ('MINVC_ENV') ? getenv ('MINVC_ENV') : 'config');

/**
 * Load the configurations into a service container (aka dependency
 * injection container), and instantiate the Controller and View
 * objects.
 */
require 'lib/Pimple.php';
$app = new Pimple ();

$conf = parse_ini_file ('config/' . MINVC_ENV . '.php', true);
foreach ($conf as $section => $settings) {
	$app[$section] = $settings;
}

$view = new minvc\View (new $app['General']['template_renderer']);
$controller = new minvc\Controller ($app, $view);

/**
 * Set the default timezone to avoid warnings in date functions.
 */
date_default_timezone_set ($app['General']['timezone']);

/**
 * Load any custom bootstrapping code, such as additional dependencies.
 */
if (file_exists ('bootstrap.php')) {
	require 'bootstrap.php';
}

/**
 * Route and return the current request. Use gzip compression if
 * available.
 */
$out = $controller->handle ($_SERVER['REQUEST_URI']);
if ($app['General']['compress_output'] && extension_loaded ('zlib')) {
	ob_start ('ob_gzhandler');
}
echo $out;