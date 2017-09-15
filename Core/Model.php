<?php

namespace Core;

use \PDO;
use Core\Libs\Helper;

class Model
{
    /**
     * @var null Database Connection
     */
    public $db = null;

    /**
     * @var null Database info
     */
    private static $database = null;

    /**
     *
     */
    public $debug = false;

    /**
     * Whenever model is created, open a database connection.
     */
    public function __construct()
    {
        // Load database config
        require_once APP . 'config/database.php';
        self::$database = $database;

        try {
            self::openDatabaseConnection();
        } catch (\PDOException $e) {
            error_log($e);
            exit('Database connection could not be established.');
        }
    }

    /**
     * Make the connection
     * @access private
     * @return void
     */
    private function openDatabaseConnection()
    {
        // database connection options
        $options = array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_AUTOCOMMIT => false,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_CASE => PDO::CASE_LOWER,
        );

        // extra options
        switch (self::$database['DB_TYPE']) {
            case 'oci':
                $options[PDO::ATTR_ORACLE_NULLS] = PDO::NULL_EMPTY_STRING;
                break;
            case 'pgsql':
                $charset = 'options=\'--client_encoding=' . self::$database['DB_CHARSET'];
                break;
            default:
                $charset = 'charset=' . self::$database['DB_CHARSET'];
                break;
        }

        // make the connection
        try {
            $this->db = new PDO(
                self::$database['DB_TYPE'] . ':host=' . self::$database['DB_HOST'] . ';dbname=' . self::$database['DB_NAME'] . ';' . $charset,
                self::$database['DB_USER'],
                self::$database['DB_PASS']
            );
        } catch (Exception $e) {
            error_log('Database connection could not be established: ' . $e);
        }
    }

    /**
     * Permite ejecutar una consulta en la base de datos.
     * @param  string     $sql    Consulta a ejecutar
     * @param  array|null $args   Arreglo con los parametros
     * @return object|bool
     */
    public function query(string $sql, array $args = null)
    {
        if (!$args) {
             return $this->query($sql);
        }

        // Debug de la consulta que se esta ejecutando
        if ($this->debug) {
            Helper::debug($sql, $args);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    /**
     * Cerrar la conexion al destruir el objeto
     * @return void
     */
    final public function __destruct()
    {
        $this->db = null;
    }

    /**
     * Evita que el objeto sea clonado
     * @return void
     */
    final public function __clone()
    {
        $this->db = null;
    }

    /**
     * Evita que el objeto sea serializado
     * @return void
     */
    final public function __wakeup()
    {
        $this->db = null;
    }
}