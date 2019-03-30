<?php
/**
 * @author liyang <liyang@uniondrug.cn>
 * @date   2019-03-22
 */
namespace Dcore\Di;

use ReflectionClass;
use Exception;

/**
 * 依赖注入类
 * Class Container
 * @package Dcore\Di
 */
class Container
{
    /**
     * @var array 存储各个类的定义  以类的名称为键
     */
    private $_definitions = [];
    /**
     * @var array 存储各个类实例化需要的参数 以类的名称为键
     */
    private $_params = [];
    /**
     * @var array 存储各个类实例化的引用
     */
    private $_reflections = [];
    /**
     * @var array 各个类依赖的类
     */
    private $_dependencies = [];
    protected static $instance = null;

    /**
     * 单例模式
     * @return null
     */
    public static function one()
    {
        if (static::$instance === null) {
            static::$instance = new Container();
        }
        return static::$instance;
    }

    /**
     * 设置依赖
     * @param       $class      类、方法 名称
     * @param array $defination 类、方法的定义
     * @param array $params     类、方法初始化需要的参数
     * @throws Exception
     */
    public function set($class, $defination = [], $params = [])
    {
        $this->_params[$class] = $params;
        $this->_definitions[$class] = $this->initDefinition($class, $defination);
    }

    /**
     * 获取实例
     * @param       $class      类、方法 名称
     * @param array $params     实例化需要的参数
     * @param array $properties 为实例配置的属性
     * @return array|mixed
     * @throws Exception
     */
    public function get($class, $params = [], $properties = [])
    {
        //如果重来没有声明过 则直接创建
        if (!isset($this->_definitions[$class])) {
            return $this->bulid($class, $params, $properties);
        }
        $defination = $this->_definitions[$class];
        //如果声明是函数
        if (is_callable($defination, true)) {
            $params = $this->parseDependencies($this->mergeParams($class, $params));
            $obj = call_user_func($defination, $this, $params, $properties);
        } else if (is_array($defination)) {
            $originalClass = $defination['class'];
            unset($defination['class']);
            //difinition中除了'class'元素外 其他的都当做实例的属性处理
            $properties = array_merge((array) $defination, $properties);
            //合并该类、函数声明时的参数
            $params = $this->mergeParams($class, $params);
            //如果声明中的class的名称和关键字的名称相同 则直接生成对象
            if ($originalClass === $class) {
                $obj = $this->bulid($class, $params, $properties);
            } else {//如果不同则有可能为别名 则从容器中获取
                $obj = $this->get($originalClass, $params, $properties);
            }
        } else if (is_object($defination)) {
            //如果是个对象 直接返回
            return $defination;
        } else {
            throw new Exception($class.' 声明错误!');
        }
        return $obj;
    }

    /**
     * 合并参数
     * @param string $class  类、函数 名称
     * @param array  $params 参数
     * @return array
     */
    protected function mergeParams($class, $params = [])
    {
        if (empty($this->_params[$class])) {
            return $params;
        }
        if (empty($params)) {
            return $this->_params;
        }
        $result = $this->_params[$class];
        foreach ($params as $key => $value) {
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * 初始化声明
     * @param $class      类、函数 名称
     * @param $defination 类、函数的定义
     * @return array
     * @throws Exception
     */
    protected function initDefinition($class, $defination)
    {
        if (empty($defination)) {
            return ['class' => $class];
        }
        if (is_string($defination)) {
            return ['class' => $defination];
        }
        if (is_callable($defination) || is_object($defination)) {
            return $defination;
        }
        if (is_array($defination)) {
            if (!isset($defination['class'])) {
                $definition['class'] = $class;
            }
            return $defination;
        }
        throw new Exception($class.' 声明错误');
    }

    /**
     * 创建类实例、函数
     * @param $class      类、函数 名称
     * @param $params     初始化时的参数
     * @param $properties 属性
     * @return mixed
     * @throws Exception
     */
    protected function bulid($class, $params, $properties)
    {
        list($reflection, $dependencies) = $this->getDependencies($class);
        foreach ((array) $params as $index => $param) {
            //依赖不仅有对象的依赖 还有普通参数的依赖
            $dependencies[$index] = $param;
        }
        $dependencies = $this->parseDependencies($dependencies, $reflection);
        $obj = $reflection->newInstanceArgs($dependencies);
        if (empty($properties)) {
            return $obj;
        }
        foreach ((array) $properties as $name => $value) {
            $obj->$name = $value;
        }
        return $obj;
    }

    /**
     * 获取依赖
     * @param $class 类、函数 名称
     * @return array
     * @throws \ReflectionException
     */
    protected function getDependencies($class)
    {
        //如果已经实例化过 直接从缓存中获取
        if (isset($this->_reflections[$class])) {
            return [
                $this->_reflections[$class],
                $this->_dependencies[$class]
            ];
        }
        $dependencies = [];
        //获取对象的实例
        $ref = new ReflectionClass($class);
        //获取对象的构造方法
        $constructor = $ref->getConstructor();
        //如果构造方法有参数
        if ($constructor !== null) {
            //获取构造方法的参数
            foreach ($constructor->getParameters() as $param) {
                //如果是默认 直接取默认值
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    //将构造函数中的参数实例化
                    $temp = $param->getClass();
                    $temp = ($temp === null ? null : $temp->getName());
                    //这里使用Instance 类标示需要实例化 并且存储类的名字
                    $temp = Instance::getInstance($temp);
                    $dependencies[] = $temp;
                }
            }
        }
        $this->_reflections[$class] = $ref;
        $this->_dependencies[$class] = $dependencies;
        return [
            $ref,
            $dependencies
        ];
    }

    /**
     * 解析依赖
     * @param      $dependencies 依赖数组
     * @param null $reflection   实例
     * @return mixed
     * @throws Exception
     */
    protected function parseDependencies($dependencies, $reflection = null)
    {
        foreach ((array) $dependencies as $index => $dependency) {
            if ($dependency instanceof Instance) {
                if ($dependency->id !== null) {
                    $dependencies[$index] = $this->get($dependency->id);
                } else if ($reflection !== null) {
                    $parameters = $reflection->getConstructor()->getParameters();
                    $name = $parameters[$index]->getName();
                    $class = $reflection->getName();
                    throw new Exception('实例化类 '.$class.' 时缺少必要参数:'.$name);
                }
            }
        }
        return $dependencies;
    }
}