<?php

namespace minvc;

class Filter {
	/**
	 * Sanitizes and echoes a value for safe output, helping to prevent
	 * XSS attacks. Please note that this method can still be insecure
	 * if used in an unquoted portion of an HTML tag, for example:
	 *
	 * Don't do this:
	 *
	 *     <a href="/example" <?php minvc\Filter::sanitize ($some_var); ?>>click me</a>
	 *
	 * But this is okay:
	 *
	 *     <a href="/example" id="<?php minvc\Filter::sanitize ($some_var); ?>">click me</a>
	 *
	 * And so is this:
	 *
	 *     <span id="some-var"><?php minvc\Filter::sanitize ($some_var); ?></span>
	 *
	 * In the first case, if the string were to contain something like
	 * `onclick=alert(document.cookie)` then your visitors would be
	 * exposed to the malicious JavaScript.
	 *
	 * The key is to know where your data comes from, and to act accordingly.
	 * Not all cases of the first example are necessarily a security hole,
	 * but it should only be used if you know the source and have validated
	 * your data beforehand.
	 */
	public static function sanitize ($val, $charset = 'UTF-8') {
		if (! defined ('ENT_SUBSTITUTE')) {
			define ('ENT_SUBSTITUTE', ENT_IGNORE);
		}
		echo htmlspecialchars ($val, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
	}
}