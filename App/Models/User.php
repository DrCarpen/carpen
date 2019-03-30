<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-24
 */
namespace App\Models;

class User extends BaseModel
{

    public function getUsers()
    {
        $sql = 'select * from `users`';
        return $this->queryOne($sql);
    }
}