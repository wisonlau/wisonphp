<?php
/**
 * Created by PhpStorm.
 * User: wison
 * Date: 2018/7/20
 */
class Ioc
{
    private static $Logic_Path = './application/logic/';
    private static $instance;

    public static function getInstance($className)
    {
        if (! self::$instance)
        {
            spl_autoload_register( array('Ioc', 'loadClass') );
            $paramArr = self::getMethodParams($className);
            self::$instance = (new ReflectionClass($className))->newInstanceArgs($paramArr);
        }

        return self::$instance;
    }

    public static function make($className, $methodName, $params = [])
    {
        self::getInstance($className);
        $paramArr = self::getMethodParams($className, $methodName);
        return self::$instance->{$methodName}(...array_merge($paramArr, $params));
    }

    protected static function getMethodParams($className, $methodsName = '__construct')
    {
        $class = new ReflectionClass($className);
        $paramArr = [];
        if ($class->hasMethod($methodsName))
        {
            $construct = $class->getMethod($methodsName);
            $params = $construct->getParameters();
            if (count($params) > 0)
            {
                foreach ($params as $key => $param)
                {
                    if ($paramClass = $param->getClass())
                    {
                        $paramClassName = $paramClass->getName();
                        $args = self::getMethodParams($paramClassName);
                        $paramArr[] = (new ReflectionClass($paramClass->getName()))->newInstanceArgs($args);
                    }
                }
            }
        }

        return $paramArr;
    }

    protected static function loadClass($class_name)
    {
        $class_path = self::$Logic_Path . "{$class_name}.php";

        if(file_exists($class_path))
        {
            require_once($class_path);
        }
        else
        {
            echo $class_path . " not be found!";
        }
    }
}