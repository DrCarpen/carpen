<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-24
 */
// 定义必须的目录常量
const ROOT_APP = ROOT.'App/';
const ROOT_APP_CONTROLLERS = ROOT_APP.'Controllers/';
const ROOT_APP_SERVICES = ROOT_APP.'Services/';
const ROOT_APP_MODELS = ROOT_APP.'Models/';
const ROOT_CONFIG = ROOT.'Config/';
const ROOT_DCORE = ROOT.'Dcore/';
const CONTROLLER_NAMESPACE = 'App\Controllers\\';
// 预定义函数
require ROOT_DCORE.'Core/Functions.php';
// 自动加载函数
spl_autoload_register('classAutoLoad', true, true);
