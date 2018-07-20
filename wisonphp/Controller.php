<?php 
/**
 * 控制器基类
 */
class Controller
{
    protected $_controller;
    protected $_action;
    protected $_view;
    private static $Container_Path = './application/common/';

    // 构造函数，初始化属性，并实例化对应模型
    public function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_view = new View($controller, $action);
        spl_autoload_register( array('Controller', 'loadClass') );
    }

    // 分配变量
    public function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }

    // 渲染视图
    public function render()
    {
        $this->_view->render();
    }

    function loadClass($class_name)
    {
        $class_path = '';
        if ($class_name == 'Di')
        {
            $class_path = self::$Container_Path . "{$class_name}.php";
        }
        else if ($class_name == 'Ioc')
        {
            $class_path = self::$Container_Path . "{$class_name}.php";
        }

        if(file_exists($class_path))
        {
            require_once($class_path);
        }
    }
}