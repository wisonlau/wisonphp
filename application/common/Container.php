<?php
/**
 * Created by PhpStorm.
 * User: wison
 * Date: 2018/7/20
 * Time: 11:17
 */

class Container {

    protected $bindings = [];
    private static $Container_Path = './application/common/';
    private static $Logic_Path = './application/logic/';

    public function bind($abstract, $concrete=null, $shared=false) {

        if(!$concrete instanceof Closure) {
            $concrete = $this->getClosure($abstract, $concrete);
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    protected function getClosure($abstract, $concrete) {

        return function($c) use ($abstract, $concrete) {
            $method = ($abstract == $concrete) ? 'build' : 'make';
            return $c->$method($concrete);
        };

    }

    public function make($abstract) {
        $concrete = $this->getConcrete($abstract);

        if($this->isBuildable($concrete, $abstract)) {
            $object = $this->build($concrete);
        } else {
            $object = $this->make($concrete);
        }

        return $object;
    }

    protected function isBuildable($concrete, $abstract) {
        return $concrete === $abstract || $concrete instanceof Closure;
    }

    protected function getConcrete($abstract) {
        if(!isset($this->bindings[$abstract])) {
            return $abstract;
        }

        return $this->bindings[$abstract]['concrete'];
    }

    public function build($concrete) {

        if($concrete instanceof Closure) {
            return $concrete($this);
        }

        $reflector = new ReflectionClass($concrete);
        if(!$reflector->isInstantiable()) {
            echo $message = "Target [$concrete] is not instantiable";
        }

        $constructor = $reflector->getConstructor();
        if(is_null($constructor)) {
            spl_autoload_register( array('Container', 'loadClass') );
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instances = $this->getDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    protected function getDependencies($parameters) {
        $dependencies = [];
        foreach($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if(is_null($dependency)) {
                $dependencies[] = NULL;
            } else {
                $dependencies[] = $this->resolveClass($parameter);
            }
        }

        return (array)$dependencies;
    }

    protected function resolveClass(ReflectionParameter $parameter) {
        return $this->make($parameter->getClass()->name);
    }

    function loadClass($class_name)
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