<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-22
 */
namespace Dcore\Router;

use Exception;
use ReflectionClass;

class TpRouter implements Router
{
    private $patterm = '/\?.*/';

    public function process()
    {
        $url = $this->getUrl();
        $route = $this->getRoute($url);
        $this->setRoute($route);
    }

    private function setRoute($route)
    {
        $controller = CONTROLLER_NAMESPACE.$route->controller;
        try {
            if (!class_exists($controller, true)) {
                throw new Exception('class '.$controller.' is not exists');
            }
            $obj = new ReflectionClass($controller);
            if (!$obj->hasMethod($route->method)) {
                throw new Exception('method '.$route->method.' is not exists');
            }
        } catch(Exception $e) {
            echo $e; //展示错误结果
            return false;
        }
        $newObj = new $controller();
        call_user_func_array([
            $newObj,
            $route->method
        ], $route->params);
    }

    /**
     * 获取URL
     * @return string
     */
    private function getUrl()
    {
        return rtrim(preg_replace($this->patterm, '', $_SERVER['REQUEST_URI']), '/');
    }

    /**
     * 获取参数h
     * @param $url
     * @return object
     */
    private function getRoute($url)
    {
        $obj = (object) [
            'controller' => 'IndexController',
            'method' => 'index',
            'params' => []
        ];
        if (empty($url)) {
            return $obj;
        }
        $params = explode('/', trim($url, '/'));
        $count = count($params);
        if ($count > 1) {
            $obj->controller = $params[0];
            $obj->method = $params[1];
        } else if ($count == 1) {
            $obj->method = $params[0];
        }
        return $obj;
    }
}