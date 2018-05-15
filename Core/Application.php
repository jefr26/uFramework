<?php
/**
 * uFramework - Simple and Minimal PHP framework
 *
 * @package uFramework
 * @author Jonathan Esquivel
 * @link https://github.com/jefr26/uFramework/
 * @license https://choosealicense.com/licenses/mpl-2.0/ Mozilla Public License 2.0
 */

namespace Core;

use \Core\View as View;

class Application
{
    /** @var null The controller */
    private $url_controller = null;

    /** @var null The method (of the above controller), often also named "action" */
    private $url_action = null;

    /** @var array URL parameters */
    private $url_params = array();

    public function __construct()
    {

    }

    /**
     * "Start" the application:
     * Analyze the URL elements and calls the according controller/method or the fallback
     */
    public function start_app()
    {
        // create array with URL parts in $url
        $this->splitUrl();

        // check for controller: no controller given ? then load start-page
        if (!$this->url_controller) {
            $controller = "\\Application\\Controllers\\" . DEFAULT_CONTROLLER;
            $page = new $controller();
            $page->index();
        } elseif (file_exists(APP . 'Controllers/' . ucfirst($this->url_controller) . 'Controller.php')) {
            // here we did check for controller: does such a controller exist ?
            // if so, then load this file and create this controller
            $controller = "\\Application\\Controllers\\" . ucfirst($this->url_controller) . 'Controller';
            $this->url_controller = new $controller();

            // check for method: does such a method exist in the controller ?
            if (method_exists($this->url_controller, $this->url_action)) {

                if (!empty($this->url_params)) {
                    // Call the method and pass arguments to it
                    call_user_func_array(array($this->url_controller, $this->url_action), $this->url_params);
                } else {
                    // If no parameters are given, just call the method without parameters, like $this->home->method();
                    $this->url_controller->{$this->url_action}();
                }

            } else {
                if (strlen($this->url_action) == 0) {
                    // no action defined: call the default index() method of a selected controller
                    $this->url_controller->index();
                } else {
                    header('location: ' . URL . 'error/404');
                }
            }
        } else {
            header('location: ' . URL . 'error/404');
        }
    }

    /**
     * Split the URL in segments
     */
    private function splitUrl()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            // split URL
            $url = trim($_SERVER['REQUEST_URI'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // Put URL parts into according properties
            // By the way, the syntax here is just a short form of if/else, called "Ternary Operators"
            // @see http://davidwalsh.name/php-shorthand-if-else-ternary-operators
            $this->url_controller = isset($url[0]) ? $url[0] : null;
            $this->url_action = isset($url[1]) ? (is_numeric($url[1]) ? '_' : null) . $url[1] : null;

            // Remove controller and action from the split URL
            unset($url[0], $url[1]);

            // Rebase array keys and store the URL params
            $this->url_params = array_values($url);
        }
    }

    /**
     * Metodo para renderizar la vista seleccionada
     * @param  string       $view     Ruta relativa de la vista.
     * @param  array|null   $data     Arreglo de datos a pasar a la vista.
     * @param  boolean      $template Variable para determinar se de be usar la
     *                                plantilla.
     * @return void
     */
    public function view(string $view, array $data = null, bool $template = true)
    {
        $v = new View($view, $data, $template);
        $v->render();
    }
}
