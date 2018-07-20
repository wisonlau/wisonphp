<?php
/**
 * Created by PhpStorm.
 * User: wison
 * Date: 2018/7/20
 * Time: 9:39
 */

class Di
{
    private static $Container_Path = './application/common/';
    private static $Logic_Path = './application/logic/';
    public function __construct($controller, $method = '')
    {
        spl_autoload_register( array('Di', 'loadClass') );


        $reflectionName = (new ReflectionMethod($controller, '__construct'))->getParameters();
        $app = new Container();
        foreach ($reflectionName as $key => $ReflectionParameter)
        {
            foreach ($ReflectionParameter as $k => $name)
            {
                $app->bind($name, $name);
                $injection[] = $app->make($name);
            }
        }

        $obj = (new ReflectionClass($controller))->newInstanceArgs($injection);
        if ($method)
        {
            return $obj->$method();
        }
        else
        {
            return $this->out($obj);
        }
    }

    function out($obj)
    {
        return $obj;
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