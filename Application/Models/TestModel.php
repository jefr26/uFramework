<?php

namespace Application\Models;

use Core\Model;

/**
 * summary
 */
class TestModel extends Model
{

    public $table = 'tabla_pruebas';

    function __construct()
    {
        parent::__construct('default');
    }

    /**
     * summary
     */
    public function testFunction()
    {
        echo '<pre>';

        $qb = $this->db->createQueryBuilder()
              ->select('*')
              ->from('test_table', 't')
              ->where('t.id <= 3');
              //->where('t.data like "%cccc%"');

        var_dump($qb->execute());

        echo "\n--------- fetch single ---------\n";
        var_dump($qb->execute()->fetch(\PDO::FETCH_OBJ));

        echo "\n--------- fetch all ---------\n";
        var_dump($qb->execute()->fetchAll(\PDO::FETCH_OBJ));
        echo '</pre>';
    }
}
