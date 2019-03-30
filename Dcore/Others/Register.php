<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-22
 */
namespace Dcore\Others;

class Register
{
    private $_messageObj;

    public function __construct($mailObj)
    {
        $this->_messageObj = $mailObj;
    }

    public function doRegister()
    {
        $this->_messageObj->send();
    }
}