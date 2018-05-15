<?php

namespace Core;

use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 * Class for render views using Twig
 *
 * @package uFramework
 * @author Jonathan Esquivel
 * @link https://github.com/jefr26/uFramework/
 * @license https://choosealicense.com/licenses/mpl-2.0/ Mozilla Public License 2.0
 */
class Views
{
    /**
     * Create cache dir if not exist
     * @return void
     */
    public function __construct()
    {
        if (!is_dir(ROOT . 'cache')) {
            mkdir(ROOT . 'cache', 0777);
        }
        return $this;
    }

    /**
     * Render the view with the data
     * @param string    $view     View route
     * @param array     $data     Array with the data
     * @return void
     */
    public function render(string $view, array $data)
    {
        // Public URL
        $data['url'] = URL;

        // Load application views
        $loader = new Twig_Loader_Filesystem(APP . "Views");
        $twig = new Twig_Environment(
            $loader,
            array(
                'cache' => ROOT . 'cache',
                'debug' => true,
                'auto_reload' => true,
                'strict_variables' => true,
            ));

        $twig->AddExtension(new \Twig_Extension_Debug());
        echo $twig->render($view . '.twig', $data);
        exit;
    }
}
