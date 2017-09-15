<?php

namespace Core;

/**
 * Clase para renderizar las vistas solicitadas
 * @package Ofrfeo\Core
 */
class View
{
    /**
     * Vista a cargar
     * @var string
     */
    private $view = '';

    /**
     * Datos que se le pasan a la vista
     * @var null
     */
    private $data = null;

    /**
     * Variable para determinar se debe cargar la plantilla predeterminada
     * @var null
     */
    private $template = null;


    /**
     * Setea las variables para poder renderizar las vistas
     * @param string      $view     Ruta relativa de la vista.
     * @param array|null  $data     Arreglo con los datos a pasar.
     * @param string|null $template Variable que determina si se debe renderizar
     *                              la plantilla.
     * @return void
     */
    public function __construct(string $view, array $data = null, bool $template = true)
    {
        $this->view = $view;
        $this->data = $data;
        $this->template = $template;
    }

    /**
     * Rdenderiza la vista seleccionada
     * @return void
     */
    public function render()
    {
        // Extrae los datos del arregloe
        (is_array($this->data) ? extract($this->data) : null);

        ob_start();
        // Si se va a cargar la plantilla
        if ($this->template) {
            include_once APP . 'view/_templates/header.php';
        }

        // Si existe la vista se carga, de lo contrario se muestra la pagina de
        // error
        if (file_exists(APP . 'view/' . $this->view . '.php')) {
            include_once APP . 'view/' . $this->view . '.php';
        } else {
            include_once APP . 'view/error/404.php';
        }

        // Si se va a cargar la plantilla
        if ($this->template) {
            include_once APP . 'view/_templates/footer.php';
        }

        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
        exit;
    }
}