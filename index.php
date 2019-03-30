<?php
/**
 * Created by PhpStorm.
 * User: liyang
 * Date: 2018/2/3
 * Time: 22:06
 */
/**
 * 想好怎么写一个框架了么
 * 如果没有的话，我们先想一想这个“东西”是用来做什么的
 * 一个入口文件来接受请求，选择路由，处理请求，返回结果
 * PHP框架会在每次接受请求时，定义常量，加载配置文件、基础类，
 * 根据访问的URL进行逻辑判断，选择对应的（模块）控制器和方法，并且自动加载对应类，
 * 处理完请求后，框架会选择并渲染对应的模板文件，以html页面的形式返回响应。
 * 在处理逻辑的时候，还要考虑到错误和异常的处理
 */
const ROOT = __DIR__.'/';
require ROOT.'Dcore/Core/Core.php';
$container = DI();
/**
 * router 处理
 */
$tpRouter = $container->get('Dcore\Router\TpRouter');
$tpRouter->process();


