<?php
/**
 * uFramework - Simple and Minimal PHP framework
 *
 * @package uFramework
 * @author Jonathan Esquivel
 * @link https://github.com/jefr26/uFramework/
 * @license https://choosealicense.com/licenses/mpl-2.0/ Mozilla Public License 2.0
 */

namespace Application\Controllers;

use Core\Application;
use Application\Models\TestModel;

/**
 * Default controller
 */
class DefaultController extends Application
{

    public function index()
    {

        echo '<pre>';
        echo 'Hello World!';

        echo "\n";

        $test = new TestModel();
        print_r($test->testFunction());

        echo "\n";
    }
}
