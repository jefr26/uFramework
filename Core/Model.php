<?php

namespace Core;

use Symfony\Component\Yaml\Yaml;

/**
 * uFramework - Simple and Minimal PHP framework
 *
 * @package uFramework
 * @author Jonathan Esquivel
 * @link https://github.com/jefr26/uFramework/
 * @license https://choosealicense.com/licenses/mpl-2.0/ Mozilla Public License 2.0
 */
class Model
{
    /**
     * configuration data
     * @var null
     */
    private $conf = null;

    /**
     * database object
     * @var null
     */
    public $db = null;

    /**
     * load configuration and make the connection
     * @param string $database name of the connection to use
     */
    public function __construct($database = 'default')
    {
        try {
            $file = file_get_contents(ROOT . 'config/database.yml');
        } catch (Exception $e) {
            die("Error loading database configuration file: $e");
        }
        $this->conf = Yaml::parse($file)['database'][$database];
        $this->connection();
    }

    /**
     * make the connection
     * @return object
     */
    private function connection()
    {
        $connParams = array(
            'driver' => $this->conf['type'],
            'host' => $this->conf['server'],
            'port' => $this->conf['port'],
            'dbname' => $this->conf['dbname'],
            'user' => $this->conf['user'],
            'password' => $this->conf['pass'],
            'charset' => !empty($this->conf['charset']) ? $this->conf['charset'] : 'utf8'
        );
        $config = new \Doctrine\DBAL\Configuration();
        $this->db = \Doctrine\DBAL\DriverManager::getConnection($connParams, $config);
    }
}
