<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-24
 */
/**
 * 快速方法调用Di
 * @return null
 */
function DI()
{
    return \Dcore\Di\Container::one();
}

/**
 * 自动加载函数
 * @param $class
 * @throws Exception
 */
function classAutoLoad($class)
{
    if (preg_match('/Controller/', $class)) {
        $filePath = ROOT.$class.'.php';
    } else if (preg_match('/Service/', $class)) {
        $filePath = ROOT.$class.'.php';
    } else if (preg_match('/Model/', $class)) {
        $filePath = ROOT.$class.'.php';
    } else {
        $filePath = ROOT.$class.'.php';
    }
    $filePath = str_replace('\\', '/', $filePath);
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        throw new \Exception($filePath.' do not exists,please check in!');
    }
}

/**
 * 调试函数
 * @param $param
 */
function _debug_($param)
{
    echo '<pre>';
    print_r($param);
    die;
}