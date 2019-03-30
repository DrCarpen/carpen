<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-24
 */
namespace App\Services;

use App\Model\User;

class IndexService
{
    public function index()
    {
        $user = new User();
        return $user->getUsers();
    }
}