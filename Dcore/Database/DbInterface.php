<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 2018/2/3
 * Time: 23:48
 * 数据库处理
 */
namespace Dcore\Database;

interface DbInterface
{
    /**
     * 数据库连接
     * @return mixed
     */
    public function connect();

    /**
     * 查询单条记录
     * @param $sql
     * @return mixed
     */
    public function queryOne($sql);

    /**
     * 查询所有记录
     * @param $sql
     * @return mixed
     */
    public function queryAll($sql);
}