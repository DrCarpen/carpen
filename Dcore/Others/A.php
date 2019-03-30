<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-22
 */
namespace Dcore\Others;

class A extends Base
{
    private $instanceB;

    public function __construct(B $instanceB)
    {
        $this->instanceB = $instanceB;
    }

    public function test()
    {
        $this->instanceB->test();
    }
}