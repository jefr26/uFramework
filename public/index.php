<?php
/**
 * uFramework - Simple and Minimal PHP framework
 *
 * @package uFramework
 * @author Jonathan Esquivel
 * @link https://github.com/jefr26/uFramework/
 * @license https://choosealicense.com/licenses/mpl-2.0/ Mozilla Public License 2.0
 */

// Trow exceptions insted of errors
function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error is not included in error_reporting
        return;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
}
\set_error_handler("exception_error_handler");

// set a constant that holds the project's folder path, like "/var/www/".
// DIRECTORY_SEPARATOR adds a slash to the end of the path
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// set a constant that holds the project's "core" folder, like "/var/www/core".
define('CORE', ROOT . 'Core' . DIRECTORY_SEPARATOR);

// set a constant that holds the project's "application" folder, like "/var/www/application".
define('APP', ROOT . 'Application' . DIRECTORY_SEPARATOR);

// This is the auto-loader for Composer-dependencies (to load tools into your project).
require ROOT . 'vendor/autoload.php';

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

// load application class
use Core\Application;

// start the application
$app = new Application();
$app->start_app();
