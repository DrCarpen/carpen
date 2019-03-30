<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-22
 */
namespace Dcore\Others;

class Email implements MessageNotice
{
    public function send()
    {
        echo 'send Email loading';
    }
}