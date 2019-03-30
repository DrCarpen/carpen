<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-24
 */
namespace Dcore\Request;

class Request implements RequestInterface
{
    public function getRequest()
    {
        $param = $_REQUEST;
        $_param_ = [];
        foreach ($param as $key => $val) {
            $_param_[$key] = $val;
        }
        return $_param_;
    }
}