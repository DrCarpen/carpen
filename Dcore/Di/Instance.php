<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-22
 */
namespace Dcore\Di;

class Instance
{
    /**
     * @var 类唯一标示
     */
    public $id;

    /**
     * 构造函数
     * @param string $id 类唯一ID
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * 获取类的实例
     * @param string $id 类唯一ID
     * @return Object Instance
     */
    public static function getInstance($id)
    {
        return new self($id);
    }
}