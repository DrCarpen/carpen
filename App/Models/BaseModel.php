<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-24
 */
namespace App\Models;

class BaseModel
{
    public $db;

    public function queryOne($sql)
    {
        return $this->db->queryOne($sql);
    }

    public function __construct()
    {
        $container = DI();
        $db = $container->get('Dcore\Database\MySqli', [
            'localhost',
            'root',
            'root',
            'test'
        ]);
        $this->db = $db;
    }
}