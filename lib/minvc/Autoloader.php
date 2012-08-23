<?php

namespace minvc;

/**
 * Very rudimentary autoloader with PSR-0 fallback support.
 * First searches for appname\ClassName in apps/{*}/{models|helpers}.
 */
class Autoloader {
	/**
	 * Base folder for libraries.
	 */
	public static $base = 'lib';

	/**
	 * Base folder for apps.
	 */
	public static $app_base = 'apps';

	/**
	 * App folder list.
	 */
	public static $app_folders = array ('models', 'helpers');

	/**
	 * Load based on app libraries.
	 */
	public static function load ($class) {
		$lib = ltrim ($class, '\\');

		if (strpos ($lib, '\\') !== false) {
			list ($app, $lib) = explode ('\\', $lib, 2);

			if (! file_exists (self::$app_base . DIRECTORY_SEPARATOR . $app)) {
				// No app by this name, skip to PSR-0
				return self::psr0 ($class);
			}

			$lib = str_replace ('\\', DIRECTORY_SEPARATOR, $lib);

			foreach (self::$app_folders as $folder) {
				// Pattern: {app_base}/{app}/{folder}/{lib}.php
				$file = self::$app_base . DIRECTORY_SEPARATOR
						. $app . DIRECTORY_SEPARATOR
						. $folder . DIRECTORY_SEPARATOR
						. $lib . '.php';

				if (file_exists ($file)) {
					require $file;
					return true;
				}
			}
		}

		// Fall back to PSR-0
		return self::psr0 ($class);
	}

	/**
	 * Load based on simple paths.
	 */
	public static function psr0 ($class) {
		$class = ltrim ($class, '\\');
		$file = '';
		$ns = '';

		if ($last_ns_pos = strripos ($class, '\\')) {
			$ns = substr ($class, 0, $last_ns_pos);
			$class = substr ($class, $last_ns_pos + 1);
			$file = str_replace ('\\', DIRECTORY_SEPARATOR, $ns) . DIRECTORY_SEPARATOR;
		}
		$file = self::$base . '/' . $file . str_replace ('_', DIRECTORY_SEPARATOR, $class) . '.php';

		require $file;
		return true;
	}
}