<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-22
 */
namespace Dcore\Others;

class Base
{
    /**
     * 魔术方法
     * @param string $name
     * @param string $value
     * @return void
     */
    public function __set($name, $value)
    {
        echo 'this is Base';
        //        $this->{$name} = $value;
    }
}