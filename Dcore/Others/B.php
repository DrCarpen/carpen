<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-22
 */
namespace Dcore\Others;

class B extends Base
{
    private $instanceC;

    public function __construct(C $instanceC)
    {
        $this->instanceC = $instanceC;
    }

    public function test()
    {
        return $this->instanceC->test();
    }
}