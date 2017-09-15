<?php
/**
 * uFramework - Simple and Minimal PHP framework
 *
 * @package uFramework
 * @author Jonathan Esquivel
 * @link https://github.com/jefr26/uFramework/
 * @license https://choosealicense.com/licenses/mpl-2.0/ Mozilla Public License 2.0
 */

// Configuration for: Error reporting
// Useful to show every little problem during development, but only show hard
// errors in production
define('ENVIRONMENT', 'development');
if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

// Configuration for: URL
define('URL_PUBLIC_FOLDER', 'public');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define('URL_PROTOCOL', $protocol);
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);

// Default controller
define('DEFAULT_CONTROLLER', 'DefaultController');