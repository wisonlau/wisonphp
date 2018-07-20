<?php
/**
 * Created by PhpStorm.
 * User: wison
 * Date: 2018/7/20
 * use
 * $injection = new Di('PayBill', 'payMyBill');
 * $injection = new Di('PayBill');
 * $injection->payMyBill(1,2,3);
 */

class Di
{
    private static $Container_Path = './application/common/';
    private static $Logic_Path = './application/logic/';
    private static $obj;


    public function __construct($controller, $method = '')
    {
        spl_autoload_register( array('Di', 'loadClass') );


        $app = new Container();
        $reflectionName = (new ReflectionMethod($controller, '__construct'))->getParameters();
        if (count($reflectionName) > 0)
        {
            foreach ($reflectionName as $key => $ReflectionParameter)
            {
                if ($paramClass = $ReflectionParameter->getClass())
                {
                    $app->bind($paramClass->getName(), $paramClass->getName());
                    $injection[] = $app->make($paramClass->getName());
                }
            }

            self::$obj = (new ReflectionClass($controller))->newInstanceArgs($injection);
        }

        if ($method)
        {
            return self::$obj->$method();
        }
        else
        {
            return self::$obj;
        }
    }

    function __call($methodName, $args)
    {
        self::$obj->$methodName();
    }

    function __clone()
    {
    }

    function loadClass($class_name)
    {
        if ($class_name == 'Container')
        {
            $class_path = self::$Container_Path . "{$class_name}.php";
        }
        else
        {
            $class_path = self::$Logic_Path . "{$class_name}.php";
        }

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